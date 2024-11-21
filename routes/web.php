<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OtpController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::view('/login-with-otp', 'auth.loginwithotp' )->name('login.with.otp');
Route::post('/login-with-otp-post', [OtpController::class, 'loginwithotppost'])->name('login.with.otp.post');

Route::view('/confirm-login-with-otp', 'auth.confirmloginotp' )->name('confirm.login.with.otp');
Route::post('/otp-verify', [OtpController::class, 'otpVerify'])->name('otp.verify');

