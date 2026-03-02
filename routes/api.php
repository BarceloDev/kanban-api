<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PicturesController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/mypictures', [PicturesController::class, 'index']);
    Route::post('/pictures', [PicturesController::class, 'store']);
    Route::put('/pictures/{picture}', [PicturesController::class, 'update']);
    Route::delete('/pictures/{picture}', [PicturesController::class, 'destroy']);

    Route::get('/pictures/{picture}/tasks', [TaskController::class, 'index']);
    Route::post('/pictures/{picture}/tasks', [TaskController::class, 'store']);
    Route::get('/pictures/{picture}', [PicturesController::class, 'show']);

    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/pictures/share/{token}', [PicturesController::class, 'share']);