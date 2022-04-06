<?php
declare(strict_types=1);

namespace App\Controller\Users;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersLoginController extends AbstractController
{
    #[Route('/api/users/login', name: 'app_user_login', methods: ['POST'])]
    public function create(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersLoginController.php',
        ]);
    }
}
