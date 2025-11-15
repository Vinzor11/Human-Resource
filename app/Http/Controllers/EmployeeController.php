<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeChildren;
use App\Models\EmployeeEducationalBackground;
use App\Models\EmployeeFamilyBackground;
use App\Models\EmployeeCivilServiceEligibility;
use App\Models\EmployeeLearningDevelopment;
use App\Models\EmployeeOtherInformation;
use App\Models\EmployeeVoluntaryWork;
use App\Models\EmployeeWorkExperience;
use App\Models\Department;
use App\Models\Position;
use App\Models\Questionnaire;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $employees = Employee::with([
            'familyBackground',
            'children',
            'educationalBackground',
            'civilServiceEligibility',
            'workExperience',
            'voluntaryWork',
            'learningDevelopment',
            'questionnaire',
            'references',
            'department',
            'position',
            'otherInformation',
        ])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('surname', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'asc')
        ->paginate($perPage)
        ->withQueryString();

        $employees->getCollection()->transform(function ($employee) {
            foreach ($employee->getRelations() as $relation => $value) {
                if ($value === null) {
                    $employee->setRelation(
                        $relation,
                        in_array($relation, ['department', 'position', 'otherInformation'])
                            ? (object)[]
                            : []
                    );
                }
            }
            return $employee;
        });

        return Inertia::render('employees/index', [
            'employees' => [
                'data' => $employees->items(),
                'links' => $employees->links()->elements,
                'meta' => [
                    'current_page' => $employees->currentPage(),
                    'from' => $employees->firstItem(),
                    'to' => $employees->lastItem(),
                    'total' => $employees->total(),
                    'last_page' => $employees->lastPage(),
                    'per_page' => $employees->perPage(),
                ]
            ],
            'filters' => [
                'search' => $search,
                'per_page' => $perPage
            ],
            'departments' => Department::select('id', 'faculty_name as name')->get(),
            'positions' => Position::select('id', 'pos_name as name')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('employees/Create', [
            'departments' => Department::select('id', 'faculty_name')->get(),
            'positions' => Position::select('id', 'pos_name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->employeeValidationRules());

        DB::transaction(function () use ($request, $validated) {
            $employee = Employee::create($validated);
            $this->handleRelatedData($request, $employee);
        });

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

     public function show(Employee $employee)
    {
        $employee->load([
            'familyBackground',
            'children',
            'educationalBackground',
            'civilServiceEligibility',
            'workExperience',
            'voluntaryWork',
            'learningDevelopment',
            'otherInformation',
            'questionnaire',
            'references',
            'department',
            'position'
        ]);

        $employeeData = [
            ...$employee->toArray(),
            'family_background' => $employee->familyBackground->isEmpty() ? [
                [ 'relation' => 'Father', 'surname' => '', 'first_name' => '', 'middle_name' => '', 'name_extension' => '', 'occupation' => '', 'employer' => '', 'business_address' => '', 'telephone_no' => '' ],
                [ 'relation' => 'Mother', 'surname' => '', 'first_name' => '', 'middle_name' => '', 'name_extension' => '', 'occupation' => '', 'employer' => '', 'business_address' => '', 'telephone_no' => '' ]
            ] : $employee->familyBackground->toArray(),
            'children' => $employee->children->toArray(),
            'educational_background' => $employee->educationalBackground->toArray(),
            'civil_service_eligibility' => $employee->civilServiceEligibility->toArray(),
            'work_experience' => $employee->workExperience->toArray(),
            'voluntary_work' => $employee->voluntaryWork->toArray(),
            'learning_development' => $employee->learningDevelopment->toArray(),
            'questionnaire' => $employee->questionnaire->isEmpty() ? collect(range(34, 41))->map(fn($num) => ['question_number' => $num, 'answer' => false, 'details' => '']) : $employee->questionnaire->toArray(),
            'references' => $employee->references->toArray(),
            'other_information' => $employee->otherInformation ?? (object)['skill_or_hobby' => '', 'non_academic_distinctions' => '', 'memberships' => '']
        ];

        return Inertia::render('employees/Create', [
            'employee' => $employeeData,
            'departments' => Department::select('id', 'faculty_name as name')->get(),
            'positions' => Position::select('id', 'pos_name as name')->get(),
            'mode' => 'view' // Pass view mode to component
        ]);
    }
    public function edit(Employee $employee)
    {
        $employee->load([
            'familyBackground',
            'children',
            'educationalBackground',
            'civilServiceEligibility',
            'workExperience',
            'voluntaryWork',
            'learningDevelopment',
            'questionnaire',
            'references',
            'department',
            'position',
            'otherInformation',
        ]);

        $employeeData = [
            ...$employee->toArray(),
            'family_background' => $employee->familyBackground->isEmpty() ? [
                [ 'relation' => 'Father', 'surname' => '', 'first_name' => '', 'middle_name' => '', 'name_extension' => '', 'occupation' => '', 'employer' => '', 'business_address' => '', 'telephone_no' => '' ],
                [ 'relation' => 'Mother', 'surname' => '', 'first_name' => '', 'middle_name' => '', 'name_extension' => '', 'occupation' => '', 'employer' => '', 'business_address' => '', 'telephone_no' => '' ]
            ] : $employee->familyBackground->toArray(),
            'children' => $employee->children->toArray(),
            'educational_background' => $employee->educationalBackground->toArray(),
            'civil_service_eligibility' => $employee->civilServiceEligibility->toArray(),
            'work_experience' => $employee->workExperience->toArray(),
            'voluntary_work' => $employee->voluntaryWork->toArray(),
            'learning_development' => $employee->learningDevelopment->toArray(),
            'questionnaire' => $employee->questionnaire->isEmpty() ? collect(range(34, 41))->map(fn($num) => ['question_number' => $num, 'answer' => false, 'details' => '']) : $employee->questionnaire->toArray(),
            'references' => $employee->references->toArray(),
            'other_information' => $employee->otherInformation ?? (object)['skill_or_hobby' => '', 'non_academic_distinctions' => '', 'memberships' => '']
        ];

        return Inertia::render('employees/Create', [
            'employee' => $employeeData,
            'departments' => Department::select('id', 'faculty_name as name')->get(),
            'positions' => Position::select('id', 'pos_name as name')->get(),
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate($this->employeeValidationRules(false));

        DB::transaction(function () use ($request, $employee, $validated) {
            $employee->update($validated);
            $this->handleRelatedData($request, $employee, true);
        });

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function () use ($employee) {
            $employee->children()->delete();
            $employee->educationalBackground()->delete();
            $employee->civilServiceEligibility()->delete();
            $employee->familyBackground()->delete();
            $employee->learningDevelopment()->delete();
            $employee->otherInformation()->delete();
            $employee->questionnaire()->delete();
            $employee->references()->delete();
            $employee->voluntaryWork()->delete();
            $employee->workExperience()->delete();

            $employee->delete();
        });

        return redirect()->route('employees.index')->with('success', 'Employee and all related records deleted successfully.');
    }

    protected function handleRelatedData(Request $request, Employee $employee, $isUpdate = false)
    {
        if ($request->has('family_background')) {
            foreach ($request->input('family_background') as $family) {
                $employee->familyBackground()->updateOrCreate(
                    ['relation' => $family['relation']],
                    $family
                );
            }
        }

        if ($isUpdate) $employee->children()->delete();
        if (is_array($request->input('children'))) {
            $employee->children()->createMany($request->input('children'));
        }

        if ($isUpdate) $employee->educationalBackground()->delete();
        if (is_array($request->input('educational_background'))) {
            $employee->educationalBackground()->createMany($request->input('educational_background'));
        }

        if ($isUpdate) $employee->civilServiceEligibility()->delete();
        if (is_array($request->input('civil_service_eligibility'))) {
            $employee->civilServiceEligibility()->createMany($request->input('civil_service_eligibility'));
        }

        if ($isUpdate) $employee->workExperience()->delete();
        if (is_array($request->input('work_experience'))) {
            $employee->workExperience()->createMany($request->input('work_experience'));
        }

        if ($isUpdate) $employee->voluntaryWork()->delete();
        if (is_array($request->input('voluntary_work'))) {
            $employee->voluntaryWork()->createMany($request->input('voluntary_work'));
        }

        if ($isUpdate) $employee->learningDevelopment()->delete();
        if (is_array($request->input('learning_development'))) {
            $employee->learningDevelopment()->createMany($request->input('learning_development'));
        }

        if ($request->has('other_information')) {
            $employee->otherInformation()->updateOrCreate(
                ['employee_id' => $employee->id],
                $request->input('other_information')
            );
        }

        if ($request->has('questionnaire')) {
            foreach ($request->input('questionnaire') as $question) {
                $employee->questionnaire()->updateOrCreate(
                    ['question_number' => $question['question_number']],
                    $question
                );
            }
        }

        if ($isUpdate) $employee->references()->delete();
        if (is_array($request->input('references'))) {
            $employee->references()->createMany($request->input('references'));
        }
    }

    protected function employeeValidationRules(bool $isCreate = true): array
    {
        return array_merge([
            'surname' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'name_extension' => 'nullable|string|max:5',
            'status' => 'required|in:active,inactive,on-leave',
            'employee_type' => 'required|in:Teaching,Non-Teaching',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'birth_date' => 'required|date',
            'birth_place' => 'required|string|max:100',
            'sex' => 'required|in:Male,Female',
            'civil_status' => 'required|string|max:15',
            'height_m' => 'required|numeric|between:0,999.99',
            'weight_kg' => 'required|numeric|between:0,9999.99',
            'blood_type' => 'nullable|string|max:3',
            'gsis_id_no' => 'nullable|string|max:25',
            'pagibig_id_no' => 'nullable|string|max:25',
            'philhealth_no' => 'nullable|string|max:25',
            'sss_no' => 'nullable|string|max:25',
            'tin_no' => 'nullable|string|max:25',
            'agency_employee_no' => 'nullable|string|max:25',
            'citizenship' => 'required|string|max:30',
            'dual_citizenship' => 'required|boolean',
            'citizenship_type' => 'nullable|in:By birth,By naturalization',
            'dual_citizenship_country' => 'nullable|string|max:50',
            'res_house_no' => 'nullable|string|max:15',
            'res_street' => 'nullable|string|max:50',
            'res_subdivision' => 'nullable|string|max:50',
            'res_barangay' => 'nullable|string|max:50',
            'res_city' => 'nullable|string|max:50',
            'res_province' => 'nullable|string|max:50',
            'res_zip_code' => 'nullable|string|max:10',
            'perm_house_no' => 'nullable|string|max:15',
            'perm_street' => 'nullable|string|max:50',
            'perm_subdivision' => 'nullable|string|max:50',
            'perm_barangay' => 'nullable|string|max:50',
            'perm_city' => 'nullable|string|max:50',
            'perm_province' => 'nullable|string|max:50',
            'perm_zip_code' => 'nullable|string|max:10',
            'telephone_no' => 'nullable|string|max:20',
            'mobile_no' => 'nullable|string|max:20',
            'email_address' => 'nullable|string|email|max:80',
            'government_issued_id' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:25',
            'id_date_issued' => 'nullable|date',
            'id_place_of_issue' => 'nullable|string|max:100',
            'indigenous_group' => 'nullable|string|max:50',
            'pwd_id_no' => 'nullable|string|max:50',
            'solo_parent_id_no' => 'nullable|string|max:50',
        ], $isCreate ? [
            'id' => 'required|string|max:15|unique:employees'
        ] : []);
    }
}
