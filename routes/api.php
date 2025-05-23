<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmailVerificationAPIController;
use App\Http\Controllers\API\TaskAPIController;
use App\Http\Controllers\API\TaskStatusAPIController;
use App\Http\Controllers\API\TaskStatusHistoryAPIController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('tasks')->group(function () {
        Route::post('/', [TaskAPIController::class, 'store']);
        Route::get('/', [TaskAPIController::class, 'index']);
        Route::get('{id}', [TaskAPIController::class, 'show']);
        Route::patch('{id}/status', [TaskAPIController::class, 'updateStatus']);
        Route::delete('{id}', [TaskAPIController::class, 'destroy']);
    });
    Route::prefix('task-statuses')->group(function () {
        Route::get('/', [TaskStatusAPIController::class, 'index']);
    });
    Route::prefix('task-status-histories/{taskId}')->group(function () {
        Route::get('/', [TaskStatusHistoryAPIController::class, 'index']);
    });
});
