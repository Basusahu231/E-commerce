<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::post('/get-otp', [AuthController::class, 'getOtp']);
Route::post('/validate-otp', [AuthController::class, 'validateOtp']);
Route::post('/register', [AuthController::class, 'register']);
