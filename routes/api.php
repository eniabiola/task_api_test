<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmailVerificationAPIController;
use App\Http\Controllers\API\TaskAPIController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/email/verify/{id}/{hash}', [EmailVerificationAPIController::class, 'verifyEmailLink'])
    ->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', [EmailVerificationAPIController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('tasks')->group(function () {
        Route::post('/', [TaskAPIController::class, 'store']);
        Route::get('/', [TaskAPIController::class, 'index']);
        Route::get('{id}', [TaskAPIController::class, 'show']);
        Route::patch('{id}/status', [TaskAPIController::class, 'updateStatus']);
        Route::delete('{id}', [TaskAPIController::class, 'destroy']);
    });
});
