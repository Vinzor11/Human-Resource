<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy('created_at', 'asc')->paginate(10);

        return Inertia::render('departments/index', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'faculty_code' => 'required|string|max:10',
            'faculty_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Department::create([
            'faculty_code' => $request->faculty_code,
            'faculty_name' => $request->faculty_name,
            'description' => $request->description,
        ]);

        return redirect()->route('departments.index')->with('success', 'Department created successfully!');
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'faculty_code' => 'required|string|max:10',
            'faculty_name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $department->update([
            'faculty_code' => $request->faculty_code,
            'faculty_name' => $request->faculty_name,
            'description' => $request->description,
            
        ]);

        return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
    }
}
