<?php
declare(strict_types=1);

namespace App\Controller\Profiles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilesFollowController extends AbstractController
{
    #[Route('/api/profiles/{username}/follow', name: 'app_profile_follow', methods: ['POST'])]
    public function create(string $username): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProfilesFollowController.php',
        ]);
    }

    #[Route('/api/profiles/{username}/follow', name: 'app_profile_unfollow', methods: ['DELETE'])]
    public function delete(string $username): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProfilesFollowController.php',
        ]);
    }
}
