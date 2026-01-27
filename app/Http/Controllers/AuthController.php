<?php

namespace App\Http\Controllers;

use App\Actions\RegisterUserAction;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use App\Actions\LoginUserAction;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Handle incoming registration request.
     * 
     * Dependency Injection: Laravel automatically injects the Action class.
     */
    public function register(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        // 1. The $request is already validated here. 
        // If validation failed, we send a 422 error automatically.
        
        // 2. Call the Action to do the work
        $result = $action->execute($request->validated());

        // 3. Return a clean JSON response
        return response()->json([
            'message' => 'Registration successful',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ], 201); //returns 201 upon succesful creation
    }

    public function login(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        $result = $action->execute($request->validated());

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
            ],
        ]);
    }
}