<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $credentials = $request->authenticate();
        $type = $credentials['type'];
        $user = $credentials['user'];
        return response()->json([
            'message' => 'Login successfully',
            'type' => $type,
            'user' =>  $user,
        ], 200);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => "Logout sucessful"], 201);
    }
}
