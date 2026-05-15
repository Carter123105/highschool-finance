<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\BalanceReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'));

/*
|--------------------------------------------------------------------------
| AUTH + VERIFIED
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:Admin')
        ->name('admin.dashboard');

    Route::get('/accountant/dashboard', [DashboardController::class, 'accountant'])
        ->middleware('role:Admin|Accountant')
        ->name('accountant.dashboard');

    Route::get('/registrar/dashboard', [DashboardController::class, 'registrar'])
        ->middleware('role:Registrar')
        ->name('registrar.dashboard');

    Route::post('/dashboard/set-year', function (Request $request) {
        $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        session(['academic_year_id' => $request->academic_year_id]);

        return back()->with('success', 'Academic year updated successfully.');
    })->name('dashboard.set-year');

    Route::post('/finance/set-year', function (Request $request) {
        $request->validate([
            'academic_year_id' => 'nullable|exists:academic_years,id',
        ]);

        session(['academic_year_id' => $request->academic_year_id]);

        return back()->with('success', 'Academic year updated successfully.');
    })->name('finance.set-year');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* ---------------- PROFILE ---------------- */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /* ---------------- ADMIN ---------------- */
    Route::middleware('role:Admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class);
            Route::patch('users/{user}/block', [UserController::class, 'block'])->name('users.block');
            Route::patch('users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
        });

    /* ---------------- ACADEMIC ---------------- */
    Route::middleware('role:Admin|Registrar')->group(function () {
        Route::resource('academic-years', AcademicYearController::class);
        Route::resource('classes', SchoolClassController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('students', StudentController::class);
    });

    /* ---------------- FINANCE ---------------- */
    Route::middleware('role:Admin|Accountant')
        ->prefix('finance')
        ->name('finance.')
        ->group(function () {

            Route::get('/summary', [FinanceController::class, 'summary'])->name('summary');
            Route::get('/daily-transactions', [FinanceController::class, 'dailyTransactions'])->name('daily.transactions');

            Route::get('/income', [FinanceController::class, 'income'])->name('income');
            Route::get('/expenses', [FinanceController::class, 'expenses'])->name('expenses');

            Route::get('/payments', [FinanceController::class, 'payments'])->name('payments');
            Route::get('/students', [FinanceController::class, 'students'])->name('students');

            Route::get('/invoices', [FinanceController::class, 'invoices'])->name('invoices');

            Route::get('/balance', [BalanceReportController::class, 'index'])->name('balance');
            Route::get('/balance/export', [BalanceReportController::class, 'export'])->name('balance.export');

            Route::get('/classes', [FinanceController::class, 'classes'])->name('classes');
            Route::get('/classes/{classId}/students', [FinanceController::class, 'classStudents'])->name('classes.students');

            /*
            |------------------------------------------------------
            | FIXED INVOICE VIEW ROUTE (NO 404)
            |------------------------------------------------------
            */
            Route::get('/invoice/student/{invoice}', [FinanceController::class, 'studentInvoice'])
                ->name('invoice.student');
        });

    /* ---------------- SHARED ADMIN/ACCOUNTANT ---------------- */
    Route::middleware('role:Admin|Accountant')->group(function () {
        Route::resource('fee-categories', FeeCategoryController::class);
        Route::resource('invoices', InvoiceController::class);
        Route::resource('invoice-items', InvoiceItemController::class);
        Route::resource('payments', PaymentController::class);
        Route::resource('expenses', ExpenseController::class);
    });

    /* ---------------- SETTINGS ---------------- */
    Route::prefix('settings')
        ->name('settings.')
        ->middleware('role:Admin')
        ->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/update', [SettingController::class, 'update'])->name('update');
        });
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';