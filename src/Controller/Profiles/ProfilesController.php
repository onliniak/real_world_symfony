<?php
declare(strict_types=1);

namespace App\Controller\Profiles;

use App\Repository\UserRepository;
use App\Service\APIResponses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProfilesController extends AbstractController
{
    #[Route('/api/profiles/{username}', name: 'app_profile_get', methods: ['GET'])]
    public function show(
        UserRepository           $userRepository,
        APIResponses             $userService,
        Security                 $security,
        string                   $username
    ): Response {
        return $this->json($userService->profileResponse(
            $userRepository->getUserByUsername($username)[0],
            $userRepository,
            $security
        ));
    }
}
