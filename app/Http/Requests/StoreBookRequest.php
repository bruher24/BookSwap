<?php

namespace App\Http\Requests;

use App\Models\Author;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100|unique:books,name',
            'author_id' => 'nullable|integer|exists:authors,id',
            'authorLastname' => [
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
            'authorFirstname' => 'string|max:100',
            'authorPatronymic' => 'nullable|string|max:100',
            'authorBirthdate' => ['date', Rule::date()->beforeOrEqual(today()->subYears(16))],
            'pageCount' => 'required|integer|min:1',
            'photo' => 'nullable|string|unique:photos,src',
        ];
    }
}
