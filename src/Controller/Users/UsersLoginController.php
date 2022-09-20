<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Repository\UserRepository;
use App\Service\APIResponses;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersLoginController extends AbstractController
{
    #[Route('/api/users/login', name: 'app_user_login', methods: ['POST'])]
    public function create(
        APIResponses             $user,
        UserRepository           $userRepository,
        JWTTokenManagerInterface $JWTToken,
        Request                  $request,
    ): Response {
        $json = json_decode($request->getContent())->user;

        return $this->json($user->userResponse(
            $userRepository->getUserByLoginPassword($json->email, $json->password),
            $JWTToken
        ));
    }
}
