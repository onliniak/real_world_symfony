<?php

namespace App\Service;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserService
{
    public function userResponse(User $user, JWTTokenManagerInterface $JWTManager): array
    {
        return [
                'user' => [
                    'email' => $user->getEmail(),
                    'token' => $JWTManager->create($user),
                    'username' => $user->getUsername(),
                    'bio' => $user->getBio(),
                    'image' => $user->getImage(),
                ],
        ];
    }
}
