<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        return [
            'name' => 'required|string|max:50|unique:users, name' . ($name ? ', ' . $name : ''),
            'email' => 'required|email|unique:users, email' . ($email ? ', ' . $email : ''),
            'password' => 'required|string',
        ];
    }
}
