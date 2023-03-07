<?php


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalitasTypeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecificationController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // USERS
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('data', [UserController::class, 'data'])->name('data');
        Route::put('activate/{id}', [UserController::class, 'activate'])->name('activate');
        Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('deactivate');
        Route::put('roles-update/{id}', [UserController::class, 'roles_user_update'])->name('roles_user_update');
    });

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // DASHBOARD
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
    });

    // SUPPLIERS
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('data', [SupplierController::class, 'data'])->name('data');
    });
    Route::resource('suppliers', SupplierController::class);

    // SPECIFICATIONS
    Route::prefix('specifications')->name('specifications.')->group(function () {
        Route::get('data', [SpecificationController::class, 'data'])->name('data');
    });
    Route::resource('specifications', SpecificationController::class);

    // LEGALITAS TYPES
    Route::prefix('legalitas_types')->name('legalitas_types.')->group(function () {
        Route::get('data', [LegalitasTypeController::class, 'data'])->name('data');
    });
    Route::resource('legalitas_types', LegalitasTypeController::class);
});
