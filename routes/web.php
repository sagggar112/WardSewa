<?php

use App\Http\Controllers\Citizen\ApplicationController;
use App\Http\Controllers\Citizen\AuthController;
use App\Http\Controllers\Citizen\BillPaymentController;
use App\Http\Controllers\Citizen\ComplaintController;
use App\Http\Controllers\Citizen\DashboardController;
use App\Http\Controllers\Citizen\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/notices', [PublicController::class, 'notices'])->name('notices.index');
Route::get('/verify/{token}', [PublicController::class, 'verifyCertificate'])->name('verify.certificate');

// Locale Switcher
Route::get('/locale/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ne'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }
    return redirect()->back(fallback: route('home'));
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Citizen Authentication
|--------------------------------------------------------------------------
*/
Route::prefix('citizen')->name('citizen.')->group(function () {
    Route::middleware('guest:citizen')->group(function () {
        // Password Login & Registration
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Citizen Protected Portal
    |--------------------------------------------------------------------------
    */
    Route::middleware('citizen.auth')->group(function () {
        // Ward Onboarding
        Route::get('/ward-select', [AuthController::class, 'showWardSelect'])->name('ward-select');
        Route::post('/ward-select', [AuthController::class, 'saveWardSelect'])->name('ward-select.save');

        // Dashboard & Profile
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // Applications & Recommendations
        Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/apply', [ApplicationController::class, 'create'])->name('applications.create');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{id}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::post('/applications/{id}/pay', [ApplicationController::class, 'initiatePayment'])->name('applications.pay');
        Route::get('/applications/{id}/payment-callback', [ApplicationController::class, 'paymentCallback'])->name('applications.payment-callback');
        Route::get('/applications/{id}/mock-pay', [ApplicationController::class, 'mockPay'])->name('applications.mock-pay');
        Route::get('/applications/{id}/certificate', [ApplicationController::class, 'downloadCertificate'])->name('applications.certificate');

        // Complaints & Grievances
        Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
        Route::get('/complaints/new', [ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
        Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');

        // Utility Bill Payments
        Route::get('/bills', [BillPaymentController::class, 'index'])->name('bills.index');
        Route::get('/bills/{id}', [BillPaymentController::class, 'showBiller'])->name('bills.show');
        Route::post('/bills/{id}/pay', [BillPaymentController::class, 'processPayment'])->name('bills.pay');
        Route::get('/bills/receipt/{id}', [BillPaymentController::class, 'receipt'])->name('bills.receipt');

        // Appointments (In-Person Ward Visits)
        Route::get('/appointments', [\App\Http\Controllers\Citizen\AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/book', [\App\Http\Controllers\Citizen\AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [\App\Http\Controllers\Citizen\AppointmentController::class, 'store'])->name('appointments.store');
    });
});
