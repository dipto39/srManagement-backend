<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;

class AuthController extends Controller
{
    use ApiResponse;
    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $loginData = $this->authService->login($credentials);

        if (!$loginData) {
            return $this->error('Invalid email or password', 401);
        }

        return $this->success($loginData, 'Login successful');
    }

    public function register(RegistrationRequest $request)
    {
        $data = $request->validated();

        $user = $this->authService->register($data);

        return $this->success($user, 'Registration successful', 201);
    }
}
