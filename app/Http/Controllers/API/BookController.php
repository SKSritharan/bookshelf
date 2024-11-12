<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return BookResource::collection(Book::latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            $author = Author::firstOrCreate(['name' => $request->author]);
            $category = Category::firstOrCreate(['name' => $request->category]);

            $book = Book::create([
                'title' => $request->title,
                'isbn' => $request->isbn,
                'isbn13' => $request->isbn13,
                'description' => $request->description,
                'cover' => $request->cover,
                'language' => $request->language,
                'available_copies' => $request->available_copies,
                'total_copies' => $request->total_copies,
                'author_id' => $author->id,
                'category_id' => $category->id,
            ]);

            return $this->responseWithSuccess('Book created successfully', new BookResource($book));
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $book = Book::findOrFail($id);
            return new BookResource($book);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $book = Book::findOrFail($id);
            $book->delete();
            return $this->responseWithSuccess('Book deleted successfully');
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Search for a book.
     */
    public function search(Request $request)
    {
        try {
            $books = Book::where('title', 'like', "%$request->search%")
                ->orWhere('isbn', 'like', "%$request->search%")
                ->orWhere('isbn13', 'like', "%$request->search%")
                ->orWhere('description', 'like', "%$request->search%")
                ->orWhere('language', 'like', "%$request->search%")
                ->orWhere('available_copies', 'like', "%$request->search%")
                ->orWhere('total_copies', 'like', "%$request->search%")
                ->orWhereHas('author', function ($query) use ($request) {
                    $query->where('name', 'like', "%$request->search%");
                })
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('name', 'like', "%$request->search%");
                })
                ->get();
            return BookResource::collection($books);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }
}
