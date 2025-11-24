<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateCoverRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    protected function prepareForValidation(): void
    {
        $this->merge(['cover_id' => $this->route('cover')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cover_id' => [
                'required',
                'integer',
                Rule::exists('covers', 'id')
                    ->withoutTrashed(),
            ],
            'src' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png',
            ],
        ];
    }
}
