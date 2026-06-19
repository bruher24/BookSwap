<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class WhereBookRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'publishing_house' => [
                'nullable',
                'array',
                'min:1'
            ],
            'publishing_house.*' => [
                'required',
                'string',
                'max:255'
            ],
            'publication_year' => [
                'nullable',
                'array',
                'min:1'
            ],
            'publication_year.*' => [
                'required',
                'string',
                'date_format:Y'
            ],
            'page_count' => [
                'nullable',
                'array',
                'size:2'
            ],
            'page_count.*' => [
                'required',
                'integer',
                'min:1'
            ],

            'book_type_id' => ['nullable', 'array', 'min:1'],
            'book_type_id.*' => ['required', 'integer'],

            'author_id' => ['nullable', 'array', 'min:1'],
            'author_id.*' => ['required', 'integer'],

            'genre_id' => ['nullable', 'array', 'min:1'],
            'genre_id.*' => ['required', 'integer'],
        ];
    }
}
