<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDealRequest extends FormRequest
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
            'seller_id' => [
                'required',
                'integer',
                'different:buyer_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
                Rule::unique('deals', 'seller_id')
                    ->where('buyer_id', request('buyer_id'))
                    ->withoutTrashed(),
            ],
            'buyer_id' => [
                'required',
                'integer',
                'different:seller_id',
                Rule::exists('users', 'id')
                    ->withoutTrashed(),
                Rule::unique('deals', 'buyer_id')
                    ->where('seller_id', request('seller_id'))
                    ->withoutTrashed(),
            ],
            'date' => [
                'required',
                'string',
                Rule::date()->beforeOrEqual(now()),
            ],
        ];
    }
}
