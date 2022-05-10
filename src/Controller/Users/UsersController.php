<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    #[Route('/api/users', name: 'app_user_create', methods: ['POST'])]
    public function create(UserRepository $userRepository,
                           Request $request, JWTTokenManagerInterface $JWTManager): JsonResponse
    {
        $json = json_decode($request->getContent())->user;
        $user = $userRepository->register(
            $json->email,
            $json->username,
            $json->password
        );
        // $token = new UsernamePasswordToken($user, 'login', $user->getRoles());
        // $tokenStorage->setToken($token);
        // $requestStack->getSession()->set('_security_main', serialize($token));

        return new JsonResponse([
            'user' => [
                'email' => $user->getEmail(),
                'token' => $JWTManager->create($user),
                'username' => $user->getUsername(),
                'bio' => $user->getBio(),
                'image' => $user->getImage(), ],
             ]);
    }

    #[Route('/api/user', name: 'app_user_show', methods: ['GET'])]
    public function show(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php',
        ]);
    }

    #[Route('/api/user', name: 'app_user_update', methods: ['PUT'])]
    public function update(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UsersController.php',
        ]);
    }
}
