<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;

use App\Http\Controllers\FeeCategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;

use App\Http\Controllers\FinanceController;
use App\Http\Controllers\BalanceReportController;

use App\Http\Controllers\SettingController;
use App\Http\Controllers\BackupController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PermissionController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view dashboard')
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | SESSION YEAR SWITCH
    |--------------------------------------------------------------------------
    */

    Route::post('/dashboard/set-year', function (Request $request) {

        $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        session([
            'academic_year_id' => $request->academic_year_id
        ]);

        return back()->with('success', 'Academic year updated successfully.');

    })->name('dashboard.set-year');

    /*
    |--------------------------------------------------------------------------
    | ROLE DASHBOARDS
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:Admin')
        ->name('admin.dashboard');

    Route::get('/accountant/dashboard', [DashboardController::class, 'accountant'])
        ->middleware('role:Admin|Accountant')
        ->name('accountant.dashboard');

    Route::get('/registrar/dashboard', [DashboardController::class, 'registrar'])
        ->middleware('role:Admin|Registrar')
        ->name('registrar.dashboard');

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN USERS
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['permission:view users'])
        ->group(function () {

            Route::resource('users', UserController::class);

            Route::patch('users/{user}/block', [UserController::class, 'block'])
                ->name('users.block');

            Route::patch('users/{user}/unblock', [UserController::class, 'unblock'])
                ->name('users.unblock');
        });

    /*
    |--------------------------------------------------------------------------
    | PERMISSIONS
    |--------------------------------------------------------------------------
    */

    Route::prefix('permissions')
        ->name('permissions.')
        ->middleware(['permission:manage permissions'])
        ->group(function () {

            Route::get('/', [PermissionController::class, 'index'])->name('index');

            Route::post('/{user}/role', [PermissionController::class, 'assignRole'])
                ->name('assignRole');

            Route::post('/{user}/permissions', [PermissionController::class, 'assignPermissions'])
                ->name('assignPermissions');
        });

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC
    |--------------------------------------------------------------------------
    */

    Route::middleware(['permission:view students'])->group(function () {

        Route::resource('academic-years', AcademicYearController::class);
        Route::resource('classes', SchoolClassController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('students', StudentController::class);

        Route::get('students/{student}/payments', [StudentController::class, 'payments'])
            ->name('students.payments');
    });

    /*
    |--------------------------------------------------------------------------
    | FINANCE (INVOICES)
    |--------------------------------------------------------------------------
    */

    Route::middleware(['permission:view invoices'])->group(function () {

        Route::resource('fee-categories', FeeCategoryController::class);
        Route::resource('invoices', InvoiceController::class);
        Route::resource('invoice-items', InvoiceItemController::class);

        Route::post('invoices/{invoice}/record-payment', [InvoiceController::class, 'recordPayment'])
            ->middleware('permission:create payments')
            ->name('invoices.record-payment');
    });

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('payments')
        ->name('payments.')
        ->middleware(['auth'])
        ->group(function () {

            Route::get('/', [PaymentController::class, 'index'])
                ->middleware('permission:view payments')
                ->name('index');

            Route::get('/create', [PaymentController::class, 'create'])
                ->middleware('permission:create payments')
                ->name('create');

            Route::post('/', [PaymentController::class, 'store'])
                ->middleware('permission:create payments')
                ->name('store');

            Route::get('/{payment}', [PaymentController::class, 'show'])
                ->middleware('permission:view payments')
                ->name('show');

            Route::get('/{payment}/edit', [PaymentController::class, 'edit'])
                ->middleware('permission:edit payments')
                ->name('edit');

            Route::put('/{payment}', [PaymentController::class, 'update'])
                ->middleware('permission:edit payments')
                ->name('update');

            Route::delete('/{payment}', [PaymentController::class, 'destroy'])
                ->middleware('permission:delete payments')
                ->name('destroy');

            Route::get('/{payment}/receipt', [PaymentController::class, 'receipt'])
                ->middleware('permission:view payments')
                ->name('receipt');

            Route::get('/student-balance', [PaymentController::class, 'studentBalance'])
                ->middleware('permission:create payments')
                ->name('student-balance');
        });

    /*
    |--------------------------------------------------------------------------
    | EXPENSES
    |--------------------------------------------------------------------------
    */

    Route::resource('expenses', ExpenseController::class)
        ->middleware('permission:view expenses');

    /*
    |--------------------------------------------------------------------------
    | FINANCE REPORTS
    |--------------------------------------------------------------------------
    */

    Route::prefix('finance')
        ->name('finance.')
        ->middleware(['permission:view payments'])
        ->group(function () {

            Route::get('summary', [FinanceController::class, 'summary'])->name('summary');
            Route::get('income', [FinanceController::class, 'income'])->name('income');
            Route::get('classes', [FinanceController::class, 'classes'])->name('classes');
            Route::get('students', [FinanceController::class, 'students'])->name('students');
             Route::get('invoices', [FinanceController::class, 'invoices'])->name('invoices');
            Route::get('expenses', [FinanceController::class, 'expenses'])->name('expenses');
            Route::get('payments', [FinanceController::class, 'payments'])->name('payments');

            Route::get('balance', [BalanceReportController::class, 'index'])->name('balance');
            Route::get('balance/export', [BalanceReportController::class, 'export'])->name('balance.export');
        });

    /*
    |--------------------------------------------------------------------------
    | SETTINGS
    |--------------------------------------------------------------------------
    */

    Route::prefix('settings')
        ->name('settings.')
        ->middleware(['permission:manage permissions'])
        ->group(function () {

            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/update', [SettingController::class, 'update'])->name('update');
        });

    /*
    |--------------------------------------------------------------------------
    | 🔥 FIXED BACKUP SYSTEM (IMPORTANT FIX)
    |--------------------------------------------------------------------------
    */

Route::prefix('system')
    ->name('system.')
    ->middleware(['permission:manage permissions'])
    ->group(function () {

        // ✅ ONLY POST (correct)
        Route::post('/backup/run', [BackupController::class, 'run'])
            ->name('backup.run');

        // GET is OK here
        Route::get('/backup/download', [BackupController::class, 'download'])
            ->name('backup.download');
    });

});