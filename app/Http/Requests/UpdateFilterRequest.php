<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

final class UpdateFilterRequest extends FormRequest
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
        $this->merge(['filter_id' => $this->route('filter')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'filter_id' => [
                'required',
                'integer',
                Rule::exists('filters', 'id')
                    ->withoutTrashed(),
            ],
            'name' => [
                'required',
                'string',
                'min:3',
                'max:30',
                Rule::unique('filters', 'name')
                    ->withoutTrashed()
                    ->ignore(request('filter_id')),
            ],
            'by_fields' => [
                'required',
                'string',
                Rule::unique('filters', 'by_fields')
                    ->withoutTrashed()
                    ->ignore(request('filter_id')),
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
