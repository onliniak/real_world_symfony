<?php

declare(strict_types=1);

namespace App\Controller\Users;

use App\Repository\UserRepository;
use App\Service\APIResponses;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UsersController extends AbstractController
{
    #[Route('/api/users', name: 'app_user_create', methods: ['POST'])]
    public function create(
        JWTTokenManagerInterface $JWTToken,
        Request                  $request,
        UserRepository           $userRepository
    ): JsonResponse {
        $json = json_decode($request->getContent())->user;

        $new_user = $userRepository->serialize(
                $json->email,
                $json->username,
                $json->password
            );
        $userRepository->add($new_user);

        return $this->json([
            'user' => [
                'email' => $json->email,
                'token' => $JWTToken->create($new_user),
                'username' => $json->username,
                'bio' => '',
                'image' => '',
            ],
    ]);
    }

    #[Route('/api/user', name: 'app_user_show', methods: ['GET'])]
    public function show(
        UserRepository           $userRepository,
        APIResponses             $userService,
        JWTTokenManagerInterface $JWTToken,
        Security                 $security
    ): Response {
        return $this->json($userService->userResponse(
            $userRepository->getUserByUsername($security->getUser()->getUserIdentifier()),
            $JWTToken
        ));
    }

    #[Route('/api/user', name: 'app_user_update', methods: ['PUT'])]
    public function update(
        UserRepository           $userRepository,
        APIResponses             $userService,
        Request                  $request,
        JWTTokenManagerInterface $JWTToken,
        Security                 $security
    ): Response {
        return $this->json(
            $userService->userResponse(
                $userRepository->updateUser($security, json_decode($request->getContent())->user),
                $JWTToken
            )
        );
    }
}
