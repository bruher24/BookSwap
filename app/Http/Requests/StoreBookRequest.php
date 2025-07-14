<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:100|unique:books,name',
            'author_id' => 'required_without_all:authorLastName,authorFirstname|nullable|integer|exists:authors,id',
            'author_id1' => 'nullable|integer|exists:authors,id',
            'author_id2' => 'nullable|integer|exists:authors,id',
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
                Rule::date()->beforeOrEqual(today()->subYears(16)),
            ],
            'page_count' => 'required|integer|min:1',
            'photo' => 'nullable|string|unique:photos,src',
            'publishing_house' => 'nullable|string',
            'publication_year' => [
                'nullable',
                Rule::date()->format('Y'),
            ],
            'type_id' => 'required|integer|exists:book_types,id',
            'isbn' => 'nullable|string|size:13|unique:books,isbn',
        ];
    }
}
