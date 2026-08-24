<?php

namespace App\Http\Requests;

use App\Enums\BookConditionEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreBookRequest extends FormRequest
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
                'required',
                'string',
                'max:100',
            ],
            'authors_ids' => [
                'required',
                'array',
            ],
            'authors_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('authors', 'id')
                    ->withoutTrashed(),
            ],
            'page_count' => 'required|integer|min:1',
            'cover' => [
                'nullable',
                'image',
                'mimes:jpeg,png',
                'max:2048'
            ],
            'condition' => [
                'required',
                Rule::enum(BookConditionEnum::class),
            ],
            'publishing_house' => [
                'nullable',
                'string',
                'max:100',
            ],
            'publication_year' => [
                'nullable',
                'integer',
                'digits:4',
                'min:1000',
                'max:' . now()->year,
            ],
            'book_type_id' => [
                'required',
                'integer',
                Rule::exists('book_types', 'id')
                    ->withoutTrashed(),
            ],
            'isbn' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^(97[89])\d{10}$/',
                Rule::unique('books', 'isbn')
                    ->withoutTrashed(),
            ],
        ];
    }
}
