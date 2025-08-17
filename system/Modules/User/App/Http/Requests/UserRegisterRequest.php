<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'fullName' => 'required|string|min:2|max:50',
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Please enter an email.',
            'email.email' => 'The entered email address is not a valid email.',
            'email.unique' => 'The :attribute is already registered. Please login in.',
            'fullName.required' => 'Please enter your full name.',
            'password.required' => 'Please enter a password.',
        ];
    }
}
