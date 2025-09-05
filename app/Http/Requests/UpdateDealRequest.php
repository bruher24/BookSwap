<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDealRequest extends FormRequest
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
            'deal_id' => [
                'required',
                'integer',
                Rule::exists('deals', 'id')
                    ->whereNull('deleted_at'),
            ],
            'seller_id' => [
                'required',
                'integer',
                'different:buyer_id',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at'),
                Rule::unique('deals', 'seller_id')
                    ->where('buyer_id', request('buyer_id'))
                    ->whereNull('deleted_at')
                    ->ignore(request('deal_id')),
            ],
            'buyer_id' => [
                'required',
                'integer',
                'different:seller_id',
                Rule::exists('users', 'id')
                    ->whereNull('deleted_at'),
                Rule::unique('deals', 'buyer_id')
                    ->where('seller_id', request('seller_id'))
                    ->whereNull('deleted_at')
                    ->ignore(request('deal_id')),
            ],
            'date' => [
                'required',
                'string',
                Rule::date()->beforeOrEqual(now()),
            ],
        ];
    }
}
