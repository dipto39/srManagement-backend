<?php

use App\Events\PrivateEvent;
use App\Events\TestMessageEvent;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\FirebaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
// otp
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

// Forget Password
Route::middleware(['auth:sanctum', 'abilities:password:reset'])->post('/forgot-password', [AuthController::class, 'forgotPassword']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
});

Route::get('/test-broadcast', function () {
    broadcast(new TestMessageEvent("Hello from Laravel Reverb!"));
    return response()->json(['status' => 'sent']);
});
Route::post('/send-private-event', function (Request $request) {
    broadcast(new PrivateEvent(
        $request->message,
        $request->user_id
    ));

    return response()->json([
        'status' => 'private event sent'
    ]);
});
// Route::post('/upload', [AuthController::class, 'fileUpload']);
Route::get('/test-firebase', function (Kreait\Firebase\Contract\Database $database) {
    try {
        $reference = $database->getReference('test');
        $reference->set(['status' => 'connected', 'time' => now()->toString()]);
        
        return 'Firebase connected successfully!';
    } catch (\Exception $e) {
        return 'Firebase error: ' . $e->getMessage();
    }
});

// Firebase Routes
Route::post('/firebase/verify-token', [FirebaseController::class, 'verifyToken']);
Route::get('/firebase/users', [FirebaseController::class, 'getUsers']);
Route::post('/firebase/users', [FirebaseController::class, 'createUser']);
Route::post('/firebase/upload', [FirebaseController::class, 'uploadFile']);



