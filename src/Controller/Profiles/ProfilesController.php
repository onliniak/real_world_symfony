<?php
declare(strict_types=1);

namespace App\Controller\Profiles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilesController extends AbstractController
{
    #[Route('/api/profiles/{username}', name: 'app_profile_get', methods: ['POST'])]
    public function show(string $username): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProfilesController.php',
        ]);
    }
}
