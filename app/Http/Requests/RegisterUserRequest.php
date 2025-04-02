<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'max:255', 'min:8'],
            'confirmPassword' => ['required', 'same:password'],
        ];
    }
    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'Email already exists.',
            'email.email' => 'Invalid Email Address',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters long.',
            'confirmPassword.required' => 'Confirm Password is required.',
            'confirmPassword.same' => 'Confirm Password does not match with Password.',
        ];
    }
}
