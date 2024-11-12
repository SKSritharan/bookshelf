<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('books')->group(function () {
    Route::get('/', [API\BookController::class, 'index']);
    Route::post('/', [API\BookController::class, 'store']);
    Route::get('/{id}', [API\BookController::class, 'show']);
    Route::put('/{id}', [API\BookController::class, 'update']);
    Route::delete('/{id}', [API\BookController::class, 'destroy']);
    Route::post('/search', [API\BookController::class, 'search']);
});

Route::apiResource('authors', API\AuthorController::class);
