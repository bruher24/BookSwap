<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFilterRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                Rule::unique('filters', 'name')
                    ->withoutTrashed(),
            ],
            'by_fields' => [
                'required',
                'string',
                Rule::unique('filters', 'by_fields')
                    ->withoutTrashed(),
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
