<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::resource('categories', CategoryController::class)->middleware('permission:access-categories-module');
    Route::resource('departments', DepartmentController::class)->middleware('permission:access-departments-module');
    Route::resource('positions', PositionController::class)->middleware('permission:access-positions-module');
    Route::resource('employees', EmployeeController::class)->middleware('permission:access-employees-module');
    Route::resource('permissions', PermissionController::class)->middleware('permission:access-permissions-module');
    Route::resource('roles', RoleController::class)->middleware('permission:access-roles-module');
    Route::resource('users', UserController::class)->middleware('permission:access-users-module');
    Route::get('/api/roles', [RoleController::class, 'getAllRoles'])->name('roles.api');

});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
