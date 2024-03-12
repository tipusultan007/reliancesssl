<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Auth::routes();


Route::post('login', [\App\Http\Controllers\LoginController::class,'login']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [\App\Http\Controllers\ReportController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
    Route::get('/', [\App\Http\Controllers\ReportController::class,'dashboard']);

    Route::get('new-account',[\App\Http\Controllers\AccountController::class,'newAccount'])->name('new.account');
Route::post('store-account',[\App\Http\Controllers\AccountController::class,'store'])->name('store.account');

    Route::resource('members',\App\Http\Controllers\MemberController::class);
Route::resource('daily-savings',\App\Http\Controllers\DailySavingController::class);
Route::resource('monthly-savings',\App\Http\Controllers\MonthlySavingController::class);
Route::resource('daily-loans',\App\Http\Controllers\DailyLoanController::class);
Route::resource('monthly-loans',\App\Http\Controllers\MonthlyLoanController::class);
Route::resource('cash-receive-categories',\App\Http\Controllers\CashReceiveCategoryController::class);
Route::resource('cash-payment-categories',\App\Http\Controllers\CashPaymentCategoryController::class);
Route::resource('cash-receives',\App\Http\Controllers\CashReceiveController::class);
Route::resource('cash-payments',\App\Http\Controllers\CashPaymentController::class);
Route::resource('daily-collections',\App\Http\Controllers\DailyCollectionController::class);
Route::resource('monthly-collections',\App\Http\Controllers\MonthlyCollectionController::class);
Route::resource('account-closing',\App\Http\Controllers\AccountClosingController::class);
Route::resource('income-categories',\App\Http\Controllers\IncomeCategoryController::class);
Route::resource('expense-categories',\App\Http\Controllers\ExpenseCategoryController::class);
Route::resource('incomes',\App\Http\Controllers\IncomeController::class);
Route::resource('expenses',\App\Http\Controllers\ExpenseController::class);
Route::resource('nominees',\App\Http\Controllers\NomineeController::class);
Route::resource('fdr',\App\Http\Controllers\FdrController::class);
Route::resource('profit-collections',\App\Http\Controllers\FdrCollectionController::class);
Route::resource('salaries',\App\Http\Controllers\SalaryController::class);
Route::resource('users',\App\Http\Controllers\UserController::class);
Route::resource('daily-attendances',\App\Http\Controllers\DailyAttendanceController::class);
Route::resource('fdr-deposits',\App\Http\Controllers\FdrDepositController::class);
Route::resource('fdr-withdraws',\App\Http\Controllers\FdrWithdrawController::class);
Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

// Attendance Routes
Route::resource('attendance', AttendanceController::class);

// Leave Routes
Route::resource('leave', LeaveController::class);

// Payroll Routes
Route::get('payrolls/getEmployeeDays', [PayrollController::class, 'getEmployeeDays'])->name('payrolls.getEmployeeDays');
Route::resource('payrolls', PayrollController::class);

Route::get('get-fdr/{account}',[\App\Http\Controllers\FdrCollectionController::class,'getFdr']);
Route::get('dataProfitCollections',[\App\Http\Controllers\FdrCollectionController::class,'dataProfitCollections']);
Route::get('countMonths',[\App\Http\Controllers\FdrCollectionController::class,'countMonths']);
Route::get('generateProfit/{id}',[\App\Http\Controllers\FdrCollectionController::class,'generateProfit']);

Route::get('getFdrDeposits/{id}',[\App\Http\Controllers\FdrDepositController::class,'fdrDeposits']);
Route::get('dataDeposits',[\App\Http\Controllers\FdrDepositController::class,'dataDeposits']);
Route::get('dataWithdraws',[\App\Http\Controllers\FdrWithdrawController::class,'dataWithdraws']);
Route::get('membersData',[\App\Http\Controllers\MemberController::class,'membersData']);
Route::get('dataAllSavings',[\App\Http\Controllers\DailySavingController::class,'dataAllSavings']);
Route::get('dataProfitByAccount',[\App\Http\Controllers\FdrCollectionController::class,'dataProfitByAccount']);
Route::get('dataSalaries',[\App\Http\Controllers\SalaryController::class,'dataSalaries']);
Route::get('usersData',[\App\Http\Controllers\UserController::class,'usersData']);
Route::get('dataAllFdr',[\App\Http\Controllers\FdrController::class,'dataAllFdr']);
Route::get('dataLoans',[\App\Http\Controllers\DailyLoanController::class,'dataLoans']);
Route::get('dataMonthlyLoans',[\App\Http\Controllers\MonthlyLoanController::class,'dataLoans']);
Route::get('dataMonthlySavings',[\App\Http\Controllers\MonthlySavingController::class,'dataSavings']);
Route::get('getSavingsTransaction',[\App\Http\Controllers\DailySavingController::class,'getSavingsTransaction']);
Route::get('get-member/{id}',[\App\Http\Controllers\MemberController::class,'getDetails']);
Route::get('savingsDetails/{id}',[\App\Http\Controllers\DailySavingController::class,'savingsDetails']);
Route::get('guarantorDetails/{id}',[\App\Http\Controllers\DailySavingController::class,'guarantorDetails']);
Route::get('getMonthlySavings',[\App\Http\Controllers\MonthlySavingController::class,'getMonthlySavings']);
Route::get('get-savings',[\App\Http\Controllers\DailySavingController::class,'getSavings']);
Route::get('existSavingAccount/{account}',[\App\Http\Controllers\DailySavingController::class,'existAccount']);
Route::get('existFdrAccount/{account}',[\App\Http\Controllers\FdrController::class,'existAccount']);
Route::get('existMonthlyAccount/{account}',[\App\Http\Controllers\MonthlySavingController::class,'existMonthlyAccount']);
Route::get('dataCollections',[\App\Http\Controllers\DailyCollectionController::class,'dataCollections']);
Route::get('dataIncomeCategories',[\App\Http\Controllers\IncomeCategoryController::class,'dataIncomeCategories']);
Route::get('dataIncomes',[\App\Http\Controllers\IncomeController::class,'dataIncomes']);
Route::get('dataExpenses',[\App\Http\Controllers\ExpenseController::class,'dataExpenses']);
Route::get('dataExpenseCategories',[\App\Http\Controllers\ExpenseCategoryController::class,'dataExpenseCategories']);
Route::get('dataMonthlyCollections',[\App\Http\Controllers\MonthlyCollectionController::class,'dataCollections']);
Route::get('dataCollectionsByAccount',[\App\Http\Controllers\MonthlyCollectionController::class,'dataCollectionsByAccount']);
Route::get('getDetails/{id}',[\App\Http\Controllers\DailyCollectionController::class,'getDetails']);
Route::get('getFdrWithdrawDetails/{id}',[\App\Http\Controllers\FdrWithdrawController::class,'getDetails']);
Route::get('getFdrProfitDetails/{id}',[\App\Http\Controllers\FdrCollectionController::class,'getProfitDetails']);
Route::get('getDetailsMonthly/{id}',[\App\Http\Controllers\MonthlyCollectionController::class,'getDetailsMonthly']);
Route::get('getMonthlyDetails/{account}',[\App\Http\Controllers\MonthlyLoanController::class,'getDetails']);
Route::get('makeAccountActive/{account}',[\App\Http\Controllers\AccountClosingController::class,'makeAccountActive']);
Route::get('dataMemberAccounts/{id}',[\App\Http\Controllers\MemberController::class,'dataMemberAccounts']);

Route::get('daily-report',[\App\Http\Controllers\ReportController::class,'dailyReport']);
Route::get('daily-savings-report',[\App\Http\Controllers\ReportController::class,'savingCollections']);
Route::get('monthly-savings-report',[\App\Http\Controllers\ReportController::class,'monthlyCollections']);
Route::get('daily-loan-report',[\App\Http\Controllers\ReportController::class,'dailyLoanCollections']);
Route::get('monthly-loan-report',[\App\Http\Controllers\ReportController::class,'monthlyLoanCollections']);
Route::get('profit-collection-report',[\App\Http\Controllers\ReportController::class,'fdrCollections']);
Route::get('fdr-deposit-report',[\App\Http\Controllers\ReportController::class,'fdrDeposits']);
Route::get('fdr-withdraw-report',[\App\Http\Controllers\ReportController::class,'fdrWithdraws']);
    Route::get('fdr-withdraws-data',[\App\Http\Controllers\ReportController::class,'dataFdrWithdraws']);
    Route::get('fdr-deposits-data',[\App\Http\Controllers\ReportController::class,'dataFdrDeposits']);
    Route::get('savingCollectionsData',[\App\Http\Controllers\ReportController::class,'savingCollectionsData']);
    Route::get('monthlyCollectionsData',[\App\Http\Controllers\ReportController::class,'monthlyCollectionsData']);
    Route::get('dailyLoanCollectionsData',[\App\Http\Controllers\ReportController::class,'dailyLoanCollectionsData']);
    Route::get('monthlyLoanCollectionsData',[\App\Http\Controllers\ReportController::class,'monthlyLoanCollectionsData']);
    Route::get('profitCollectionsData',[\App\Http\Controllers\ReportController::class,'profitCollectionsData']);
    Route::get('transaction-summary-report',[\App\Http\Controllers\ReportController::class,'transactionSummary']);
    Route::get('data-transaction-summary',[\App\Http\Controllers\ReportController::class,'dataTransactionSummary']);
    Route::resource('roles', RoleController::class);

    Route::get('print/monthly/{id}',[\App\Http\Controllers\PrintController::class,'monthly']);
    Route::get('print/fdr-withdraw/{id}',[\App\Http\Controllers\PrintController::class,'fdrWithdraw']);

    Route::post('fdr-closing',[\App\Http\Controllers\FdrCollectionController::class,'fdrClosing'])->name('fdr.closing');
    Route::get('activate-fdr/{id}',[\App\Http\Controllers\FdrCollectionController::class,'activeFdr'])->name('fdr.active');

        Route::get('delete-loan-collection/{id}',[\App\Http\Controllers\DailyCollectionController::class,'deleteLoanCollection'])->name('delete.loan.collection');
    Route::get('delete-saving-collection/{id}',[\App\Http\Controllers\DailyCollectionController::class,'deleteSavingCollection']);

//Route::get('get-attendance/{id}',[AttendanceController::class,'getAttendance']);
    Route::post('importMonthlySavings',[\App\Http\Controllers\MonthlyCollectionController::class,'importMonthlySavings'])->name('importMonthlySavings');

});

// Route::get('/create-storage-link', function () {
//     Artisan::call('storage:link');
//     return 'Storage link created successfully';
// });
Route::get('/import', [ImportController::class, 'importCSVForm'])->name('import.form');
Route::post('/import-daily', [ImportController::class, 'importDailySavings'])->name('import.daily');
Route::post('/import-monthly', [ImportController::class, 'importMonthlySavings'])->name('import.monthly');

Route::resource('add-profits',\App\Http\Controllers\AddProfitController::class);
Route::get('daily-profits',[\App\Http\Controllers\AddProfitController::class,'dailyProfits'])->name('profits.daily');
Route::get('monthly-profits',[\App\Http\Controllers\AddProfitController::class,'monthlyProfits'])->name('profits.monthly');

Route::post('daily-loan-status',[\App\Http\Controllers\DailyLoanController::class,'changeStatus'])->name('daily.loan.status.update');
Route::post('monthly-loan-status',[\App\Http\Controllers\MonthlyCollectionController::class,'changeStatus'])->name('monthly.loan.status.update');
