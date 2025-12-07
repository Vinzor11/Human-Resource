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
        // Table already exists from migration 2025_11_27_234051, so we'll add columns
        if (Schema::hasTable('certificate_templates')) {
            Schema::table('certificate_templates', function (Blueprint $table) {
                if (!Schema::hasColumn('certificate_templates', 'name')) {
                    $table->string('name')->after('id');
                }
                if (!Schema::hasColumn('certificate_templates', 'description')) {
                    $table->text('description')->nullable()->after('name');
                }
                if (!Schema::hasColumn('certificate_templates', 'background_image_path')) {
                    $table->string('background_image_path')->nullable()->after('description');
                }
                if (!Schema::hasColumn('certificate_templates', 'width')) {
                    $table->integer('width')->default(1200)->after('background_image_path');
                }
                if (!Schema::hasColumn('certificate_templates', 'height')) {
                    $table->integer('height')->default(800)->after('width');
                }
                if (!Schema::hasColumn('certificate_templates', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('height');
                }
            });
        } else {
            // If table doesn't exist for some reason, create it
            Schema::create('certificate_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('background_image_path')->nullable();
                $table->integer('width')->default(1200);
                $table->integer('height')->default(800);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
