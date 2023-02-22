<?php

use App\Http\Controllers\DangerTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HazardReportController;
use App\Http\Controllers\HazardResponseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReportAttachmentController;
use App\Http\Controllers\RoleController;
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
        Route::get('/test', [DashboardController::class, 'test'])->name('test');
    });

    // HAZARD REPORTS
    Route::prefix('hazard-rpt')->name('hazard-rpt.')->group(function () {
        Route::get('/data', [HazardReportController::class, 'data'])->name('data');
        Route::get('/response_data/{id}', [HazardReportController::class, 'response_data'])->name('response_data');
        Route::get('/closed-data', [HazardReportController::class, 'closed_data'])->name('closed_data');
        Route::get('/close-rpt/{id}', [HazardReportController::class, 'close_report'])->name('close_report');
        Route::post('/store-attachment', [HazardReportController::class, 'store_attachment'])->name('store_attachment');
        Route::post('/store-response', [HazardReportController::class, 'store_response'])->name('store_response');
        Route::get('/closed-index', [HazardReportController::class, 'closed_index'])->name('closed_index');
        Route::get('/closed-show/{id}', [HazardReportController::class, 'show_closed'])->name('show_closed');
    });
    Route::resource('hazard-rpt', HazardReportController::class);

    // REPORT ATTACHMENTS
    Route::prefix('report-attachment')->name('report-attachment.')->group(function () {
        // Route::post('/', [ReportAttachmentController::class, 'store'])->name('store');
        Route::delete('/{id}', [ReportAttachmentController::class, 'destroy'])->name('destroy');
    });

    // DANGER TYPES
    Route::get('danger-types/data', [DangerTypeController::class, 'data'])->name('danger-types.data');
    Route::resource('danger-types', DangerTypeController::class);

    // HAZARD RESPONSES
    Route::prefix('hazard-responses')->name('hazard-responses.')->group(function () {
        Route::get('data', [HazardResponseController::class, 'data'])->name('data');
        Route::put('/{id}', [HazardResponseController::class, 'destroy'])->name('destroy');
    });
});
