<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Security;

// ToDo: Remove unnecessary dependencies such us entities.
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

    public function articleResponse(array $array, string $currentUser): array
    {
        $article = array_slice($array, 0, 8);
        $following = in_array($currentUser, $array);
        $user = array_slice($array, -3);
        $user['following'] = $following;
        $article['tagList'] = $array['tagList'];
        unset($article['tags']);
        $article['createdAt'] = $article['createdAt']->format(DateTimeInterface::ATOM)."Z";
        $article['author'] = $user;

        return [
            'article' => $article,
        ];
    }
}
