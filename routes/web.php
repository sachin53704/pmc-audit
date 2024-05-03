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
Route::middleware(['guest','PreventBackHistory'])->group(function()
{
    Route::get('login', [App\Http\Controllers\Admin\AuthController::class, 'showLogin'] )->name('login');
    Route::post('login', [App\Http\Controllers\Admin\AuthController::class, 'login'])->name('signin');
    Route::get('register', [App\Http\Controllers\Admin\AuthController::class, 'showRegister'] )->name('register');
    Route::post('register', [App\Http\Controllers\Admin\AuthController::class, 'register'])->name('signup');

});




// Authenticated users
Route::middleware(['auth','PreventBackHistory'])->group(function()
{

    // Auth Routes
    Route::get('home', fn () => redirect()->route('dashboard'))->name('home');
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [App\Http\Controllers\Admin\AuthController::class, 'Logout'])->name('logout');
    Route::get('change-theme-mode', [App\Http\Controllers\Admin\DashboardController::class, 'changeThemeMode'])->name('change-theme-mode');
    Route::get('show-change-password', [App\Http\Controllers\Admin\AuthController::class, 'showChangePassword'] )->name('show-change-password');
    Route::post('change-password', [App\Http\Controllers\Admin\AuthController::class, 'changePassword'] )->name('change-password');



    // Masters
    Route::resource('fiscal_years', App\Http\Controllers\Admin\Masters\FiscalYearController::class );
    Route::resource('departments', App\Http\Controllers\Admin\Masters\DepartmentController::class );



    // Clerk Routes
    Route::resource('audit', App\Http\Controllers\Admin\ClerkAuditController::class );



    // MCA / DY MCA Routes
    Route::get('audit/status/{status}', [App\Http\Controllers\Admin\MCAAuditController::class, 'statusWiseAuditList'] )->name('audit-list.status');
    Route::put('audit/status_change/{audit}', [App\Http\Controllers\Admin\MCAAuditController::class, 'auditStatusChange'] )->name('audit.status-change');
    Route::get('assign-auditor', [App\Http\Controllers\Admin\MCAAuditController::class, 'assignAudiorList' ])->name('assign-auditor');
    Route::get('audit/{audit}/get-auditors', [App\Http\Controllers\Admin\MCAAuditController::class, 'getAuditors' ])->name('audit.get-auditors');
    Route::put('audit/{audit}/assign-auditor', [App\Http\Controllers\Admin\MCAAuditController::class, 'assignAuditor' ])->name('audit.assign-auditor');
    Route::get('draft-review', [App\Http\Controllers\Admin\MCAAuditController::class, 'draftReview'] )->name('draft-review');
    Route::get('draft-answer-details/{audit}', [App\Http\Controllers\Admin\MCAAuditController::class, 'draftAnswerDetails'] )->name('draft-answer-details');
    Route::put('draft-approve-answers/{audit}', [App\Http\Controllers\Admin\MCAAuditController::class, 'draftApproveAnswer'] )->name('draft-approve-answers');



    // Auditor Routes
    Route::get('assigned-audit', [App\Http\Controllers\Admin\AuditorAuditController::class, 'assignedAuditList'] )->name('assigned-audit.index');
    Route::get('audit-info', [App\Http\Controllers\Admin\AuditorAuditController::class, 'getAuditInfo'] )->name('get-audit-info');
    Route::post('send-letter', [App\Http\Controllers\Admin\AuditorAuditController::class, 'sendLetter'] )->name('send-letter');
    Route::get('objection-create', [App\Http\Controllers\Admin\AuditorAuditController::class, 'createObjection'] )->name('objection.create');
    Route::post('objection-store', [App\Http\Controllers\Admin\AuditorAuditController::class, 'storeObjection'] )->name('objection.store');
    Route::get('answered-questions', [App\Http\Controllers\Admin\AuditorAuditController::class, 'answeredQuestions'] )->name('answered-questions');
    Route::get('answer-details/{audit}', [App\Http\Controllers\Admin\AuditorAuditController::class, 'answerDetails'] )->name('answer-details');
    Route::put('approve-answers/{audit}', [App\Http\Controllers\Admin\AuditorAuditController::class, 'approveAnswer'] )->name('approve-answers');



    // Department Routes
    Route::get('department_letter', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'index'] )->name('department-letter.index');
    Route::get('compliance-create', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'createCompliance'] )->name('compliance.create');
    Route::get('compliance-info/{audit}', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'complianceInfo'] )->name('compliance.info');
    Route::put('compliance-update/{audit}', [App\Http\Controllers\Admin\DepartmentAuditController::class, 'updateCompliance'] )->name('compliance.update');



    // Users Roles n Permissions
    Route::resource('users', App\Http\Controllers\Admin\UserController::class );
    Route::get('users/{user}/toggle', [App\Http\Controllers\Admin\UserController::class, 'toggle' ])->name('users.toggle');
    Route::get('users/{user}/retire', [App\Http\Controllers\Admin\UserController::class, 'retire' ])->name('users.retire');
    Route::put('users/{user}/change-password', [App\Http\Controllers\Admin\UserController::class, 'changePassword' ])->name('users.change-password');
    Route::get('users/{user}/get-role', [App\Http\Controllers\Admin\UserController::class, 'getRole' ])->name('users.get-role');
    Route::put('users/{user}/assign-role', [App\Http\Controllers\Admin\UserController::class, 'assignRole' ])->name('users.assign-role');
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class );

});




Route::get('/php', function(Request $request){
    if( !auth()->check() )
        return 'Unauthorized request';

    Artisan::call($request->artisan);
    return dd(Artisan::output());
});
