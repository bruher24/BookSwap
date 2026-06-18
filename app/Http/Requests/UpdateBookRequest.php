<?php

namespace App\Http\Requests;

use App\Enums\BookCondition;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    #[Override]
    protected function prepareForValidation(): void
    {
        $book = $this->route('book');
        $this->merge([
            'book_id' => $book instanceof Book ? $book->id : $book,
        ]);
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
                'max:100',
            ],
            'author_id' => [
                'required_without_all:authorLastname,authorFirstname',
                'nullable',
                'integer',
                Rule::exists('authors', 'id')
                    ->withoutTrashed(),
            ],
            'author_id1' => [
                'nullable',
                'integer',
                Rule::exists('authors', 'id')
                    ->withoutTrashed(),
            ],
            'author_id2' => [
                'nullable',
                'integer',
                Rule::exists('authors', 'id')
                    ->withoutTrashed(),
            ],
            'authorLastname' => [
                'required_without:author_id',
                'nullable',
                'string',
                'max:100',
                Rule::unique('authors', 'lastname')
                    ->where('firstname', $this->input('firstname'))
                    ->where('patronymic', $this->input('patronymic')),
            ],
            'authorFirstname' => 'required_without:author_id|nullable|string|max:100',
            'authorPatronymic' => 'nullable|string|max:100',
            'authorBirthdate' => [
                'nullable',
                'date',
                Rule::date()->beforeOrEqual(today()->subYears(14)),
            ],
            'page_count' => 'required|integer|min:1',
            'condition' => [
                'string',
                Rule::in(BookCondition::cases()),
            ],
            'cover' => [
                'nullable',
                'file',
                'mimes:jpeg,png',
                'max:2048'
            ],
            'publishing_house' => 'nullable|string',
            'publication_year' => [
                'nullable',
                Rule::date()->format('Y'),
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
                Rule::unique('books', 'isbn')
                    ->ignore(request('book_id'))
                    ->withoutTrashed(),
            ],
        ];
    }
}
