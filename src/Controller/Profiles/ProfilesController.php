<?php
declare(strict_types=1);

namespace App\Controller\Profiles;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ProfilesController extends AbstractController
{
    #[Route('/api/profiles/{username}', name: 'app_profile_get', methods: ['GET'])]
    public function show(
        UserRepository           $userRepository,
        string                   $username,
        #[CurrentUser] ?User $userInterface
    ): Response {
        return $this->json(["profile" => $userRepository->getProfile($username, $userInterface?->getEmail())]);
    }
}
