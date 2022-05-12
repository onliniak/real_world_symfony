<?php

declare(strict_types=1);

namespace App\Controller\Profiles;

use App\Repository\UserRepository;
use App\Service\APIResponses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfilesFollowController extends AbstractController
{
    #[Route('/api/profiles/{username}/follow', name: 'app_profile_follow', methods: ['POST'])]
    public function create(string $username, Security $security, UserRepository $userRepository, APIResponses $response): Response
    {
        $userRepository->add($userRepository->getUserByUsername($security->getUser()
            ->getUserIdentifier())[0]->setFollowedUsers([$username]));

        return $this->json($response->profileResponse($userRepository->getUserByUsername($username)[0], $userRepository, $security));
    }

    #[Route('/api/profiles/{username}/follow', name: 'app_profile_unfollow', methods: ['DELETE'])]
    public function delete(string $username, Security $security, UserRepository $userRepository, APIResponses $response): Response
    {
        $userRepository->add($userRepository->getUserByUsername($security->getUser()
            ->getUserIdentifier())[0]->deleteFollowedUsers([$username]));

        return $this->json($response->profileResponse($userRepository->getUserByUsername($username)[0], $userRepository, $security));
    }
}
