<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MigrationController;
use App\Http\Controllers\PatternController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RemovalReasonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportTransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TyreSizeController;
use App\Http\Controllers\TyreBrandController;
use App\Http\Controllers\TyreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ToolController;
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
        Route::get('change-password/{id}', [UserController::class, 'change_password'])->name('change_password');
        Route::put('password-update/{id}', [UserController::class, 'password_update'])->name('password_update');
    });

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // ANNOUNCEMENTS
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::put('toggle-status/{announcement}', [AnnouncementController::class, 'toggleStatus'])->name('toggle_status');
    });
    Route::resource('announcements', AnnouncementController::class);

    // DASHBOARD
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/test', [DashboardController::class, 'test'])->name('test');
    });

    // PATTERNS
    Route::get('patterns/data', [PatternController::class, 'data'])->name('patterns.data');
    Route::resource('patterns', PatternController::class);

    // REMOVAL REASONS
    Route::get('removal-reasons/data', [RemovalReasonController::class, 'data'])->name('removal-reasons.data');
    Route::resource('removal-reasons', RemovalReasonController::class);

    // TYRE SIZE
    Route::get('tyre-sizes/data', [TyreSizeController::class, 'data'])->name('tyre-sizes.data');
    Route::resource('tyre-sizes', TyreSizeController::class);

    // TYRE BRAND
    Route::get('tyre-brands/data', [TyreBrandController::class, 'data'])->name('tyre-brands.data');
    Route::resource('tyre-brands', TyreBrandController::class);

    // SUPPLIERS / VENDORS
    Route::get('suppliers/data', [SupplierController::class, 'data'])->name('suppliers.data');
    Route::resource('suppliers', SupplierController::class);

    // EQUIPMENTS
    Route::prefix('equipments')->name('equipments.')->group(function () {
        Route::get('/data', [EquipmentController::class, 'data'])->name('data');
        Route::get('/', [EquipmentController::class, 'index'])->name('index');
    });

    // TYRES
    Route::prefix('tyres')->name('tyres.')->group(function () {
        Route::get('/search', [TyreController::class, 'search'])->name('search');
        Route::get('/search-data', [TyreController::class, 'searchData'])->name('search.data');
        Route::get('/data', [TyreController::class, 'data'])->name('data');
        Route::get('/get-last-hm', [ToolController::class, 'getLastHm'])->name('get_last_hm');
        Route::get('/{id}/data', [TyreController::class, 'histories_data'])->name('histories.data');
        Route::delete('/histories/{transaction_id}', [TyreController::class, 'transaction_destroy'])->name('transaction.destroy');
        Route::get('/activate/{id}', [TyreController::class, 'activate'])->name('activate');
        Route::post('/inactive/{id}', [TyreController::class, 'inactive'])->name('inactive');
        Route::put('/reset/{id}', [TyreController::class, 'reset_hm'])->name('reset_hm');
        // test
        Route::get('/{id}/test', [TyreController::class, 'test'])->name('test');
        Route::resource('/', TyreController::class)->parameters(['' => 'tyre']);
        Route::post('/{id}/update-hm', [TransactionController::class, 'updateHm'])->name('tyres.update_hm');
        Route::get('/{brand_id}/avg-cph', [TyreController::class, 'getAvgCph'])->name('avg_cph');
    });

    // TRANSACTIONS
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/data', [TransactionController::class, 'data'])->name('data');
    });
    Route::resource('transactions', TransactionController::class);

    // MIGRATIONS
    Route::prefix('migrations')->name('migrations.')->group(function () {
        // TYRES
        Route::get('/tyres', [MigrationController::class, 'tyres'])->name('tyres');
        Route::get('/tyres/data', [MigrationController::class, 'tyres_data'])->name('tyres.data');
        Route::get('/tyres/migrate', [MigrationController::class, 'tyres_migrate'])->name('tyres.migrate');

        // TRANSACTIONS
        Route::get('/transactions', [MigrationController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/data', [MigrationController::class, 'transactions_data'])->name('transactions.data');
        Route::get('/transactions/migrate', [MigrationController::class, 'transactions_migrate'])->name('transactions.migrate');
    });

    //REPORTS
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');

        // TYRE REKAPS
        Route::prefix('tyre-rekaps')->name('tyre-rekaps.')->group(function () {
            Route::get('/export', [ReportController::class, 'tyre_rekaps_export'])->name('export');
            Route::get('/', [ReportController::class, 'tyre_rekaps'])->name('index');
            Route::get('/data', [ReportController::class, 'tyre_rekaps_data'])->name('data');
            Route::get('/{id}', [ReportController::class, 'tyre_rekaps_show'])->name('show');
            Route::get('/{id}/data', [ReportController::class, 'tyre_rekaps_history_data'])->name('histories.data');
        });

        // TRASACTION REKAPS
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/export', [ReportTransactionController::class, 'export'])->name('export');
            Route::get('/', [ReportTransactionController::class, 'index'])->name('index');
            Route::get('/data', [ReportTransactionController::class, 'data'])->name('data');
        });
    });

    // TEST
    Route::get('/test', [TestController::class, 'index'])->name('index');
});
