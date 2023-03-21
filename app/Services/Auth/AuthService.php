<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Repositories\Auth\AuthRepository;

class AuthService
{
    private AuthRepository $authRepository;

    public function __construct(AuthRepository $repository, User $user)
    {
        $this->authRepository = $repository;
        $this->authRepository->setModel($user);
    }

    public function register(array $params): array
    {
        $this->authRepository->register([
            ...$params,
            'password' => bcrypt($params['password']),
        ]);
                        
        return [
            'message' => 'Registered successfully',
        ];
    }

    public function login(array $params): ? string
    {
        return $this->authRepository->login($params);
    }

}
