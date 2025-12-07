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
        // Create faculties table if it doesn't exist
        if (!Schema::hasTable('faculties')) {
            Schema::create('faculties', function (Blueprint $table) {
                $table->id();
                $table->string('code', 10)->unique();
                $table->string('name', 100);
                $table->text('description')->nullable();
                $table->string('type', 50)->default('academic');
                $table->string('status', 20)->default('active');
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('faculties', function (Blueprint $table) {
                if (!Schema::hasColumn('faculties', 'status')) {
                    $table->string('status', 20)->default('active')->after('type');
                }

                if (!Schema::hasColumn('faculties', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }

        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            if (Schema::hasColumn('faculties', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('faculties', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });

        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
