<?php

namespace App\Http\Requests;

use App\Models\Filter;
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

    /**
     * Prepare the data for validation.
     */
    #[Override]
    protected function prepareForValidation(): void
    {
        $filter = $this->route('filter');
        $this->merge([
            'filter_id' => $filter instanceof Filter ? $filter->id : $filter,
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
