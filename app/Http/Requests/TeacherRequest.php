<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email',
            'password' => 'required|string|max:255',
            'phone' => 'required|max:15|unique:teachers,phone',
            'sex' => 'required',
            'address' => 'required|string|max:255',
            'dob' => 'required',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id',
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
            'phone.required' => 'phone is required.',
            'phone.unique' => 'Phone number already exists',
            'phone.max' => 'Phone number must not exceed 15 characters',
            'sex.required' => 'sex is required',
            'address.required' => 'address is required',
            'dob.required' => 'dob is required',
        ];
    }
}
