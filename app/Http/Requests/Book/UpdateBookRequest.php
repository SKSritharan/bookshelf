<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:255'],
            'isbn13' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'cover' => ['sometimes', 'image'],
            'language' => ['required', 'string'],
            'available_copies' => ['required', 'integer'],
            'total_copies' => ['required', 'integer'],
            'author' => ['required', 'string', 'exists:authors,name'],
            'category' => ['required', 'string', 'exists:categories,name'],
        ];
    }
}
