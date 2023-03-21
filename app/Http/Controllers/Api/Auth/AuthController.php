<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponseSender;

class AuthController extends Controller
{
    use ApiResponseSender;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->successResponse($this->authService->register($request->validated()));
    }

    public function login(LoginRequest $request)
    {
        return $this->successResponse([
            'message' => 'login successfully',
            'token' => $this->authService->login($request->validated()),
        ]);
    }
}
