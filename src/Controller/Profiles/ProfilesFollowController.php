<?php

declare(strict_types=1);

namespace App\Controller\Profiles;

use App\Entity\User;
use App\Repository\FollowersRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfilesFollowController extends AbstractController
{
    #[Route('/api/profiles/{username}/follow', name: 'app_profile_follow', methods: ['POST'])]
    public function create(string $username, UserRepository $userRepository, FollowersRepository $followers, 
    Request $request, #[CurrentUser] ?UserInterface $userInterface): Response
    {
        $json = json_decode($request->getContent(), true)['user'];
        $followers->follow($userInterface?->getUserIdentifier(), $username);

        return $this->json(["profile" => $userRepository->getProfile($username, $userInterface?->getUserIdentifier())]);
    }

    #[Route('/api/profiles/{username}/follow', name: 'app_profile_unfollow', methods: ['DELETE'])]
    public function delete(string $username, UserRepository $userRepository, 
    FollowersRepository $followers, #[CurrentUser] ?UserInterface $userInterface): Response
    {
        $followers->unfollow($username);

        return $this->json(["profile" => $userRepository->getProfile($username, $userInterface?->getUserIdentifier())]);
    }
}
