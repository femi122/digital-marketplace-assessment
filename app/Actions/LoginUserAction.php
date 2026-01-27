<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    public function execute(array $data): array
    {
        //Find user by email
        $user = User::where('email', $data['email'])->first();

        // Check password
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            // We throw a validation exception so Laravel returns a standard 422 error
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // Generate new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}