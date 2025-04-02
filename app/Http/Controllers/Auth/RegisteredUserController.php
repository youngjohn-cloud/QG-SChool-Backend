<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{

    public function store(RegisterUserRequest $request)
    {
        try {
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error creating user', 'message' => $th->getMessage()], 500);
        }
    }
}
