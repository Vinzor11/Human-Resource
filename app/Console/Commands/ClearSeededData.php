<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearSeededData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear-seeded-data {--force : Force the operation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all seeded data for employees, departments, and positions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  This will delete ALL employees, departments, and positions. Are you sure?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('🗑️  Starting to clear seeded data...');

        try {
            // Disable foreign key checks temporarily (MySQL/MariaDB)
            $driver = DB::connection()->getDriverName();
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }

            // Delete all employee-related data
            $this->info('Deleting employee-related data...');
            
            $employeeRelatedTables = [
                'employee_family_backgrounds',
                'employee_childrens',
                'employee_educational_backgrounds',
                'employee_civil_service_eligibilities',
                'employee_work_experiences',
                'employee_voluntary_works',
                'employee_learning_developments',
                'employee_other_information',
                'questionnaires',
                'references',
            ];

            foreach ($employeeRelatedTables as $table) {
                $count = DB::table($table)->count();
                DB::table($table)->truncate();
                $this->line("  ✓ Cleared {$table} ({$count} records)");
            }

            // Delete all employees
            $employeeCount = DB::table('employees')->count();
            DB::table('employees')->truncate();
            $this->info("  ✓ Cleared employees table ({$employeeCount} records)");

            // Delete all departments
            $departmentCount = DB::table('departments')->count();
            DB::table('departments')->truncate();
            $this->info("  ✓ Cleared departments table ({$departmentCount} records)");

            // Delete all positions
            $positionCount = DB::table('positions')->count();
            DB::table('positions')->truncate();
            $this->info("  ✓ Cleared positions table ({$positionCount} records)");

            // Re-enable foreign key checks (MySQL/MariaDB)
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }

            $this->info('');
            $this->info('✅ Successfully cleared all seeded data!');
            return 0;
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of error (MySQL/MariaDB)
            $driver = DB::connection()->getDriverName();
            if (in_array($driver, ['mysql', 'mariadb'])) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
            
            $this->error('❌ Error clearing seeded data: ' . $e->getMessage());
            return 1;
        }
    }
}
