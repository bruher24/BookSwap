<?php

namespace App\Http\Requests;

use App\Models\BookType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateBookTypeRequest extends FormRequest
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
        $bookType = $this->route('book_type');
        $this->merge([
            'book_type_id' => $bookType instanceof BookType ? $bookType->id : $bookType,
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
                'required',
                'string',
                'max:50',
                Rule::unique('book_types', 'name')
                    ->ignore(request('book_type_id'))
                    ->withoutTrashed(),
            ],
        ];
    }
}
