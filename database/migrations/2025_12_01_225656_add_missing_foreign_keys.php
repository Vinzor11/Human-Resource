<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign key for leave_balances.employee_id if it doesn't exist
        if (Schema::hasTable('leave_balances') && Schema::hasTable('employees')) {
            $fkExists = \DB::selectOne("
                SELECT COUNT(*) as count
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'leave_balances'
                AND COLUMN_NAME = 'employee_id'
                AND REFERENCED_TABLE_NAME = 'employees'
            ");
            
            if (!$fkExists || $fkExists->count == 0) {
                Schema::table('leave_balances', function (Blueprint $table) {
                    $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
                });
            }
        }

        // Add foreign key for leave_requests.employee_id if it doesn't exist
        if (Schema::hasTable('leave_requests') && Schema::hasTable('employees')) {
            $fkExists = \DB::selectOne("
                SELECT COUNT(*) as count
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'leave_requests'
                AND COLUMN_NAME = 'employee_id'
                AND REFERENCED_TABLE_NAME = 'employees'
            ");
            
            if (!$fkExists || $fkExists->count == 0) {
                Schema::table('leave_requests', function (Blueprint $table) {
                    $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
                });
            }
        }

        // Add foreign key for leave_requests.request_submission_id if it doesn't exist
        if (Schema::hasTable('leave_requests') && Schema::hasTable('request_submissions')) {
            $fkExists = \DB::selectOne("
                SELECT COUNT(*) as count
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'leave_requests'
                AND COLUMN_NAME = 'request_submission_id'
                AND REFERENCED_TABLE_NAME = 'request_submissions'
            ");
            
            if (!$fkExists || $fkExists->count == 0) {
                Schema::table('leave_requests', function (Blueprint $table) {
                    $table->foreign('request_submission_id')->references('id')->on('request_submissions')->cascadeOnDelete();
                });
            }
        }

        // Add foreign key for leave_accruals.employee_id if it doesn't exist
        if (Schema::hasTable('leave_accruals') && Schema::hasTable('employees')) {
            $fkExists = \DB::selectOne("
                SELECT COUNT(*) as count
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'leave_accruals'
                AND COLUMN_NAME = 'employee_id'
                AND REFERENCED_TABLE_NAME = 'employees'
            ");
            
            if (!$fkExists || $fkExists->count == 0) {
                Schema::table('leave_accruals', function (Blueprint $table) {
                    $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys if they exist
        if (Schema::hasTable('leave_balances')) {
            Schema::table('leave_balances', function (Blueprint $table) {
                try {
                    $table->dropForeign(['employee_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, ignore
                }
            });
        }

        if (Schema::hasTable('leave_requests')) {
            Schema::table('leave_requests', function (Blueprint $table) {
                try {
                    $table->dropForeign(['employee_id']);
                    $table->dropForeign(['request_submission_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, ignore
                }
            });
        }

        if (Schema::hasTable('leave_accruals')) {
            Schema::table('leave_accruals', function (Blueprint $table) {
                try {
                    $table->dropForeign(['employee_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, ignore
                }
            });
        }
    }
};
