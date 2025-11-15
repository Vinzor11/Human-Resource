<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Super Admin role
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin'], [
            'guard_name' => 'web',
        ]);

        // 2. Define all permissions
        $permissions = [
            // Access Module Permissions
            [
                'name' => 'access-roles-module',
                'module' => 'Roles',
                'label' => 'Access Roles Module',
                'description' => 'Can access roles module',
            ],
            [
                'name' => 'access-permissions-module',
                'module' => 'Permissions',
                'label' => 'Access Permissions Module',
                'description' => 'Can access permissions module',
            ],
            [
                'name' => 'access-users-module',
                'module' => 'Users',
                'label' => 'Access Users Module',
                'description' => 'Can access users module',
            ],
            [
                'name' => 'access-employees-module',
                'module' => 'Employees',
                'label' => 'Access Employees Module',
                'description' => 'Can access employee module',
            ],
            [
                'name' => 'access-departments-module',
                'module' => 'Departments',
                'label' => 'Access Departments Module',
                'description' => 'Can access departments module',
            ],
            [
                'name' => 'access-positions-module',
                'module' => 'Positions',
                'label' => 'Access Positions Module',
                'description' => 'Can access positions module',
            ],

            // Roles Permissions
            ['name' => 'create-role', 'module' => 'Roles', 'label' => 'Create Role', 'description' => 'Can create new roles'],
            ['name' => 'edit-role', 'module' => 'Roles', 'label' => 'Edit Role', 'description' => 'Can modify existing roles'],
            ['name' => 'delete-role', 'module' => 'Roles', 'label' => 'Delete Role', 'description' => 'Can remove roles'],
            ['name' => 'view-role', 'module' => 'Roles', 'label' => 'View Role', 'description' => 'Can view role details'],

            // Permissions Permissions
            ['name' => 'create-permission', 'module' => 'Permissions', 'label' => 'Create Permission', 'description' => 'Can create permissions'],
            ['name' => 'edit-permission', 'module' => 'Permissions', 'label' => 'Edit Permission', 'description' => 'Can modify permissions'],
            ['name' => 'delete-permission', 'module' => 'Permissions', 'label' => 'Delete Permission', 'description' => 'Can delete permissions'],
            ['name' => 'view-permission', 'module' => 'Permissions', 'label' => 'View Permission', 'description' => 'Can view permissions'],

            // Users Permissions
            ['name' => 'create-user', 'module' => 'Users', 'label' => 'Create User', 'description' => 'Can create new users'],
            ['name' => 'edit-user', 'module' => 'Users', 'label' => 'Edit User', 'description' => 'Can modify user accounts'],
            ['name' => 'delete-user', 'module' => 'Users', 'label' => 'Delete User', 'description' => 'Can delete users'],
            ['name' => 'view-user', 'module' => 'Users', 'label' => 'View User', 'description' => 'Can view user details'],

            // Employees Permissions
            ['name' => 'create-employee', 'module' => 'Employees', 'label' => 'Create Employee', 'description' => 'Can create new employees'],
            ['name' => 'edit-employee', 'module' => 'Employees', 'label' => 'Edit Employee', 'description' => 'Can edit employee records'],
            ['name' => 'delete-employee', 'module' => 'Employees', 'label' => 'Delete Employee', 'description' => 'Can delete employee records'],
            ['name' => 'view-employee', 'module' => 'Employees', 'label' => 'View Employee', 'description' => 'Can view employee details'],

            // Departments Permissions
            ['name' => 'create-department', 'module' => 'Departments', 'label' => 'Create Department', 'description' => 'Can create new departments'],
            ['name' => 'edit-department', 'module' => 'Departments', 'label' => 'Edit Department', 'description' => 'Can edit department records'],
            ['name' => 'delete-department', 'module' => 'Departments', 'label' => 'Delete Department', 'description' => 'Can delete departments'],
            ['name' => 'view-department', 'module' => 'Departments', 'label' => 'View Department', 'description' => 'Can view department details'],

            // Positions Permissions
            ['name' => 'create-position', 'module' => 'Positions', 'label' => 'Create Position', 'description' => 'Can create new positions'],
            ['name' => 'edit-position', 'module' => 'Positions', 'label' => 'Edit Position', 'description' => 'Can edit position records'],
            ['name' => 'delete-position', 'module' => 'Positions', 'label' => 'Delete Position', 'description' => 'Can delete positions'],
            ['name' => 'view-position', 'module' => 'Positions', 'label' => 'View Position', 'description' => 'Can view position details'],
        ];

        // 3. Create permissions if they don't exist
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(
                ['name' => $perm['name']],
                [
                    'guard_name' => 'web',
                    'module' => $perm['module'],
                    'label' => $perm['label'],
                    'description' => $perm['description'],
                    'is_active' => true,
                ]
            );
        }

        // 4. Assign all permissions to the Super Admin role
        $superAdminRole->syncPermissions(Permission::all());

        // 5. Create the Super Admin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // ⚠️ Change this in production!
            ]
        );

        // 6. Assign the Super Admin role to the user
        $user->assignRole($superAdminRole);

        $this->command->info('✅ Super Admin user, role, and all module permissions (including Departments & Positions) seeded successfully!');
    }
}
