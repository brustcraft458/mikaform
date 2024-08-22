<?php

use App\Http\Controllers\FormDataController;
use App\Http\Controllers\FormTemplateController;
use App\Http\Controllers\UserController;
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

// Get
Route::get('/', fn() => redirect()->route('login'));
Route::get('/form/template', [FormTemplateController::class, 'index'])->name('form_template');
Route::get('/form/data/{uuid}', [FormDataController::class, 'index'])->name('form_data');
Route::get('/login', [UserController::class, 'webLogin'])->name('login');
Route::get('/register', [UserController::class, 'webRegister'])->name('register');

// Post
Route::post('/form/template', [FormTemplateController::class, 'store']);
Route::post('/login', [UserController::class, 'login']);
