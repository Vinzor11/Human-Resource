<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BulkEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder creates 1000 employees with all related data.
     * Run with: php artisan db:seed --class=BulkEmployeeSeeder
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        $this->command->info('Starting bulk employee seeding (1000 employees)...');
        $this->command->info('This may take several minutes...');

        // Get existing departments and positions
        $departments = DB::table('departments')->pluck('id')->toArray();
        $positions = DB::table('positions')->pluck('id')->toArray();
        
        if (empty($departments) || empty($positions)) {
            $this->command->error('No departments or positions found. Please run EmployeeDatabaseSeeder first.');
            return;
        }

        // Filipino surnames and first names for realistic data
        $surnames = [
            'Santos', 'Reyes', 'Cruz', 'Bautista', 'Villanueva', 'Fernandez', 'Garcia', 'Ramos',
            'Torres', 'Lopez', 'Gonzales', 'Mendoza', 'Castillo', 'Castro', 'Aquino', 'Dela Cruz',
            'Rivera', 'Mercado', 'Ocampo', 'Villanueva', 'Alvarez', 'Romero', 'Morales', 'Dela Rosa',
            'Gutierrez', 'Perez', 'Sanchez', 'Martinez', 'Rodriguez', 'Flores', 'Diaz', 'Gomez',
            'Ramos', 'Vargas', 'Jimenez', 'Moreno', 'Herrera', 'Medina', 'Aguilar', 'Salazar',
            'Navarro', 'Ortega', 'Vega', 'Guerrero', 'Mendoza', 'Silva', 'Valdez', 'Cortez',
            'Pena', 'Ramos', 'Molina', 'Espinoza', 'Chavez', 'Campos', 'Rojas', 'Acosta',
            'Padilla', 'Miranda', 'Vasquez', 'Velasco', 'Fuentes', 'Pacheco', 'Soto', 'Mendez',
            'Solis', 'Mejia', 'Herrera', 'Cordero', 'Valencia', 'Montes', 'Serrano', 'Aguirre',
            'Carrillo', 'Escobar', 'Maldonado', 'Suarez', 'Zamora', 'Villarreal', 'Paredes', 'Barrera',
            'Galvan', 'Trujillo', 'Villanueva', 'Cardenas', 'Rios', 'Avila', 'Marquez', 'Rosales',
            'Nunez', 'Ibarra', 'Juarez', 'Macias', 'Andrade', 'Villa', 'Arellano', 'Tapia',
        ];

        $firstNamesMale = [
            'Juan', 'Jose', 'Carlos', 'Miguel', 'Roberto', 'Ricardo', 'Antonio', 'Fernando',
            'Eduardo', 'Manuel', 'Ramon', 'Alberto', 'Francisco', 'Luis', 'Angel', 'Pedro',
            'Daniel', 'Mario', 'Rafael', 'Enrique', 'Alfredo', 'Jorge', 'Sergio', 'Victor',
            'Oscar', 'Raul', 'Hector', 'Julio', 'Andres', 'Felipe', 'Rodrigo', 'Arturo',
            'Gerardo', 'Ignacio', 'Leonardo', 'Marco', 'Nicolas', 'Pablo', 'Quentin', 'Ruben',
            'Sebastian', 'Tomas', 'Ulises', 'Vicente', 'Xavier', 'Yves', 'Zachary', 'Adrian',
            'Benjamin', 'Cesar', 'Diego', 'Emilio', 'Felix', 'Gabriel', 'Hugo', 'Ivan',
        ];

        $firstNamesFemale = [
            'Maria', 'Ana', 'Carmen', 'Patricia', 'Elena', 'Rosa', 'Laura', 'Sofia',
            'Isabel', 'Andrea', 'Monica', 'Gabriela', 'Valeria', 'Daniela', 'Fernanda', 'Alejandra',
            'Beatriz', 'Claudia', 'Diana', 'Esperanza', 'Francisca', 'Gloria', 'Helena', 'Irene',
            'Josefina', 'Karina', 'Lucia', 'Margarita', 'Natalia', 'Olga', 'Paula', 'Querida',
            'Rebecca', 'Silvia', 'Teresa', 'Ursula', 'Veronica', 'Wendy', 'Ximena', 'Yolanda',
            'Zenaida', 'Adriana', 'Bianca', 'Catalina', 'Dolores', 'Esther', 'Felicia', 'Gina',
            'Hilda', 'Ines', 'Julia', 'Katherine', 'Leticia', 'Marcela', 'Nora', 'Olivia',
        ];

        $middleNames = [
            'Cruz', 'Dela Cruz', 'Santos', 'Reyes', 'Garcia', 'Lopez', 'Ramos', 'Torres',
            'Fernandez', 'Villanueva', 'Mendoza', 'Bautista', 'Castillo', 'Castro', 'Aquino',
            'Rivera', 'Mercado', 'Ocampo', 'Alvarez', 'Romero', 'Morales', 'Gutierrez', 'Perez',
            'Sanchez', 'Martinez', 'Rodriguez', 'Flores', 'Diaz', 'Gomez', 'Vargas', 'Jimenez',
        ];

        $cities = [
            'Manila', 'Quezon City', 'Makati', 'Pasig', 'Mandaluyong', 'Taguig', 'Caloocan',
            'Marikina', 'Las Piñas', 'Parañaque', 'Muntinlupa', 'Valenzuela', 'Malabon', 'Navotas',
            'San Juan', 'Pasay', 'Bacoor', 'Dasmarinas', 'Antipolo', 'Cainta', 'San Mateo',
        ];

        $provinces = [
            'Metro Manila', 'Cavite', 'Laguna', 'Rizal', 'Bulacan', 'Pampanga', 'Batangas',
        ];

        $streets = [
            'Rizal Street', 'EDSA', 'Ayala Avenue', 'Ortigas Avenue', 'Shaw Boulevard', 'BGC',
            'Marcos Highway', 'Alabang-Zapote Road', 'Sucat Road', 'Rizal Avenue', 'Quezon Avenue',
            'Commonwealth Avenue', 'España Boulevard', 'Taft Avenue', 'Roxas Boulevard',
        ];

        $subdivisions = [
            'Greenhills', 'Cubao', 'Bel-Air', 'San Antonio', 'Wack-Wack', 'Fort Bonifacio',
            'Grace Park', 'Concepcion', 'Alabang', 'BF Homes', 'Ayala Alabang', 'Forbes Park',
            'Dasmarinas Village', 'Urdaneta Village', 'Magallanes Village',
        ];

        $employeeType = ['Teaching', 'Non-Teaching'];
        $statuses = ['active', 'inactive', 'on-leave'];
        $sex = ['Male', 'Female'];
        $civilStatus = ['Single', 'Married', 'Separated', 'Widowed', 'Annulled'];
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $citizenshipTypes = ['By birth', 'By naturalization'];
        $governmentIds = ['Passport', 'Driver License', 'UMID'];

        $batchSize = 100;
        $totalEmployees = 1000;
        $progressBar = $this->command->getOutput()->createProgressBar($totalEmployees);
        $progressBar->start();

        for ($batch = 0; $batch < ($totalEmployees / $batchSize); $batch++) {
            $employees = [];
            $familyBackgrounds = [];
            $children = [];
            $educationalBackgrounds = [];
            $civilServiceEligibilities = [];
            $workExperiences = [];
            $voluntaryWorks = [];
            $learningDevelopments = [];
            $otherInformations = [];
            $questionnaires = [];
            $references = [];

            for ($i = 0; $i < $batchSize && ($batch * $batchSize + $i) < $totalEmployees; $i++) {
                $empNum = $batch * $batchSize + $i + 1;
                $employeeId = 'EMP' . str_pad($empNum, 4, '0', STR_PAD_LEFT);
                
                $isMale = rand(0, 1);
                $firstName = $isMale 
                    ? $firstNamesMale[array_rand($firstNamesMale)]
                    : $firstNamesFemale[array_rand($firstNamesFemale)];
                $surname = $surnames[array_rand($surnames)];
                $middleName = $middleNames[array_rand($middleNames)];
                $gender = $isMale ? 'Male' : 'Female';
                $civilStat = $civilStatus[array_rand($civilStatus)];
                $isMarried = $civilStat === 'Married';
                
                $birthYear = rand(1965, 2000);
                $birthMonth = rand(1, 12);
                $birthDay = rand(1, 28);
                $birthDate = sprintf('%04d-%02d-%02d', $birthYear, $birthMonth, $birthDay);
                
                $city = $cities[array_rand($cities)];
                $province = $provinces[array_rand($provinces)];
                $street = $streets[array_rand($streets)];
                $subdivision = $subdivisions[array_rand($subdivisions)];
                
                $departmentId = $departments[array_rand($departments)];
                $positionId = $positions[array_rand($positions)];
                
                $employees[] = [
                    'id' => $employeeId,
                    'surname' => $surname,
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'name_extension' => rand(0, 10) === 0 ? (rand(0, 1) ? 'Jr.' : 'Sr.') : null,
                    'status' => $statuses[array_rand($statuses)],
                    'employee_type' => $employeeType[array_rand($employeeType)],
                    'department_id' => $departmentId,
                    'position_id' => $positionId,
                    'birth_date' => $birthDate,
                    'birth_place' => $city,
                    'sex' => $gender,
                    'civil_status' => $civilStat,
                    'height_m' => round(($isMale ? rand(160, 190) : rand(150, 175)) / 100, 2),
                    'weight_kg' => round(($isMale ? rand(60, 100) : rand(45, 80)) + (rand(0, 9) / 10), 1),
                    'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                    'gsis_id_no' => 'GSIS' . rand(100000, 999999),
                    'pagibig_id_no' => 'PAGIBIG' . rand(100000, 999999),
                    'philhealth_no' => 'PHIL' . rand(100000, 999999),
                    'sss_no' => 'SSS' . rand(100000, 999999),
                    'tin_no' => 'TIN' . rand(100000, 999999),
                    'agency_employee_no' => 'AGENCY' . str_pad($empNum, 4, '0', STR_PAD_LEFT),
                    'citizenship' => 'Filipino',
                    'dual_citizenship' => rand(0, 10) === 0,
                    'citizenship_type' => $citizenshipTypes[array_rand($citizenshipTypes)],
                    'dual_citizenship_country' => rand(0, 10) === 0 ? ['USA', 'Canada', 'UK', 'Australia'][rand(0, 3)] : null,
                    'res_house_no' => rand(1, 999),
                    'res_street' => $street,
                    'res_subdivision' => $subdivision,
                    'res_barangay' => 'Barangay ' . rand(1, 200),
                    'res_city' => $city,
                    'res_province' => $province,
                    'res_zip_code' => rand(1000, 9999),
                    'perm_house_no' => rand(1, 999),
                    'perm_street' => $street,
                    'perm_subdivision' => $subdivision,
                    'perm_barangay' => 'Barangay ' . rand(1, 200),
                    'perm_city' => $city,
                    'perm_province' => $province,
                    'perm_zip_code' => rand(1000, 9999),
                    'telephone_no' => '02' . rand(1000000, 9999999),
                    'mobile_no' => '09' . rand(100000000, 999999999),
                    'email_address' => strtolower($firstName . '.' . $surname . '@university.edu'),
                    'government_issued_id' => $governmentIds[array_rand($governmentIds)],
                    'id_number' => strtoupper(substr($governmentIds[array_rand($governmentIds)], 0, 2)) . rand(100000, 999999),
                    'id_date_issued' => date('Y-m-d', strtotime('-' . rand(1, 15) . ' years')),
                    'id_place_of_issue' => $city,
                    'indigenous_group' => rand(0, 20) === 0 ? ['Igorot', 'Lumad', 'Mangyan', 'Aeta'][rand(0, 3)] : null,
                    'pwd_id_no' => rand(0, 30) === 0 ? 'PWD' . rand(100000, 999999) : null,
                    'solo_parent_id_no' => ($isMarried && rand(0, 20) === 0) ? 'SP' . rand(100000, 999999) : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Family Backgrounds
                $familyBackgrounds[] = [
                    'employee_id' => $employeeId,
                    'relation' => 'Father',
                    'surname' => $surname,
                    'first_name' => 'Father' . substr($firstName, 0, 3),
                    'middle_name' => $middleName,
                    'name_extension' => null,
                    'occupation' => ['Engineer', 'Doctor', 'Teacher', 'Businessman', 'Retired', 'Farmer'][rand(0, 5)],
                    'employer' => 'Company ' . rand(1, 500),
                    'business_address' => $city,
                    'telephone_no' => '02' . rand(1000000, 9999999),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $familyBackgrounds[] = [
                    'employee_id' => $employeeId,
                    'relation' => 'Mother',
                    'surname' => $surname,
                    'first_name' => 'Mother' . substr($firstName, 0, 3),
                    'middle_name' => $middleName,
                    'name_extension' => null,
                    'occupation' => ['Nurse', 'Teacher', 'Housewife', 'Accountant', 'Businesswoman', 'Retired'][rand(0, 5)],
                    'employer' => 'Company ' . rand(1, 500),
                    'business_address' => $city,
                    'telephone_no' => '02' . rand(1000000, 9999999),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if ($isMarried) {
                    $familyBackgrounds[] = [
                        'employee_id' => $employeeId,
                        'relation' => 'Spouse',
                        'surname' => $surnames[array_rand($surnames)],
                        'first_name' => ($isMale ? $firstNamesFemale : $firstNamesMale)[array_rand($isMale ? $firstNamesFemale : $firstNamesMale)],
                        'middle_name' => $middleNames[array_rand($middleNames)],
                        'name_extension' => null,
                        'occupation' => ['Engineer', 'Doctor', 'Teacher', 'Nurse', 'Accountant', 'Businessman'][rand(0, 5)],
                        'employer' => 'Company ' . rand(1, 500),
                        'business_address' => $city,
                        'telephone_no' => '02' . rand(1000000, 9999999),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Children
                    $numChildren = rand(1, 3);
                    for ($j = 1; $j <= $numChildren; $j++) {
                        $children[] = [
                            'employee_id' => $employeeId,
                            'full_name' => 'Child ' . $j . ' ' . $surname,
                            'birth_date' => date('Y-m-d', strtotime('-' . rand(1, 25) . ' years')),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                // Educational Backgrounds
                $levels = ['Elementary', 'Secondary', 'Vocational', 'College', 'Graduate Studies'];
                $numEdu = rand(3, 5);
                for ($j = 0; $j < $numEdu; $j++) {
                    $level = $levels[$j] ?? $levels[rand(0, count($levels) - 1)];
                    $yearGraduated = date('Y') - rand(5, 40);
                    
                    if ($level === 'Graduate Studies') {
                        $periodFrom = $yearGraduated - rand(2, 5);
                    } elseif ($level === 'College') {
                        $periodFrom = $yearGraduated - rand(4, 6);
                    } elseif ($level === 'Vocational') {
                        $periodFrom = $yearGraduated - rand(1, 2);
                    } elseif ($level === 'Secondary') {
                        $periodFrom = $yearGraduated - rand(4, 6);
                    } else {
                        $periodFrom = $yearGraduated - rand(6, 7);
                    }
                    
                    $educationalBackgrounds[] = [
                        'employee_id' => $employeeId,
                        'level' => $level,
                        'school_name' => $level . ' School ' . rand(1, 500),
                        'degree_course' => ($level === 'College' || $level === 'Graduate Studies') 
                            ? ['BS Computer Science', 'BS Engineering', 'BA English', 'BS Biology', 'MA Education', 'PhD Mathematics', 'BS Business Administration', 'BS Accountancy'][rand(0, 7)]
                            : null,
                        'period_from' => $periodFrom . '-06-01',
                        'period_to' => $yearGraduated . '-03-31',
                        'highest_level_units' => ($level === 'College' || $level === 'Graduate Studies') 
                            ? rand(100, 200) . ' units' 
                            : null,
                        'year_graduated' => $yearGraduated,
                        'honors_received' => rand(0, 5) === 0 
                            ? ['Cum Laude', 'Magna Cum Laude', 'Summa Cum Laude', 'With Honors'][rand(0, 3)]
                            : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Civil Service Eligibilities
                $numElig = rand(0, 2);
                for ($j = 0; $j < $numElig; $j++) {
                    $civilServiceEligibilities[] = [
                        'employee_id' => $employeeId,
                        'eligibility' => ['Professional', 'Subprofessional', 'RA 1080', 'CSEE', 'CES'][rand(0, 4)],
                        'rating' => rand(80, 99) . '.' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT),
                        'exam_date' => date('Y-m-d', strtotime('-' . rand(1, 15) . ' years')),
                        'exam_place' => $city,
                        'license_no' => 'LIC' . rand(100000, 999999),
                        'license_validity' => date('Y-m-d', strtotime('+' . rand(1, 10) . ' years')),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Work Experiences
                $numWork = rand(2, 5);
                for ($j = 0; $j < $numWork; $j++) {
                    $dateFrom = date('Y-m-d', strtotime('-' . rand(5, 20) . ' years'));
                    $dateTo = rand(0, 1) ? date('Y-m-d', strtotime($dateFrom . ' +' . rand(1, 8) . ' years')) : null;
                    
                    $workExperiences[] = [
                        'employee_id' => $employeeId,
                        'position_title' => ['Manager', 'Supervisor', 'Specialist', 'Analyst', 'Engineer', 'Teacher', 'Clerk', 'Assistant'][rand(0, 7)],
                        'company_name' => 'Company ' . rand(1, 1000),
                        'company_address' => $city,
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'monthly_salary' => rand(15000, 120000),
                        'salary_grade_step' => rand(1, 33) . '-' . rand(1, 8),
                        'status_of_appointment' => ['Permanent', 'Contractual', 'Probationary', 'Part-time'][rand(0, 3)],
                        'is_gov_service' => rand(0, 1),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Voluntary Works
                $numVol = rand(0, 3);
                for ($j = 0; $j < $numVol; $j++) {
                    $dateFrom = date('Y-m-d', strtotime('-' . rand(2, 12) . ' years'));
                    $dateTo = rand(0, 1) ? date('Y-m-d', strtotime($dateFrom . ' +' . rand(1, 4) . ' years')) : null;
                    
                    $voluntaryWorks[] = [
                        'employee_id' => $employeeId,
                        'organization_name' => 'Organization ' . rand(1, 200),
                        'organization_address' => $city,
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'hours_rendered' => rand(50, 800),
                        'position_or_nature' => ['Volunteer', 'Coordinator', 'Trainer', 'Facilitator', 'Member'][rand(0, 4)],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Learning Developments
                $numLearning = rand(3, 10);
                for ($j = 0; $j < $numLearning; $j++) {
                    $dateFrom = date('Y-m-d', strtotime('-' . rand(1, 6) . ' years'));
                    $dateTo = date('Y-m-d', strtotime($dateFrom . ' +' . rand(1, 21) . ' days'));
                    
                    $learningDevelopments[] = [
                        'employee_id' => $employeeId,
                        'title' => 'Training ' . ['Workshop', 'Seminar', 'Course', 'Program', 'Bootcamp'][rand(0, 4)] . ' ' . rand(1, 500),
                        'date_from' => $dateFrom,
                        'date_to' => $dateTo,
                        'hours' => rand(4, 48),
                        'type_of_ld' => ['Managerial', 'Supervisory', 'Technical', 'Foundation', 'Others'][rand(0, 4)],
                        'conducted_by' => 'Organization ' . rand(1, 300),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Other Information
                $otherInformations[] = [
                    'employee_id' => $employeeId,
                    'skill_or_hobby' => ['Programming', 'Painting', 'Singing', 'Dancing', 'Sports', 'Writing', 'Photography', 'Cooking', 'Reading'][rand(0, 8)],
                    'non_academic_distinctions' => rand(0, 3) === 0 ? 'Award ' . rand(1, 50) : null,
                    'memberships' => rand(0, 2) === 0 ? 'Member of ' . ['Professional Association', 'Sports Club', 'Cultural Group', 'Charity Organization'][rand(0, 3)] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Questionnaires
                for ($j = 34; $j <= 41; $j++) {
                    $answer = rand(0, 1);
                    $questionnaires[] = [
                        'employee_id' => $employeeId,
                        'question_number' => $j,
                        'answer' => $answer,
                        'details' => $answer ? 'Details for question ' . $j : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // References
                for ($j = 1; $j <= 3; $j++) {
                    $references[] = [
                        'employee_id' => $employeeId,
                        'first_name' => $firstNamesMale[array_rand($firstNamesMale)],
                        'middle_initial' => chr(rand(65, 90)),
                        'surname' => $surnames[array_rand($surnames)],
                        'address' => $city,
                        'telephone_no' => '02' . rand(1000000, 9999999),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // Batch insert
            DB::table('employees')->insert($employees);
            if (!empty($familyBackgrounds)) DB::table('employee_family_backgrounds')->insert($familyBackgrounds);
            if (!empty($children)) DB::table('employee_childrens')->insert($children);
            if (!empty($educationalBackgrounds)) DB::table('employee_educational_backgrounds')->insert($educationalBackgrounds);
            if (!empty($civilServiceEligibilities)) DB::table('employee_civil_service_eligibilities')->insert($civilServiceEligibilities);
            if (!empty($workExperiences)) DB::table('employee_work_experiences')->insert($workExperiences);
            if (!empty($voluntaryWorks)) DB::table('employee_voluntary_works')->insert($voluntaryWorks);
            if (!empty($learningDevelopments)) DB::table('employee_learning_developments')->insert($learningDevelopments);
            if (!empty($otherInformations)) DB::table('employee_other_information')->insert($otherInformations);
            if (!empty($questionnaires)) DB::table('questionnaires')->insert($questionnaires);
            if (!empty($references)) DB::table('references')->insert($references);

            $progressBar->advance($batchSize);
        }

        $progressBar->finish();
        $this->command->newLine(2);

        // Re-enable foreign key checks
        if (in_array($driver, ['mysql', 'mariadb'])) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('✅ Successfully seeded 1000 employees with all related data!');
    }
}
