<?php

use App\Http\Controllers\FormDataController;
use App\Http\Controllers\FormTemplateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OTPVerificationController;
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
// login_view+post
Route::get('/', fn () => redirect('/login'));
Route::get('/login', [LoginController::class, 'webLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');
// ====

//register_view+post
Route::get('/register', [RegisterController::class, 'webRegister'])->name('register');
Route::post('/register', [RegisterController::class, 'handleRegister']);

//
Route::get('/form/template', [FormTemplateController::class, 'index'])->name('form_template');
Route::post('/form/template', [FormTemplateController::class, 'store']);

// share
Route::get('/form/share/{uuid}', [FormDataController::class, 'webShare'])->name('form_share');
Route::post('/form/share/{uuid}', [FormDataController::class, 'userInput']);