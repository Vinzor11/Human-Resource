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
        Schema::table('departments', function (Blueprint $table) {
            // Add type column if it doesn't exist
            if (!Schema::hasColumn('departments', 'type')) {
                $table->string('type', 50)->default('academic')->after('description');
            }
            
            // Add faculty_id column if it doesn't exist
            if (!Schema::hasColumn('departments', 'faculty_id')) {
                $table->unsignedBigInteger('faculty_id')->nullable()->after('type');
                $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('set null');
            }
            
            // Add head_position_id column if it doesn't exist (used in some queries)
            if (!Schema::hasColumn('departments', 'head_position_id')) {
                $table->unsignedBigInteger('head_position_id')->nullable()->after('faculty_id');
                $table->foreign('head_position_id')->references('id')->on('positions')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'head_position_id')) {
                $table->dropForeign(['head_position_id']);
                $table->dropColumn('head_position_id');
            }
            
            if (Schema::hasColumn('departments', 'faculty_id')) {
                $table->dropForeign(['faculty_id']);
                $table->dropColumn('faculty_id');
            }
            
            if (Schema::hasColumn('departments', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
