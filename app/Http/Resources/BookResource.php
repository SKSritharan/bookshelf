<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'language' => $this->language,
            'description' => $this->description,
            'author' => AuthorResource::make($this->author),
            'category' => CategoryResource::make($this->category),
            'cover' => config('app.url') . '/storage/' . $this->cover,
            'isbn' => $this->isbn,
            'isbn13' => $this->isbn13,
            'available_copies' => $this->available_copies,
            'total_copies' => $this->total_copies,
            'tags' => TagResource::collection($this->tags),
            'created_at' => $this->created_at,
        ];
    }
}
