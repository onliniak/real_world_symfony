<?php
declare(strict_types=1);

namespace App\Controller\Profiles;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilesController extends AbstractController
{
    #[Route('/api/profiles/{username}', name: 'app_profile_get', methods: ['GET'])]
    public function show(
        UserRepository           $userRepository,
        string                   $username
    ): Response {
        return $this->json(["profile" => $userRepository->getProfile($username)]);
    }
}
