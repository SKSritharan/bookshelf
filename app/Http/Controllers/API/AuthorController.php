<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return AuthorResource::collection(Author::latest()->paginate($request->perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request)
    {
        try {
            $author = Author::create($request->validated());
            return $this->responseWithSuccess('Author created successfully', new AuthorResource($author));
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
            $author = Author::findOrFail($id);
            return new AuthorResource($author);
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, string $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->update($request->validated());
            return $this->responseWithSuccess('Author updated successfully', new AuthorResource($author));
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();
            return $this->responseWithSuccess('Author deleted successfully');
        } catch (\Exception $e) {
            return $this->responseWithError($e->getMessage());
        }
    }

    /**
     * Search for authors.
     */
    public function search(Request $request)
    {
        $authors = Author::where('name', 'like', "%{$request->name}%")->get();
        return AuthorResource::collection($authors);
    }
}
