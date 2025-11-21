<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class UpdatePhotoRequest extends FormRequest
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
        $this->merge(['photo_id' => $this->route('photo')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'photo_id' => [
                'required',
                'integer',
                Rule::exists('photos', 'id')
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
