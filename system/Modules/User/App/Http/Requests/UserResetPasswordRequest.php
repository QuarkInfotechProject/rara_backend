<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'confirmPassword' => 'required|same:password'
        ];
    }
    public function messages()
    {
        return [
            'password.required' => 'Don\'t forget to set a new password for your account.',
            'password.string' => 'Your new password should be a mix of letters, numbers, and symbols for better security.',
            'password.min' => 'Hold on! Your new password must be at least :min characters long.',
            'password.uncompromised' => 'For your safety, please choose a new password that hasn\'t been compromised.',
            'confirmPassword.required' => 'Confirm your new password to make sure it\'s just right.',
            'confirmPassword.same' => 'Hold on! The confirm password doesn\'t match your new password. Please check and try again.',
        ];
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
