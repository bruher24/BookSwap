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
            'author_id' => 'required_without_all:authorLastName,authorFirstname,authorPatronymic|integer|exists:authors,id',
            'author_id1' => 'nullable|integer|exists:authors,id',
            'author_id2' => 'nullable|integer|exists:authors,id',
            'authorLastname' => [
                'required_without:author_id',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $exists = Author::where('lastname', request('authorLastname'))
                        ->where('firstname', request('authorFirstname'))
                        ->where('patronymic', request('authorPatronymic'))
                        ->exists();
                    if ($exists) {
                        $fail('Автор с таким ФИО уже существует');
                    }
                }
            ],
            'authorFirstname' => 'required_without:author_id|string|max:100',
            'authorPatronymic' => 'required_without:author_id|string|max:100',
            'authorBirthdate' => [
                'required_without:author_id',
                'date',
                Rule::date()->beforeOrEqual(today()->subYears(16))
            ],
            'page_count' => 'required|integer|min:1',
            'photo' => 'nullable|string|unique:photos,src',
            'publishing_house' => 'string',
            'publication_year' => Rule::date()->format('Y'),
            'type_id' => 'integer|exists:book_types,id',
        ];
    }
}
