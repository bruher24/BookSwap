<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookTypeRequest extends FormRequest
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
        request()->merge(['book_type_id' => $this->route('book_type')]);
        return [
            'book_type_id' => [
                'required',
                'integer',
                Rule::exists('book_types', 'id')
                    ->withoutTrashed(),
            ],
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
