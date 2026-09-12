<?php

use App\Http\Controllers\Staff\AuthController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\NoticeController;
use App\Http\Controllers\Staff\ReviewController;
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
| Staff Authenticated Portal
|--------------------------------------------------------------------------
*/
Route::middleware('staff.auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Applications Review Workflow
    Route::get('/applications', [ReviewController::class, 'index'])->name('applications.index');
    Route::get('/applications/{id}', [ReviewController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/start-review', [ReviewController::class, 'startReview'])->name('applications.start-review');
    Route::post('/applications/{id}/request-docs', [ReviewController::class, 'requestDocuments'])->name('applications.request-docs');
    Route::post('/applications/{id}/approve', [ReviewController::class, 'approve'])->name('applications.approve');
    Route::post('/applications/{id}/reject', [ReviewController::class, 'reject'])->name('applications.reject');

    // Notices Management
    Route::get('/notices', [NoticeController::class, 'index'])->name('notices.index');
    Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [NoticeController::class, 'store'])->name('notices.store');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');
});
