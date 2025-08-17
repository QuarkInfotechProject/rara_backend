<?php

namespace Modules\User\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone_no' => 'nullable|regex:/^\+?[0-9\s]{8,15}$/',
            'offers_notification' => 'boolean',
            'country' => 'string',
            'full_name' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'phone_no.integer' => 'Please enter a valid phone number without any spaces or special characters.',
            'phone_no.regex' => 'Your phone number should be between 8 to 15 digits long.',
            'offers_notification.boolean' => 'Please select a valid option for offers notification.',
            'country.string' => 'Please enter a valid country name.',
            'full_name.string' => 'Please enter a valid full name.',
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
