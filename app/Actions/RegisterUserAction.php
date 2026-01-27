<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    /**
     * Handle the registration logic.
     * 
     * @param array $data Validated data from the request
     * @return array Contains the User model and their plain-text API token
     */
    public function execute(array $data): array
    {
        // 1. Create the user in the database
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']), // Always hash passwords!
            'role' => $data['role'],
        ]);

        // 2. Generate a Sanctum API Token
        // This acts like a digital ID card for future requests.
        $token = $user->createToken('auth_token')->plainTextToken;

        // 3. Return both
        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}