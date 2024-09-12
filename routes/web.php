<?php

use App\Http\Controllers\FormDataController;
use App\Http\Controllers\FormTemplateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OTPVerificationController;
use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\KelolaUserController;
use App\Http\Controllers\UserProfileController;


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

// landing
Route::get('/', fn() => redirect('/login'))->name('landing');

// login
Route::get('/login', [LoginController::class, 'webLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);

// forgot password
Route::post('/handle-forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('handle-forgot-password');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password-form');
Route::post('/new-password', [ForgotPasswordController::class, 'resetPasswords'])->name('reset-password');
Route::get('/send-otp', [ForgotPasswordController::class, 'showSendOtpForm'])->name('send-otp-form');

// register
Route::get('/register', [RegisterController::class, 'webRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'handleRegister']);


// role admin
Route::middleware(['role.admin'])->group(function () {
    // form template
    Route::get('/form/template', [FormTemplateController::class, 'index'])->name('form_template');
    Route::post('/form/template', [FormTemplateController::class, 'handleForm']);

    // form data
    Route::get('/form/data/{uuid}', [FormDataController::class, 'webData'])->name('form_data');

    // presence qr
    Route::get('/presence/scan/{uuid}', [PresenceController::class, 'webScanner'])->name('presence_scanner');
    Route::post('/presence/scan/{uuid}', [PresenceController::class, 'handlePresence']);

    // profile
    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile'); 
});

// role super admin
Route::middleware(['role.superadmin'])->group(function () {
    // manage user
    Route::get('/user/manage', [KelolaUserController::class, 'index'])->name('user_manage');
    Route::post('/user/manage', [KelolaUserController::class, 'handleManage']);
});


// form share
Route::get('/form/share/{uuid}', [FormDataController::class, 'webShare'])->name('form_share');
Route::post('/form/share/{uuid}', [FormDataController::class, 'userInput']);

// presence qr
Route::get('/presence/{uuid}', [PresenceController::class, 'webPresence'])->name('presence_user');