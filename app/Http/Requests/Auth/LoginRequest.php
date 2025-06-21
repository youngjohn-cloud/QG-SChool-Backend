<?php

namespace App\Http\Requests\Auth;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }
    public function authenticate(): array
    {
        $credentials = $this->only('email', 'password');
        $models = [
            'student' => [
                'model' => Student::class,
                'guard' => 'student' // Match this to your config/auth.php guard name
            ],
            'admin' => [
                'model' => Admin::class,
                'guard' => 'admin'
            ],
            'teacher' => [
                'model' => Teacher::class,
                'guard' => 'teacher'
            ],
            'parent' => [
                'model' => User::class, // Use User model for parents
                'guard' => 'parent'
            ],
        ];

        foreach ($models as $type => $config) {
            $model = $config['model'];
            $user = $model::where('email', $credentials['email'])->first();
            if ($user && Hash::check($credentials['password'], $user->password)) {
                // Return the user and type
                return [
                    'type' => $type,
                    'user' => $user,
                ];
            }
        }

        throw ValidationException::withMessages([
            'email' => ['Invalid credentials'],
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' =>
            [
                "Too many login attempts. Try again in {$seconds} seconds."
            ],
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')) . '|' . $this->ip());
    }
}
