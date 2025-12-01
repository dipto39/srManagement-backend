<?php

use App\Events\TestMessageEvent;
use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;


// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

});

Route::post('/send-mail', [AuthController::class, 'sendMail']);
Route::get('/test-broadcast', function () {
    broadcast(new TestMessageEvent("Hello from Laravel Reverb!"));
    return response()->json(['status' => 'sent']);
});
// Route::post('/upload', [AuthController::class, 'fileUpload']);



