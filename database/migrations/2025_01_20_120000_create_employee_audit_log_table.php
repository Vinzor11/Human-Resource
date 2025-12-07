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
        Schema::create('employee_audit_log', function (Blueprint $table) {
            $table->unsignedInteger('record_id')->autoIncrement()->primary();
            $table->string('employee_id', 15);
            $table->enum('action_type', ['CREATE', 'UPDATE', 'DELETE']);
            $table->string('field_changed', 100)->nullable();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->timestamp('action_date')->useCurrent();
            $table->string('performed_by', 150);

            // Indexes for performance
            $table->index(['employee_id', 'action_date'], 'idx_employee_date');
            $table->index('action_type', 'idx_action_type');
        });

        // Add foreign key constraint only if employees table exists
        // This will be handled by a later migration (2025_11_20_115230) after employees table is created
        if (Schema::hasTable('employees')) {
            Schema::table('employee_audit_log', function (Blueprint $table) {
                $table->foreign('employee_id')
                      ->references('id')
                      ->on('employees')
                      ->onDelete('cascade')
                      ->onUpdate('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_audit_log');
    }
};

