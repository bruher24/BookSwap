<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        request()->merge(['book_id' => $this->route('book')]);
        return [
            'book_id' => [
                'required',
                'integer',
                Rule::exists('books', 'id')
                    ->withoutTrashed(),
            ],
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
            ],
            'name' => [
                'nullable',
                'string',
                'max:100',
            ],
            'author_id' => [
                'required_without_all:authorLastName,authorFirstname',
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
                function ($attribute, $value, $fail) {
                    $exists = Author::where('lastname', request('authorLastname'))
                        ->where('firstname', request('authorFirstname'))
                        ->where('patronymic', request('authorPatronymic'))
                        ->exists();
                    if ($exists) {
                        $fail('Автор с таким ФИО уже существует!');
                    }
                }
            ],
            'authorFirstname' => 'required_without:author_id|nullable|string|max:100',
            'authorPatronymic' => 'nullable|string|max:100',
            'authorBirthdate' => [
                'nullable',
                'date',
                Rule::date()
                    ->beforeOrEqual(today()->subYears(16)),
            ],
            'page_count' => 'required|integer|min:1',
            'cover' => [
                'nullable',
                'file',
                'mimes:jpeg,png,pd',
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
