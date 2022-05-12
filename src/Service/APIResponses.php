<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\ArticlesRepository;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Security;

class APIResponses
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

    public function profileResponse(User $user, UserRepository $userRepository, Security $security): array
    {
        return [
            'profile' => [
                'username' => $user->getUsername(),
                'bio' => $user->getBio(),
                'image' => $user->getImage(),
                'following' => in_array(
                    $user->getUsername(),
                    $userRepository->getUserByUsername($security->getUser()
                        ->getUserIdentifier())[0]->getFollowedUsers()
                ),
            ],
        ];
    }

    public function articleResponse(
        string $slug,
        UserRepository $userRepository,
        ArticlesRepository $articlesRepository,
        Security $security
    ): array {
        return [
            'article' => $articlesRepository->getSingleArticle($slug),
            'author' => $this->profileResponse(
                $articlesRepository->getSingleArticle($slug)->authorID,
                $userRepository,
                $security
            ),
        ];
    }
}
