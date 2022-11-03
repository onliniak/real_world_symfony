<?php

declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ArticlesCommentsController extends AbstractController
{
    #[Route('/api/articles/{slug}/comments', name: 'app_articles_comments_create', methods: ['POST'])]
    public function create(string $slug, #[CurrentUser] ?UserInterface $userInterface, Request $request, CommentsRepository $comments): JsonResponse
    {
        $json = json_decode($request->getContent(), true)['comment'];


        $comments->CreateComment($slug, $userInterface->getUserIdentifier(), $json['body']);
        return $this->json($comments->ShowComments($slug));
    }

    #[Route('/api/articles/{slug}/comments', name: 'app_articles_comments_get', methods: ['GET'])]
    public function show(string $slug, CommentsRepository $comments): JsonResponse
    {
        return $this->json($comments->ShowComments($slug));
    }

    #[Route('/api/articles/{slug}/comments/{id}', name: 'app_articles_comments_delete', methods: ['DELETE'])]
    public function delete(string $slug, int $id): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesCommentsController.php',
        ]);
    }
}
