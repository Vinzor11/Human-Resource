<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks temporarily
        $driver = DB::connection()->getDriverName();
        if (in_array($driver, ['mysql', 'mariadb'])) {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // 1. Seed Faculties
        $this->command->info('Seeding Faculties...');
        $faculties = [
            [
                'code' => 'CIT',
                'name' => 'College of Information Technology',
                'description' => 'Leading institution in computer science and information technology education',
                'type' => 'academic',
                'status' => 'active',
            ],
            [
                'code' => 'COE',
                'name' => 'College of Engineering',
                'description' => 'Excellence in engineering education and research',
                'type' => 'academic',
                'status' => 'active',
            ],
            [
                'code' => 'CBA',
                'name' => 'College of Business Administration',
                'description' => 'Preparing future business leaders and entrepreneurs',
                'type' => 'academic',
                'status' => 'active',
            ],
        ];

        $facultyIds = [];
        foreach ($faculties as $faculty) {
            $existing = DB::table('faculties')->where('code', $faculty['code'])->first();
            if ($existing) {
                $id = $existing->id;
            } else {
                $id = DB::table('faculties')->insertGetId(array_merge($faculty, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            $facultyIds[$faculty['code']] = $id;
            $this->command->line("  ✓ " . ($existing ? 'Updated' : 'Created') . " faculty: {$faculty['name']}");
        }

        // 2. Seed Departments (Academic and Administrative)
        $this->command->info('Seeding Departments...');
        $departments = [
            // Academic Departments under CIT
            [
                'faculty_id' => $facultyIds['CIT'],
                'faculty_code' => 'CS',
                'faculty_name' => 'Computer Science',
                'type' => 'academic',
                'description' => 'Department of Computer Science',
            ],
            [
                'faculty_id' => $facultyIds['CIT'],
                'faculty_code' => 'IT',
                'faculty_name' => 'Information Technology',
                'type' => 'academic',
                'description' => 'Department of Information Technology',
            ],
            // Academic Departments under COE
            [
                'faculty_id' => $facultyIds['COE'],
                'faculty_code' => 'CE',
                'faculty_name' => 'Civil Engineering',
                'type' => 'academic',
                'description' => 'Department of Civil Engineering',
            ],
            [
                'faculty_id' => $facultyIds['COE'],
                'faculty_code' => 'EE',
                'faculty_name' => 'Electrical Engineering',
                'type' => 'academic',
                'description' => 'Department of Electrical Engineering',
            ],
            // Academic Departments under CBA
            [
                'faculty_id' => $facultyIds['CBA'],
                'faculty_code' => 'ACC',
                'faculty_name' => 'Accountancy',
                'type' => 'academic',
                'description' => 'Department of Accountancy',
            ],
            [
                'faculty_id' => $facultyIds['CBA'],
                'faculty_code' => 'MGT',
                'faculty_name' => 'Management',
                'type' => 'academic',
                'description' => 'Department of Management',
            ],
            // Administrative Offices
            [
                'faculty_id' => null,
                'faculty_code' => 'HR',
                'faculty_name' => 'Human Resources Office',
                'type' => 'administrative',
                'description' => 'Human Resources and Personnel Management',
            ],
            [
                'faculty_id' => null,
                'faculty_code' => 'REG',
                'faculty_name' => 'Registrar\'s Office',
                'type' => 'administrative',
                'description' => 'Student Records and Registration',
            ],
            [
                'faculty_id' => null,
                'faculty_code' => 'FIN',
                'faculty_name' => 'Finance Office',
                'type' => 'administrative',
                'description' => 'Financial Management and Accounting',
            ],
        ];

        $departmentIds = [];
        foreach ($departments as $dept) {
            $existing = DB::table('departments')->where('faculty_code', $dept['faculty_code'])->first();
            if ($existing) {
                $id = $existing->id;
                DB::table('departments')->where('id', $id)->update(array_merge($dept, [
                    'updated_at' => now(),
                ]));
            } else {
                $id = DB::table('departments')->insertGetId(array_merge($dept, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            $departmentIds[$dept['faculty_code']] = $id;
            $this->command->line("  ✓ " . ($existing ? 'Updated' : 'Created') . " department: {$dept['faculty_name']}");
        }

        // 3. Seed Positions
        $this->command->info('Seeding Positions...');
        $positions = [
            // Faculty-level positions
            [
                'pos_code' => 'DEAN-CIT', 
                'pos_name' => 'Dean - College of IT', 
                'description' => 'Dean of College of Information Technology', 
                'faculty_id' => $facultyIds['CIT'], 
                'department_id' => null, 
                'hierarchy_level' => 10, 
                'position_type' => 'faculty_leadership',
                'position_category' => 'executive',
                'creation_type' => 'manual',
                'slug' => Str::slug('Dean - College of IT'),
            ],
            [
                'pos_code' => 'DEAN-COE', 
                'pos_name' => 'Dean - College of Engineering', 
                'description' => 'Dean of College of Engineering', 
                'faculty_id' => $facultyIds['COE'], 
                'department_id' => null, 
                'hierarchy_level' => 10, 
                'position_type' => 'faculty_leadership',
                'position_category' => 'executive',
                'creation_type' => 'manual',
                'slug' => Str::slug('Dean - College of Engineering'),
            ],
            [
                'pos_code' => 'DEAN-CBA', 
                'pos_name' => 'Dean - College of Business', 
                'description' => 'Dean of College of Business Administration', 
                'faculty_id' => $facultyIds['CBA'], 
                'department_id' => null, 
                'hierarchy_level' => 10, 
                'position_type' => 'faculty_leadership',
                'position_category' => 'executive',
                'creation_type' => 'manual',
                'slug' => Str::slug('Dean - College of Business'),
            ],
            
            // Department-level positions
            [
                'pos_code' => 'CHAIR-CS', 
                'pos_name' => 'Department Chair - Computer Science', 
                'description' => 'Chairperson of Computer Science Department', 
                'faculty_id' => $facultyIds['CIT'], 
                'department_id' => $departmentIds['CS'], 
                'hierarchy_level' => 8, 
                'position_type' => 'department_leadership',
                'position_category' => 'academic_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Department Chair - Computer Science'),
            ],
            [
                'pos_code' => 'PROF', 
                'pos_name' => 'Professor', 
                'description' => 'Full Professor', 
                'faculty_id' => null, 
                'department_id' => null, 
                'hierarchy_level' => 7, 
                'position_type' => 'academic',
                'position_category' => 'academic_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Professor'),
            ],
            [
                'pos_code' => 'ASSP', 
                'pos_name' => 'Associate Professor', 
                'description' => 'Associate Professor', 
                'faculty_id' => null, 
                'department_id' => null, 
                'hierarchy_level' => 6, 
                'position_type' => 'academic',
                'position_category' => 'academic_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Associate Professor'),
            ],
            [
                'pos_code' => 'ASST', 
                'pos_name' => 'Assistant Professor', 
                'description' => 'Assistant Professor', 
                'faculty_id' => null, 
                'department_id' => null, 
                'hierarchy_level' => 5, 
                'position_type' => 'academic',
                'position_category' => 'academic_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Assistant Professor'),
            ],
            [
                'pos_code' => 'INST', 
                'pos_name' => 'Instructor', 
                'description' => 'Instructor', 
                'faculty_id' => null, 
                'department_id' => null, 
                'hierarchy_level' => 4, 
                'position_type' => 'academic',
                'position_category' => 'academic_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Instructor'),
            ],
            
            // Administrative positions
            [
                'pos_code' => 'HR-MGR', 
                'pos_name' => 'HR Manager', 
                'description' => 'Human Resources Manager', 
                'faculty_id' => null, 
                'department_id' => $departmentIds['HR'], 
                'hierarchy_level' => 7, 
                'position_type' => 'administrative',
                'position_category' => 'administrative_non_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('HR Manager'),
            ],
            [
                'pos_code' => 'HR-OFF', 
                'pos_name' => 'HR Officer', 
                'description' => 'Human Resources Officer', 
                'faculty_id' => null, 
                'department_id' => $departmentIds['HR'], 
                'hierarchy_level' => 5, 
                'position_type' => 'administrative',
                'position_category' => 'administrative_non_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('HR Officer'),
            ],
            [
                'pos_code' => 'REG-MGR', 
                'pos_name' => 'Registrar', 
                'description' => 'University Registrar', 
                'faculty_id' => null, 
                'department_id' => $departmentIds['REG'], 
                'hierarchy_level' => 8, 
                'position_type' => 'administrative',
                'position_category' => 'administrative_non_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Registrar'),
            ],
            [
                'pos_code' => 'REG-OFF', 
                'pos_name' => 'Registration Officer', 
                'description' => 'Registration Officer', 
                'faculty_id' => null, 
                'department_id' => $departmentIds['REG'], 
                'hierarchy_level' => 5, 
                'position_type' => 'administrative',
                'position_category' => 'administrative_non_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Registration Officer'),
            ],
            [
                'pos_code' => 'FIN-MGR', 
                'pos_name' => 'Finance Manager', 
                'description' => 'Finance Manager', 
                'faculty_id' => null, 
                'department_id' => $departmentIds['FIN'], 
                'hierarchy_level' => 7, 
                'position_type' => 'administrative',
                'position_category' => 'administrative_non_teaching',
                'creation_type' => 'manual',
                'slug' => Str::slug('Finance Manager'),
            ],
            [
                'pos_code' => 'ACC-OFF', 
                'pos_name' => 'Accountant', 
                'description' => 'Accountant', 
                'faculty_id' => null, 
                'department_id' => $departmentIds['FIN'], 
                'hierarchy_level' => 5, 
                'position_type' => 'administrative',
                'position_category' => 'specialized_compliance',
                'creation_type' => 'manual',
                'slug' => Str::slug('Accountant'),
            ],
        ];

        $positionIds = [];
        foreach ($positions as $pos) {
            $existing = DB::table('positions')->where('pos_code', $pos['pos_code'])->first();
            if ($existing) {
                $id = $existing->id;
                DB::table('positions')->where('id', $id)->update(array_merge($pos, [
                    'updated_at' => now(),
                ]));
            } else {
                $id = DB::table('positions')->insertGetId(array_merge($pos, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            $positionIds[$pos['pos_code']] = $id;
            $this->command->line("  ✓ " . ($existing ? 'Updated' : 'Created') . " position: {$pos['pos_name']}");
        }

        // 4. Seed Employees (10 with real-world names)
        $this->command->info('Seeding Employees...');
        $employees = [
            [
                'id' => 'EMP0001',
                'surname' => 'Santos',
                'first_name' => 'Maria',
                'middle_name' => 'Cruz',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Teaching',
                'department_id' => $departmentIds['CS'],
                'position_id' => $positionIds['PROF'],
                'birth_date' => '1980-05-15',
                'birth_place' => 'Manila',
                'sex' => 'Female',
                'civil_status' => 'Married',
                'height_m' => 1.65,
                'weight_kg' => 58.5,
                'blood_type' => 'A+',
                'gsis_id_no' => 'GSIS123456',
                'pagibig_id_no' => 'PAGIBIG123456',
                'philhealth_no' => 'PHIL123456',
                'sss_no' => 'SSS123456',
                'tin_no' => 'TIN123456',
                'agency_employee_no' => 'AGENCY001',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '123',
                'res_street' => 'Rizal Street',
                'res_subdivision' => 'Greenhills',
                'res_barangay' => 'Barangay 1',
                'res_city' => 'Manila',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1000',
                'perm_house_no' => '123',
                'perm_street' => 'Rizal Street',
                'perm_subdivision' => 'Greenhills',
                'perm_barangay' => 'Barangay 1',
                'perm_city' => 'Manila',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1000',
                'telephone_no' => '02-1234567',
                'mobile_no' => '09171234567',
                'email_address' => 'maria.santos@university.edu',
                'government_issued_id' => 'Passport',
                'id_number' => 'P123456',
                'id_date_issued' => '2015-01-15',
                'id_place_of_issue' => 'Manila',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0002',
                'surname' => 'Reyes',
                'first_name' => 'Juan',
                'middle_name' => 'Dela Cruz',
                'name_extension' => 'Jr.',
                'status' => 'active',
                'employee_type' => 'Teaching',
                'department_id' => $departmentIds['IT'],
                'position_id' => $positionIds['ASSP'],
                'birth_date' => '1985-08-20',
                'birth_place' => 'Quezon City',
                'sex' => 'Male',
                'civil_status' => 'Married',
                'height_m' => 1.75,
                'weight_kg' => 72.0,
                'blood_type' => 'O+',
                'gsis_id_no' => 'GSIS234567',
                'pagibig_id_no' => 'PAGIBIG234567',
                'philhealth_no' => 'PHIL234567',
                'sss_no' => 'SSS234567',
                'tin_no' => 'TIN234567',
                'agency_employee_no' => 'AGENCY002',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '456',
                'res_street' => 'EDSA',
                'res_subdivision' => 'Cubao',
                'res_barangay' => 'Barangay 2',
                'res_city' => 'Quezon City',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1100',
                'perm_house_no' => '456',
                'perm_street' => 'EDSA',
                'perm_subdivision' => 'Cubao',
                'perm_barangay' => 'Barangay 2',
                'perm_city' => 'Quezon City',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1100',
                'telephone_no' => '02-2345678',
                'mobile_no' => '09182345678',
                'email_address' => 'juan.reyes@university.edu',
                'government_issued_id' => 'Driver License',
                'id_number' => 'DL234567',
                'id_date_issued' => '2016-03-20',
                'id_place_of_issue' => 'Quezon City',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0003',
                'surname' => 'Garcia',
                'first_name' => 'Ana',
                'middle_name' => 'Lopez',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Teaching',
                'department_id' => $departmentIds['CE'],
                'position_id' => $positionIds['ASST'],
                'birth_date' => '1990-03-10',
                'birth_place' => 'Makati',
                'sex' => 'Female',
                'civil_status' => 'Single',
                'height_m' => 1.60,
                'weight_kg' => 55.0,
                'blood_type' => 'B+',
                'gsis_id_no' => 'GSIS345678',
                'pagibig_id_no' => 'PAGIBIG345678',
                'philhealth_no' => 'PHIL345678',
                'sss_no' => 'SSS345678',
                'tin_no' => 'TIN345678',
                'agency_employee_no' => 'AGENCY003',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '789',
                'res_street' => 'Ayala Avenue',
                'res_subdivision' => 'Bel-Air',
                'res_barangay' => 'Barangay 3',
                'res_city' => 'Makati',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1200',
                'perm_house_no' => '789',
                'perm_street' => 'Ayala Avenue',
                'perm_subdivision' => 'Bel-Air',
                'perm_barangay' => 'Barangay 3',
                'perm_city' => 'Makati',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1200',
                'telephone_no' => '02-3456789',
                'mobile_no' => '09193456789',
                'email_address' => 'ana.garcia@university.edu',
                'government_issued_id' => 'UMID',
                'id_number' => 'UMID345678',
                'id_date_issued' => '2017-05-10',
                'id_place_of_issue' => 'Makati',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0004',
                'surname' => 'Torres',
                'first_name' => 'Carlos',
                'middle_name' => 'Villanueva',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Teaching',
                'department_id' => $departmentIds['EE'],
                'position_id' => $positionIds['INST'],
                'birth_date' => '1992-11-25',
                'birth_place' => 'Pasig',
                'sex' => 'Male',
                'civil_status' => 'Single',
                'height_m' => 1.70,
                'weight_kg' => 68.5,
                'blood_type' => 'AB+',
                'gsis_id_no' => 'GSIS456789',
                'pagibig_id_no' => 'PAGIBIG456789',
                'philhealth_no' => 'PHIL456789',
                'sss_no' => 'SSS456789',
                'tin_no' => 'TIN456789',
                'agency_employee_no' => 'AGENCY004',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '321',
                'res_street' => 'Ortigas Avenue',
                'res_subdivision' => 'San Antonio',
                'res_barangay' => 'Barangay 4',
                'res_city' => 'Pasig',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1600',
                'perm_house_no' => '321',
                'perm_street' => 'Ortigas Avenue',
                'perm_subdivision' => 'San Antonio',
                'perm_barangay' => 'Barangay 4',
                'perm_city' => 'Pasig',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1600',
                'telephone_no' => '02-4567890',
                'mobile_no' => '09204567890',
                'email_address' => 'carlos.torres@university.edu',
                'government_issued_id' => 'Driver License',
                'id_number' => 'DL456789',
                'id_date_issued' => '2018-07-25',
                'id_place_of_issue' => 'Pasig',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0005',
                'surname' => 'Fernandez',
                'first_name' => 'Patricia',
                'middle_name' => 'Ramos',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Teaching',
                'department_id' => $departmentIds['ACC'],
                'position_id' => $positionIds['ASST'],
                'birth_date' => '1988-07-12',
                'birth_place' => 'Mandaluyong',
                'sex' => 'Female',
                'civil_status' => 'Married',
                'height_m' => 1.62,
                'weight_kg' => 56.0,
                'blood_type' => 'A-',
                'gsis_id_no' => 'GSIS567890',
                'pagibig_id_no' => 'PAGIBIG567890',
                'philhealth_no' => 'PHIL567890',
                'sss_no' => 'SSS567890',
                'tin_no' => 'TIN567890',
                'agency_employee_no' => 'AGENCY005',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '654',
                'res_street' => 'Shaw Boulevard',
                'res_subdivision' => 'Wack-Wack',
                'res_barangay' => 'Barangay 5',
                'res_city' => 'Mandaluyong',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1550',
                'perm_house_no' => '654',
                'perm_street' => 'Shaw Boulevard',
                'perm_subdivision' => 'Wack-Wack',
                'perm_barangay' => 'Barangay 5',
                'perm_city' => 'Mandaluyong',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1550',
                'telephone_no' => '02-5678901',
                'mobile_no' => '09215678901',
                'email_address' => 'patricia.fernandez@university.edu',
                'government_issued_id' => 'Passport',
                'id_number' => 'P567890',
                'id_date_issued' => '2019-02-12',
                'id_place_of_issue' => 'Mandaluyong',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0006',
                'surname' => 'Lopez',
                'first_name' => 'Roberto',
                'middle_name' => 'Mendoza',
                'name_extension' => 'Sr.',
                'status' => 'active',
                'employee_type' => 'Non-Teaching',
                'department_id' => $departmentIds['HR'],
                'position_id' => $positionIds['HR-MGR'],
                'birth_date' => '1975-12-05',
                'birth_place' => 'Caloocan',
                'sex' => 'Male',
                'civil_status' => 'Married',
                'height_m' => 1.78,
                'weight_kg' => 75.0,
                'blood_type' => 'O-',
                'gsis_id_no' => 'GSIS678901',
                'pagibig_id_no' => 'PAGIBIG678901',
                'philhealth_no' => 'PHIL678901',
                'sss_no' => 'SSS678901',
                'tin_no' => 'TIN678901',
                'agency_employee_no' => 'AGENCY006',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '987',
                'res_street' => 'Rizal Avenue',
                'res_subdivision' => 'Grace Park',
                'res_barangay' => 'Barangay 6',
                'res_city' => 'Caloocan',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1400',
                'perm_house_no' => '987',
                'perm_street' => 'Rizal Avenue',
                'perm_subdivision' => 'Grace Park',
                'perm_barangay' => 'Barangay 6',
                'perm_city' => 'Caloocan',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1400',
                'telephone_no' => '02-6789012',
                'mobile_no' => '09226789012',
                'email_address' => 'roberto.lopez@university.edu',
                'government_issued_id' => 'UMID',
                'id_number' => 'UMID678901',
                'id_date_issued' => '2014-09-05',
                'id_place_of_issue' => 'Caloocan',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0007',
                'surname' => 'Villanueva',
                'first_name' => 'Carmen',
                'middle_name' => 'Bautista',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Non-Teaching',
                'department_id' => $departmentIds['HR'],
                'position_id' => $positionIds['HR-OFF'],
                'birth_date' => '1987-04-18',
                'birth_place' => 'Marikina',
                'sex' => 'Female',
                'civil_status' => 'Married',
                'height_m' => 1.58,
                'weight_kg' => 54.0,
                'blood_type' => 'B-',
                'gsis_id_no' => 'GSIS789012',
                'pagibig_id_no' => 'PAGIBIG789012',
                'philhealth_no' => 'PHIL789012',
                'sss_no' => 'SSS789012',
                'tin_no' => 'TIN789012',
                'agency_employee_no' => 'AGENCY007',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '147',
                'res_street' => 'Marcos Highway',
                'res_subdivision' => 'Concepcion',
                'res_barangay' => 'Barangay 7',
                'res_city' => 'Marikina',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1800',
                'perm_house_no' => '147',
                'perm_street' => 'Marcos Highway',
                'perm_subdivision' => 'Concepcion',
                'perm_barangay' => 'Barangay 7',
                'perm_city' => 'Marikina',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1800',
                'telephone_no' => '02-7890123',
                'mobile_no' => '09237890123',
                'email_address' => 'carmen.villanueva@university.edu',
                'government_issued_id' => 'Passport',
                'id_number' => 'P789012',
                'id_date_issued' => '2020-06-18',
                'id_place_of_issue' => 'Marikina',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0008',
                'surname' => 'Cruz',
                'first_name' => 'Miguel',
                'middle_name' => 'Sanchez',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Non-Teaching',
                'department_id' => $departmentIds['REG'],
                'position_id' => $positionIds['REG-MGR'],
                'birth_date' => '1978-09-30',
                'birth_place' => 'Taguig',
                'sex' => 'Male',
                'civil_status' => 'Married',
                'height_m' => 1.72,
                'weight_kg' => 70.0,
                'blood_type' => 'A+',
                'gsis_id_no' => 'GSIS890123',
                'pagibig_id_no' => 'PAGIBIG890123',
                'philhealth_no' => 'PHIL890123',
                'sss_no' => 'SSS890123',
                'tin_no' => 'TIN890123',
                'agency_employee_no' => 'AGENCY008',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '258',
                'res_street' => 'BGC',
                'res_subdivision' => 'Fort Bonifacio',
                'res_barangay' => 'Barangay 8',
                'res_city' => 'Taguig',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1630',
                'perm_house_no' => '258',
                'perm_street' => 'BGC',
                'perm_subdivision' => 'Fort Bonifacio',
                'perm_barangay' => 'Barangay 8',
                'perm_city' => 'Taguig',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1630',
                'telephone_no' => '02-8901234',
                'mobile_no' => '09248901234',
                'email_address' => 'miguel.cruz@university.edu',
                'government_issued_id' => 'Driver License',
                'id_number' => 'DL890123',
                'id_date_issued' => '2015-11-30',
                'id_place_of_issue' => 'Taguig',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0009',
                'surname' => 'Ramos',
                'first_name' => 'Elena',
                'middle_name' => 'Gonzales',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Non-Teaching',
                'department_id' => $departmentIds['REG'],
                'position_id' => $positionIds['REG-OFF'],
                'birth_date' => '1991-01-22',
                'birth_place' => 'Las Piñas',
                'sex' => 'Female',
                'civil_status' => 'Single',
                'height_m' => 1.63,
                'weight_kg' => 57.0,
                'blood_type' => 'O+',
                'gsis_id_no' => 'GSIS901234',
                'pagibig_id_no' => 'PAGIBIG901234',
                'philhealth_no' => 'PHIL901234',
                'sss_no' => 'SSS901234',
                'tin_no' => 'TIN901234',
                'agency_employee_no' => 'AGENCY009',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '369',
                'res_street' => 'Alabang-Zapote Road',
                'res_subdivision' => 'Alabang',
                'res_barangay' => 'Barangay 9',
                'res_city' => 'Las Piñas',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1740',
                'perm_house_no' => '369',
                'perm_street' => 'Alabang-Zapote Road',
                'perm_subdivision' => 'Alabang',
                'perm_barangay' => 'Barangay 9',
                'perm_city' => 'Las Piñas',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1740',
                'telephone_no' => '02-9012345',
                'mobile_no' => '09259012345',
                'email_address' => 'elena.ramos@university.edu',
                'government_issued_id' => 'UMID',
                'id_number' => 'UMID901234',
                'id_date_issued' => '2021-03-22',
                'id_place_of_issue' => 'Las Piñas',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
            [
                'id' => 'EMP0010',
                'surname' => 'Mendoza',
                'first_name' => 'Ricardo',
                'middle_name' => 'Castillo',
                'name_extension' => null,
                'status' => 'active',
                'employee_type' => 'Non-Teaching',
                'department_id' => $departmentIds['FIN'],
                'position_id' => $positionIds['FIN-MGR'],
                'birth_date' => '1982-06-08',
                'birth_place' => 'Parañaque',
                'sex' => 'Male',
                'civil_status' => 'Married',
                'height_m' => 1.76,
                'weight_kg' => 73.5,
                'blood_type' => 'AB-',
                'gsis_id_no' => 'GSIS012345',
                'pagibig_id_no' => 'PAGIBIG012345',
                'philhealth_no' => 'PHIL012345',
                'sss_no' => 'SSS012345',
                'tin_no' => 'TIN012345',
                'agency_employee_no' => 'AGENCY010',
                'citizenship' => 'Filipino',
                'dual_citizenship' => false,
                'citizenship_type' => 'By birth',
                'dual_citizenship_country' => null,
                'res_house_no' => '741',
                'res_street' => 'Sucat Road',
                'res_subdivision' => 'BF Homes',
                'res_barangay' => 'Barangay 10',
                'res_city' => 'Parañaque',
                'res_province' => 'Metro Manila',
                'res_zip_code' => '1700',
                'perm_house_no' => '741',
                'perm_street' => 'Sucat Road',
                'perm_subdivision' => 'BF Homes',
                'perm_barangay' => 'Barangay 10',
                'perm_city' => 'Parañaque',
                'perm_province' => 'Metro Manila',
                'perm_zip_code' => '1700',
                'telephone_no' => '02-0123456',
                'mobile_no' => '09260123456',
                'email_address' => 'ricardo.mendoza@university.edu',
                'government_issued_id' => 'Driver License',
                'id_number' => 'DL012345',
                'id_date_issued' => '2016-08-08',
                'id_place_of_issue' => 'Parañaque',
                'indigenous_group' => null,
                'pwd_id_no' => null,
                'solo_parent_id_no' => null,
            ],
        ];

        foreach ($employees as $emp) {
            $existing = DB::table('employees')->where('id', $emp['id'])->first();
            if ($existing) {
                DB::table('employees')->where('id', $emp['id'])->update(array_merge($emp, [
                    'updated_at' => now(),
                ]));
            } else {
                DB::table('employees')->insert(array_merge($emp, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            $this->command->line("  ✓ " . ($existing ? 'Updated' : 'Created') . " employee: {$emp['first_name']} {$emp['surname']} ({$emp['id']})");
        }

        // 4.1. Seed Employee Related Data
        $this->command->info('Seeding Employee Related Data...');
        foreach ($employees as $emp) {
            $employeeId = $emp['id'];
            
            // Family Backgrounds
            $familyBackgrounds = [
                [
                    'employee_id' => $employeeId,
                    'relation' => 'Father',
                    'surname' => $emp['surname'],
                    'first_name' => 'Father' . substr($emp['first_name'], 0, 3),
                    'middle_name' => $emp['middle_name'],
                    'name_extension' => null,
                    'occupation' => ['Engineer', 'Doctor', 'Teacher', 'Businessman', 'Retired'][rand(0, 4)],
                    'employer' => 'Company ' . rand(1, 50),
                    'business_address' => $emp['res_city'],
                    'telephone_no' => $emp['telephone_no'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'employee_id' => $employeeId,
                    'relation' => 'Mother',
                    'surname' => $emp['surname'],
                    'first_name' => 'Mother' . substr($emp['first_name'], 0, 3),
                    'middle_name' => $emp['middle_name'],
                    'name_extension' => null,
                    'occupation' => ['Nurse', 'Teacher', 'Housewife', 'Accountant', 'Businesswoman'][rand(0, 4)],
                    'employer' => 'Company ' . rand(1, 50),
                    'business_address' => $emp['res_city'],
                    'telephone_no' => $emp['telephone_no'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            
            // Add spouse if married
            if ($emp['civil_status'] == 'Married') {
                $familyBackgrounds[] = [
                    'employee_id' => $employeeId,
                    'relation' => 'Spouse',
                    'surname' => $emp['surname'],
                    'first_name' => 'Spouse' . substr($emp['first_name'], 0, 3),
                    'middle_name' => $emp['middle_name'],
                    'name_extension' => null,
                    'occupation' => ['Engineer', 'Doctor', 'Teacher', 'Nurse', 'Accountant'][rand(0, 4)],
                    'employer' => 'Company ' . rand(1, 50),
                    'business_address' => $emp['res_city'],
                    'telephone_no' => $emp['telephone_no'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            DB::table('employee_family_backgrounds')->where('employee_id', $employeeId)->delete();
            DB::table('employee_family_backgrounds')->insert($familyBackgrounds);
            
            // Children (if married)
            if ($emp['civil_status'] == 'Married') {
                $numChildren = rand(1, 3);
                $children = [];
                for ($i = 1; $i <= $numChildren; $i++) {
                    $children[] = [
                        'employee_id' => $employeeId,
                        'full_name' => 'Child ' . $i . ' ' . $emp['surname'],
                        'birth_date' => date('Y-m-d', strtotime('-' . rand(1, 20) . ' years')),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                DB::table('employee_childrens')->where('employee_id', $employeeId)->delete();
                if (!empty($children)) {
                    DB::table('employee_childrens')->insert($children);
                }
            }
            
            // Educational Backgrounds
            $educationalBackgrounds = [];
            $levels = ['Elementary', 'Secondary', 'Vocational', 'College', 'Graduate Studies'];
            $numRecords = rand(3, 5);
            
            for ($i = 0; $i < $numRecords; $i++) {
                $level = $levels[$i] ?? $levels[rand(0, count($levels) - 1)];
                $yearGraduated = date('Y') - rand(5, 30);
                
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
                    'school_name' => $level . ' School ' . rand(1, 100),
                    'degree_course' => ($level === 'College' || $level === 'Graduate Studies') ? ['BS Computer Science', 'BS Engineering', 'BA English', 'BS Biology', 'MA Education', 'PhD Mathematics'][rand(0, 5)] : null,
                    'period_from' => $periodFrom . '-06-01',
                    'period_to' => $yearGraduated . '-03-31',
                    'highest_level_units' => ($level === 'College' || $level === 'Graduate Studies') ? rand(100, 200) . ' units' : null,
                    'year_graduated' => $yearGraduated,
                    'honors_received' => rand(0, 1) ? ['Cum Laude', 'Magna Cum Laude', 'Summa Cum Laude', 'With Honors'][rand(0, 3)] : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('employee_educational_backgrounds')->where('employee_id', $employeeId)->delete();
            DB::table('employee_educational_backgrounds')->insert($educationalBackgrounds);
            
            // Civil Service Eligibilities
            $numEligibilities = rand(0, 2);
            $civilServiceEligibilities = [];
            for ($i = 0; $i < $numEligibilities; $i++) {
                $eligibility = ['Professional', 'Subprofessional', 'RA 1080', 'CSEE', 'CES'][rand(0, 4)];
                $civilServiceEligibilities[] = [
                    'employee_id' => $employeeId,
                    'eligibility' => $eligibility,
                    'rating' => rand(80, 99) . '.' . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT),
                    'exam_date' => date('Y-m-d', strtotime('-' . rand(1, 10) . ' years')),
                    'exam_place' => $emp['res_city'],
                    'license_no' => 'LIC' . rand(100000, 999999),
                    'license_validity' => date('Y-m-d', strtotime('+' . rand(1, 10) . ' years')),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('employee_civil_service_eligibilities')->where('employee_id', $employeeId)->delete();
            if (!empty($civilServiceEligibilities)) {
                DB::table('employee_civil_service_eligibilities')->insert($civilServiceEligibilities);
            }
            
            // Work Experiences
            $numWorkExp = rand(2, 5);
            $workExperiences = [];
            for ($i = 0; $i < $numWorkExp; $i++) {
                $dateFrom = date('Y-m-d', strtotime('-' . rand(5, 15) . ' years'));
                $dateTo = rand(0, 1) ? date('Y-m-d', strtotime($dateFrom . ' +' . rand(1, 5) . ' years')) : null;
                
                $workExperiences[] = [
                    'employee_id' => $employeeId,
                    'position_title' => ['Manager', 'Supervisor', 'Specialist', 'Analyst', 'Engineer', 'Teacher'][rand(0, 5)],
                    'company_name' => 'Company ' . rand(1, 100),
                    'company_address' => $emp['res_city'],
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'monthly_salary' => rand(20000, 100000),
                    'salary_grade_step' => rand(1, 33) . '-' . rand(1, 8),
                    'status_of_appointment' => ['Permanent', 'Contractual', 'Probationary', 'Part-time'][rand(0, 3)],
                    'is_gov_service' => rand(0, 1),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('employee_work_experiences')->where('employee_id', $employeeId)->delete();
            DB::table('employee_work_experiences')->insert($workExperiences);
            
            // Voluntary Works
            $numVoluntary = rand(0, 3);
            $voluntaryWorks = [];
            for ($i = 0; $i < $numVoluntary; $i++) {
                $dateFrom = date('Y-m-d', strtotime('-' . rand(2, 10) . ' years'));
                $dateTo = rand(0, 1) ? date('Y-m-d', strtotime($dateFrom . ' +' . rand(1, 3) . ' years')) : null;
                
                $voluntaryWorks[] = [
                    'employee_id' => $employeeId,
                    'organization_name' => 'Organization ' . rand(1, 50),
                    'organization_address' => $emp['res_city'],
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'hours_rendered' => rand(50, 500),
                    'position_or_nature' => ['Volunteer', 'Coordinator', 'Trainer', 'Facilitator'][rand(0, 3)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('employee_voluntary_works')->where('employee_id', $employeeId)->delete();
            if (!empty($voluntaryWorks)) {
                DB::table('employee_voluntary_works')->insert($voluntaryWorks);
            }
            
            // Learning Developments
            $numLearning = rand(3, 8);
            $learningDevelopments = [];
            for ($i = 0; $i < $numLearning; $i++) {
                $dateFrom = date('Y-m-d', strtotime('-' . rand(1, 5) . ' years'));
                $dateTo = date('Y-m-d', strtotime($dateFrom . ' +' . rand(1, 14) . ' days'));
                
                $learningDevelopments[] = [
                    'employee_id' => $employeeId,
                    'title' => 'Training ' . rand(1, 100),
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'hours' => rand(8, 40),
                    'type_of_ld' => ['Managerial', 'Supervisory', 'Technical', 'Foundation', 'Others'][rand(0, 4)],
                    'conducted_by' => 'Organization ' . rand(1, 50),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('employee_learning_developments')->where('employee_id', $employeeId)->delete();
            DB::table('employee_learning_developments')->insert($learningDevelopments);
            
            // Other Information
            DB::table('employee_other_information')->where('employee_id', $employeeId)->delete();
            DB::table('employee_other_information')->insert([
                'employee_id' => $employeeId,
                'skill_or_hobby' => ['Programming', 'Painting', 'Singing', 'Dancing', 'Sports', 'Writing'][rand(0, 5)],
                'non_academic_distinctions' => rand(0, 1) ? 'Award ' . rand(1, 10) : null,
                'memberships' => rand(0, 1) ? 'Member of Organization ' . rand(1, 10) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Questionnaires
            $questionnaires = [];
            for ($i = 34; $i <= 41; $i++) {
                $answer = rand(0, 1);
                $questionnaires[] = [
                    'employee_id' => $employeeId,
                    'question_number' => $i,
                    'answer' => $answer,
                    'details' => $answer ? 'Details for question ' . $i : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('questionnaires')->where('employee_id', $employeeId)->delete();
            DB::table('questionnaires')->insert($questionnaires);
            
            // References
            $references = [];
            for ($i = 1; $i <= 3; $i++) {
                $references[] = [
                    'employee_id' => $employeeId,
                    'first_name' => 'ReferenceFirstName' . $i,
                    'middle_initial' => chr(rand(65, 90)),
                    'surname' => 'ReferenceLastName' . $i,
                    'address' => $emp['res_city'],
                    'telephone_no' => $emp['telephone_no'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('references')->where('employee_id', $employeeId)->delete();
            DB::table('references')->insert($references);
        }
        $this->command->info('  ✓ Seeded all employee related data');

        // 5. Seed Trainings (5 trainings without requires_approval)
        $this->command->info('Seeding Trainings...');
        $trainings = [
            [
                'training_title' => 'Effective Communication Skills Workshop',
                'training_category_id' => null,
                'date_from' => now()->addDays(30)->format('Y-m-d'),
                'date_to' => now()->addDays(31)->format('Y-m-d'),
                'hours' => 8.00,
                'facilitator' => 'Dr. Maria Santos',
                'venue' => 'Main Conference Hall',
                'capacity' => 50,
                'remarks' => 'Open to all employees. Focus on improving workplace communication.',
                'requires_approval' => false,
                'request_type_id' => null,
                'reference_number' => 'TRN-' . date('Y') . '-001',
            ],
            [
                'training_title' => 'Leadership and Management Development Program',
                'training_category_id' => null,
                'date_from' => now()->addDays(45)->format('Y-m-d'),
                'date_to' => now()->addDays(47)->format('Y-m-d'),
                'hours' => 16.00,
                'facilitator' => 'Prof. Juan Reyes',
                'venue' => 'Executive Training Center',
                'capacity' => 30,
                'remarks' => 'Designed for supervisors and managers. Covers strategic planning and team management.',
                'requires_approval' => false,
                'request_type_id' => null,
                'reference_number' => 'TRN-' . date('Y') . '-002',
            ],
            [
                'training_title' => 'Digital Literacy and Office Productivity Tools',
                'training_category_id' => null,
                'date_from' => now()->addDays(60)->format('Y-m-d'),
                'date_to' => now()->addDays(61)->format('Y-m-d'),
                'hours' => 6.00,
                'facilitator' => 'Ms. Ana Garcia',
                'venue' => 'Computer Laboratory 1',
                'capacity' => 40,
                'remarks' => 'Basic to intermediate level training on Microsoft Office and Google Workspace.',
                'requires_approval' => false,
                'request_type_id' => null,
                'reference_number' => 'TRN-' . date('Y') . '-003',
            ],
            [
                'training_title' => 'Workplace Safety and Emergency Preparedness',
                'training_category_id' => null,
                'date_from' => now()->addDays(75)->format('Y-m-d'),
                'date_to' => now()->addDays(75)->format('Y-m-d'),
                'hours' => 4.00,
                'facilitator' => 'Safety Officer Team',
                'venue' => 'Auditorium',
                'capacity' => 100,
                'remarks' => 'Mandatory training for all employees. Covers fire safety, earthquake preparedness, and first aid basics.',
                'requires_approval' => false,
                'request_type_id' => null,
                'reference_number' => 'TRN-' . date('Y') . '-004',
            ],
            [
                'training_title' => 'Customer Service Excellence Training',
                'training_category_id' => null,
                'date_from' => now()->addDays(90)->format('Y-m-d'),
                'date_to' => now()->addDays(91)->format('Y-m-d'),
                'hours' => 10.00,
                'facilitator' => 'External Training Consultant',
                'venue' => 'Training Room 2',
                'capacity' => 35,
                'remarks' => 'For front-line staff and customer-facing employees. Focus on handling difficult situations and building rapport.',
                'requires_approval' => false,
                'request_type_id' => null,
                'reference_number' => 'TRN-' . date('Y') . '-005',
            ],
        ];

        foreach ($trainings as $training) {
            $existing = DB::table('trainings')->where('reference_number', $training['reference_number'])->first();
            if ($existing) {
                DB::table('trainings')->where('reference_number', $training['reference_number'])->update(array_merge($training, [
                    'updated_at' => now(),
                ]));
            } else {
                DB::table('trainings')->insert(array_merge($training, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
            $this->command->line("  ✓ " . ($existing ? 'Updated' : 'Created') . " training: {$training['training_title']}");
        }

        // Re-enable foreign key checks
        if (in_array($driver, ['mysql', 'mariadb'])) {
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->command->info('');
        $this->command->info('✅ Successfully seeded all data!');
        $this->command->info('  - ' . count($faculties) . ' Faculties');
        $this->command->info('  - ' . count($departments) . ' Departments/Offices');
        $this->command->info('  - ' . count($positions) . ' Positions');
        $this->command->info('  - ' . count($employees) . ' Employees');
        $this->command->info('  - ' . count($trainings) . ' Trainings');
    }
}