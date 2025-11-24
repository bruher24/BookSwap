<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreAuthorRequest extends FormRequest
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
            'lastname' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $exists = Author::where('lastname', request('lastname'))
                        ->where('firstname', request('firstname'))
                        ->where('patronymic', request('patronymic'))
                        ->exists();
                    if ($exists) {
                        $fail('Автор с таким ФИО уже существует!');
                    }
                }
            ],
            'firstname' => [
                'required',
                'string',
                'max:100',
            ],
            'patronymic' => [
                'nullable',
                'string',
                'max:100',
            ],
            'birthdate' => [
                'nullable',
                Rule::date()->beforeOrEqual(today()->subYears(14)),
            ],
        ];
    }
}
