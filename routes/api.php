<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PicturesController;
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
    Route::post('/pictures', [PicturesController::class, 'store']);
    Route::get('/mypictures', [PicturesController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/pictures/share/{token}', [PicturesController::class, 'share']);
});