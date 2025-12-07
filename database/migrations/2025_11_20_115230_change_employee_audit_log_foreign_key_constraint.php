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
        // Check if foreign key exists before trying to drop it
        $foreignKeyExists = \DB::selectOne("
            SELECT COUNT(*) as count
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'employee_audit_log'
            AND COLUMN_NAME = 'employee_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if ($foreignKeyExists && $foreignKeyExists->count > 0) {
            Schema::table('employee_audit_log', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
            });
        }

        // Recreate the foreign key with NO ACTION instead of CASCADE
        // This preserves audit logs even after employee deletion
        Schema::table('employee_audit_log', function (Blueprint $table) {
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('no action')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_audit_log', function (Blueprint $table) {
            // Drop the NO ACTION constraint
            $table->dropForeign(['employee_id']);
            
            // Restore the original CASCADE constraint
            $table->foreign('employee_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
};
