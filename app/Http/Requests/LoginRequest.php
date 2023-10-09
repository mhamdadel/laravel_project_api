<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
        if(!is_null($this->email)){
            return [
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
            ];
        } else {
            return [
                'phone_number' => 'required|string|email|min:11|max:11|unique:users',
                'password' => 'required|string',
            ];
        }
    }
}
