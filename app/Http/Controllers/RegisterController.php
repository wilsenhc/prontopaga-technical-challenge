<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\RoleEnum;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(RegisterRequest $request)
    {
        // Handle the registration logic here
        // For example, create a new user using the validated data
        $data = $request->validated();

        // Assuming you have a User model and it is set up correctly
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => RoleEnum::PATIENT,
        ]);

        // Optionally, you can return a response or redirect
        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}
