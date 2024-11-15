<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

Route::post('/login', [API\AuthController::class, 'login']);
Route::post('/register', [API\AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [API\AuthController::class, 'logout']);
    Route::post('/refresh', [API\AuthController::class, 'refresh']);
    Route::get('/user', [API\AuthController::class, 'user']);

    Route::prefix('books')->group(function () {
        Route::post('/', [API\BookController::class, 'store']);
        Route::put('/{id}', [API\BookController::class, 'update']);
        Route::delete('/{id}', [API\BookController::class, 'destroy']);
    });

    Route::apiResource('authors', API\AuthorController::class);
});

Route::prefix('books')->group(function () {
    Route::get('/', [API\BookController::class, 'index']);
    Route::get('/{id}', [API\BookController::class, 'show']);
    Route::post('/search', [API\BookController::class, 'search']);
});
