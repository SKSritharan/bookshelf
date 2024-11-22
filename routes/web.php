<?php

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {

    $books = Book::latest()->cursorPaginate(10);

    if ($request->wantsJson()) {
        return BookResource::collection($books);
    }

    return inertia('Home', [
        'books' => BookResource::collection($books),
    ]);
});
