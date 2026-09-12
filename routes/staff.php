<?php

use App\Http\Controllers\Staff\AppointmentController;
use App\Http\Controllers\Staff\AuthController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\DistrictAdminController;
use App\Http\Controllers\Staff\LocalGovtAdminController;
use App\Http\Controllers\Staff\NoticeController;
use App\Http\Controllers\Staff\ReviewController;
use App\Http\Controllers\Staff\SuperAdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Staff Authentication
|--------------------------------------------------------------------------
*/
Route::middleware('guest:staff')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Staff Authenticated Portal (4-Tier Hierarchy)
|--------------------------------------------------------------------------
*/
Route::middleware('staff.auth')->group(function () {
    // Universal Dashboard Router (routes to Super, District, Local Govt, or Ward Dashboard)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =========================================================================
    // TIER 1: SUPER ADMIN
    // =========================================================================
    Route::middleware('staff.role:super_admin,admin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/geography', [SuperAdminController::class, 'geography'])->name('geography');
        Route::get('/admins', [SuperAdminController::class, 'admins'])->name('admins');
        Route::get('/audit-logs', [SuperAdminController::class, 'auditLogs'])->name('audit-logs');
    });

    // =========================================================================
    // TIER 2: DISTRICT ADMIN
    // =========================================================================
    Route::middleware('staff.role:district_admin,super_admin,admin')->prefix('district')->name('district.')->group(function () {
        Route::get('/dashboard', [DistrictAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/palikas', [DistrictAdminController::class, 'palikas'])->name('palikas');
        Route::get('/applications', [DistrictAdminController::class, 'applications'])->name('applications');
    });

    // =========================================================================
    // TIER 3: LOCAL GOVERNMENT ADMIN
    // =========================================================================
    Route::middleware('staff.role:local_government_admin,super_admin,admin')->prefix('localgovt')->name('localgovt.')->group(function () {
        Route::get('/dashboard', [LocalGovtAdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/wards', [LocalGovtAdminController::class, 'wards'])->name('wards');
        Route::get('/applications', [LocalGovtAdminController::class, 'applications'])->name('applications');
    });

    // =========================================================================
    // TIER 4: WARD ADMIN & OPERATIONAL WORKFLOW
    // =========================================================================
    // Applications Review Workflow
    Route::get('/applications', [ReviewController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [ReviewController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/start-review', [ReviewController::class, 'startReview'])->name('applications.start-review');
    Route::post('/applications/{id}/request-docs', [ReviewController::class, 'requestDocuments'])->name('applications.request-docs');
    Route::post('/applications/{id}/approve', [ReviewController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [ReviewController::class, 'reject'])->name('applications.reject');

    // Appointments Schedule Management
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Notices Management
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');
});
