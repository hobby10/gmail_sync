<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::get('/', [\App\Http\Controllers\GmailController::class, 'test']);
Route::get('/email', [\App\Http\Controllers\GmailController::class, 'index']);
Route::get('/send_email', [\App\Http\Controllers\GmailController::class, 'sendEmail']);
