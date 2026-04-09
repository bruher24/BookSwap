<?php

namespace App\Http\Requests;

use App\Models\Author;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateAuthorRequest extends FormRequest
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
        $this->merge([
            'author_id' => $this->route('author')->id,
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
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
            'lastname' => [
                'required',
                'string',
                'max:100',
                function ($attribute, $value, $fail) {
                    $exists = Author::where('lastname', request('lastname'))
                        ->where('firstname', request('firstname'))
                        ->where('patronymic', request('patronymic'))
                        ->whereNot('id', request('author_id'))
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
