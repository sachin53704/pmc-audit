<?php

use Illuminate\Http\Request;
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

Route::get('/', function () {
    return redirect()->route('login');
})->name('/');




// Guest Users
Route::middleware(['guest', 'PreventBackHistory'])->group(function () {
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('signin');
    Route::get('register', [App\Http\Controllers\Admin\AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [App\Http\Controllers\Admin\AuthController::class, 'register'])->name('signup');
});




// Authenticated users
Route::middleware(['auth', 'PreventBackHistory', 'confirm-login-type'])->group(function () {

    // Auth Routes
    Route::get('home', fn() => redirect()->route('dashboard'))->name('home');
    Route::get('show-login-types', [App\Http\Controllers\Admin\AuthController::class, 'showLoginTypes'])->name('show-login-types');
    Route::get('confirm-login-type/{type}', [App\Http\Controllers\Admin\AuthController::class, 'confirmLoginType'])->name('confirm-login-type');
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'Logout'])->name('logout');
    Route::get('change-theme-mode', [App\Http\Controllers\Admin\DashboardController::class, 'changeThemeMode'])->name('change-theme-mode');
    Route::get('show-change-password', [App\Http\Controllers\Admin\AuthController::class, 'showChangePassword'])->name('show-change-password');
    Route::post('change-password', [App\Http\Controllers\Admin\AuthController::class, 'changePassword'])->name('change-password');



    // Masters
    Route::resource('fiscal_years', App\Http\Controllers\Admin\Masters\FiscalYearController::class);
    Route::resource('departments', App\Http\Controllers\Admin\Masters\DepartmentController::class);
    Route::resource('audit-para-category', App\Http\Controllers\Master\AuditParaCategoryController::class);
    Route::resource('audit-type', App\Http\Controllers\Master\AuditTypeController::class);
    Route::resource('severity', App\Http\Controllers\Master\SeverityController::class);
    Route::resource('zone', App\Http\Controllers\Master\ZoneController::class);

    Route::resource('diary', App\Http\Controllers\DiaryController::class);



    // Clerk Routes
    Route::resource('audit', App\Http\Controllers\Admin\ClerkAuditController::class);



    // MCA / DY MCA Routes
    Route::get('audit/status/{status}', [App\Http\Controllers\Admin\MCAAuditController::class, 'statusWiseAuditList'])->name('audit-list.status');
    Route::put('audit/status_change/{audit}', [App\Http\Controllers\Admin\MCAAuditController::class, 'auditStatusChange'])->name('audit.status-change');
    Route::get('assign-auditor', [App\Http\Controllers\Admin\MCAAuditController::class, 'assignAudiorList'])->name('assign-auditor');
    Route::get('audit/{audit}/get-auditors', [App\Http\Controllers\Admin\MCAAuditController::class, 'getAuditors'])->name('audit.get-auditors');
    Route::put('audit/{audit}/assign-auditor', [App\Http\Controllers\Admin\MCAAuditController::class, 'assignAuditor'])->name('audit.assign-auditor');
    Route::get('draft-review', [App\Http\Controllers\Admin\MCAAuditController::class, 'draftReview'])->name('draft-review');
    Route::get('draft-answer-details/{audit}', [App\Http\Controllers\Admin\MCAAuditController::class, 'draftAnswerDetails'])->name('draft-answer-details');
    Route::put('draft-approve-answers/{audit}', [App\Http\Controllers\Admin\MCAAuditController::class, 'draftApproveAnswer'])->name('draft-approve-answers');
    Route::get('final-report', [App\Http\Controllers\Admin\ReportController::class, 'finalReport'])->name('final-report');
    Route::get('para-audit-report', [App\Http\Controllers\Admin\ReportController::class, 'paraAuditReport'])->name('para-audit-report');
    Route::get('complience-answer-report', [App\Http\Controllers\Admin\ReportController::class, 'complienceAnswerReport'])->name('complience-answer-report');
    Route::post('report/getQuestion', [App\Http\Controllers\Admin\ReportController::class, 'getResponseQuestion'])->name('report-get-response-question');
    Route::post('report/getUnanswerQuestion', [App\Http\Controllers\Admin\ReportController::class, 'getUnanswerQuestion'])->name('report-get-unresponse-question');
    Route::get('report/department-program-audit', [App\Http\Controllers\Admin\ReportController::class, 'departmentWiseProgramAudit'])->name('department-program-audit');



    // Auditor Routes
    Route::get('assigned-audit', [App\Http\Controllers\Admin\AuditorAuditController::class, 'assignedAuditList'])->name('assigned-audit.index');
    Route::get('audit-info', [App\Http\Controllers\Admin\AuditorAuditController::class, 'getAuditInfo'])->name('get-audit-info');
    Route::post('send-letter', [App\Http\Controllers\Admin\AuditorAuditController::class, 'sendLetter'])->name('send-letter');
    Route::get('objection-create', [App\Http\Controllers\Admin\AuditorAuditController::class, 'createObjection'])->name('objection.create');
    Route::post('objection-store', [App\Http\Controllers\Admin\AuditorAuditController::class, 'storeObjection'])->name('objection.store');
    Route::get('answered-questions', [App\Http\Controllers\Admin\AuditorAuditController::class, 'answeredQuestions'])->name('answered-questions');
    Route::get('answer-details/{audit}', [App\Http\Controllers\Admin\AuditorAuditController::class, 'answerDetails'])->name('answer-details');
    Route::put('approve-answers/{audit}', [App\Http\Controllers\Admin\AuditorAuditController::class, 'approveAnswer'])->name('approve-answers');



    // Department Routes
    Route::get('department_letter', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'index'])->name('department-letter.index');
    Route::get('compliance-create', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'createCompliance'])->name('compliance.create');
    Route::get('compliance-info/{audit}', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'complianceInfo'])->name('compliance.info');
    Route::put('compliance-update/{audit}', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'updateCompliance'])->name('compliance.update');

    // Account Department Routes
    Route::resource('receipts', App\Http\Controllers\Admin\AccountReceiptController::class);
    Route::resource('payment-receipts', App\Http\Controllers\Admin\AccountPaymentReceiptController::class);

    // RECEIPT APPROVE/REJECT Routes
    Route::get('receipts/status/pending-list', [App\Http\Controllers\Admin\AccountReceiptController::class, 'pendingReceipts'])->name('receipts.pending-list');
    Route::get('receipts/status/approved-list', [App\Http\Controllers\Admin\AccountReceiptController::class, 'approvedReceipts'])->name('receipts.approved-list');
    Route::get('receipts/status/rejected-list', [App\Http\Controllers\Admin\AccountReceiptController::class, 'rejectedReceipts'])->name('receipts.rejected-list');
    Route::get('receipts/details/{receipt}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'receiptDetails'])->name('receipts.details');
    Route::get('receipt-info/{receipt}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'receiptInfo'])->name('receipts.info');
    Route::put('approve-receipts/{receipt}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'approveReceipts'])->name('approve-receipts');

    // PAYMENT RECEIPT APPROVE/REJECT Routes
    Route::get('payment-receipts/status/pending-list', [App\Http\Controllers\Admin\AccountPaymentReceiptController::class, 'pendingReceipts'])->name('payment-receipts.pending-list');
    Route::get('payment-receipts/status/approved-list', [App\Http\Controllers\Admin\AccountPaymentReceiptController::class, 'approvedReceipts'])->name('payment-receipts.approved-list');
    Route::get('payment-receipts/status/rejected-list', [App\Http\Controllers\Admin\AccountPaymentReceiptController::class, 'rejectedReceipts'])->name('payment-receipts.rejected-list');
    Route::get('payment-receipts/details/{payment_receipt}', [App\Http\Controllers\Admin\AccountPaymentReceiptController::class, 'receiptDetails'])->name('payment-receipts.details');
    Route::get('payment-receipt-info/{payment_receipt}', [App\Http\Controllers\Admin\AccountPaymentReceiptController::class, 'receiptInfo'])->name('payment-receipts.info');
    Route::put('approve-payment-receipts/{payment_receipt}', [App\Http\Controllers\Admin\AccountPaymentReceiptController::class, 'approveReceipts'])->name('approve-payment-receipts');

    // // DY MCA ROUTES
    // Route::get('dy-mca-receipt-info/{receipt}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'receiptInfo'] )->name('receipts.info');
    // Route::put('dy-mca-approve-receipts/{audit}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'approveReceipts'] )->name('approve-receipts');

    // // MCA ROUTES
    // Route::get('mca-receipt-info/{receipt}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'receiptInfo'] )->name('receipts.info');
    // Route::put('mca-approve-receipts/{audit}', [App\Http\Controllers\Admin\AccountReceiptController::class, 'approveReceipts'] )->name('approve-receipts');



    // Users Roles n Permissions
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::get('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle'])->name('users.toggle');
    Route::get('users/{user}/retire', [App\Http\Controllers\Admin\UserController::class, 'retire'])->name('users.retire');
    Route::put('users/{user}/change-password', [App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');
    Route::get('users/{user}/get-role', [App\Http\Controllers\Admin\UserController::class, 'getRole'])->name('users.get-role');
    Route::put('users/{user}/assign-role', [App\Http\Controllers\Admin\UserController::class, 'assignRole'])->name('users.assign-role');
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
});




Route::get('/php', function (Request $request) {
    if (!auth()->check())
        return 'Unauthorized request';

    Artisan::call($request->artisan);
    return dd(Artisan::output());
});
