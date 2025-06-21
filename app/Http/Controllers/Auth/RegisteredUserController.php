<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'max:255', 'min:8'],
            'confirmPassword' => ['required', 'same:password'],
        ]);
        try {
            User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json(null, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error creating user', 'message' => $th->getMessage()], 500);
        }
    }
}
