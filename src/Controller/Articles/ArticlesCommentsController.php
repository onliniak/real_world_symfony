<?php

declare(strict_types=1);

namespace App\Controller\Articles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesCommentsController extends AbstractController
{
    #[Route('/api/articles/{slug}/comments', name: 'app_articles_comments_create', methods: ['POST'])]
    public function create(string $slug, string $body): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesCommentsController.php',
        ]);
    }

    #[Route('/api/articles/{slug}/comments', name: 'app_articles_comments_get', methods: ['GET'])]
    public function show(string $slug): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesCommentsController.php',
        ]);
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
