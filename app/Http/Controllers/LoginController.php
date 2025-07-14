<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        // Handle login logic here
        $validated = $request->validated();

        // Authenticate the user using the validated data
        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            // Generate a new token for the user
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $token = $user->createToken('api_token')->plainTextToken;

            // Return the token in the response
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ], 200);
        }

        // Authentication failed, return an error response
        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
