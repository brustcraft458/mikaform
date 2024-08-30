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
// login_view+post
Route::get('/', fn() => redirect('/login'))->name('landing');
Route::get('/login', [LoginController::class, 'webLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);
// ====

// Rute untuk menampilkan form pengiriman OTP
Route::get('/send-otp', [ForgotPasswordController::class, 'showSendOtpForm'])->name('send-otp-form');

// Rute untuk menangani permintaan pengiriman OTP dan verifikasi OTP
Route::post('/handle-forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('handle-forgot-password');


// Rute untuk menampilkan form reset password setelah verifikasi OTP berhasil
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password-form');

// Rute POST untuk mengirimkan form reset password (OTP + password baru)
Route::post('/new-password', [ForgotPasswordController::class, 'resetPasswords'])->name('reset-password');



//register_view+post
Route::get('/register', [RegisterController::class, 'webRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'handleRegister']);

// template
Route::get('/form/template', [FormTemplateController::class, 'index'])->name('form_template');
Route::post('/form/template', [FormTemplateController::class, 'store']);

// kelola show data user
Route::get('/user/manage', [KelolaUserController::class, 'index'])->name('user_manage');
// Route untuk mengubah role user
Route::post('/user/manage', [KelolaUserController::class, 'handleManage']);

// Show Profile
Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');

// data
Route::get('/form/data/{uuid}', [FormDataController::class, 'webData'])->name('form_data');

// share
Route::get('/form/share/{uuid}', [FormDataController::class, 'webShare'])->name('form_share');
Route::post('/form/share/{uuid}', [FormDataController::class, 'userInput']);

// presence qr
Route::get('/presence/{uuid}', [PresenceController::class, 'webPresence']);