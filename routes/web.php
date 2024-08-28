<?php

use App\Http\Controllers\FormDataController;
use App\Http\Controllers\FormTemplateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OTPVerificationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;


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
Route::get('/', fn () => redirect('/login'));
Route::get('/login', [LoginController::class, 'webLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');
// ====

// Rute untuk menampilkan form pengiriman OTP
Route::get('/send-otp', [ForgotPasswordController::class, 'showSendOtpForm'])->name('send-otp-form');

// Rute untuk menangani permintaan pengiriman OTP dan verifikasi OTP
Route::post('/handle-forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('handle-forgot-password');


// Rute untuk menampilkan form reset password setelah verifikasi OTP berhasil
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset-password-form');

// Rute POST untuk mengirimkan form reset password (OTP + password baru)
Route::post('/new-password', [ForgotPasswordController::class, 'resetPassword'])->name('reset-password');


//register_view+post

Route::get('/register', [RegisterController::class, 'webRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'handleRegister']);

//
Route::get('/form/template', fn () => view('form.template'))->name('form_template');