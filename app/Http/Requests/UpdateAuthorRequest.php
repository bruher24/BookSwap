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
        $author = $this->route('author');
        $this->merge([
            'author_id' => $author instanceof Author ? $author->id : $author,
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
                Rule::unique('authors', 'lastname')
                    ->where('firstname', $this->input('firstname'))
                    ->where('patronymic', $this->input('patronymic'))
                    ->ignore($this->input('author_id')),
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
