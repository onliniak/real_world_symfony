<?php
declare(strict_types=1);

namespace App\Controller\Articles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{
    #[Route('/api/articles', name: 'app_articles', methods: ['GET'])]
    public function index(?string $tag, ?string $author, ?string $favorited, int $limit = 20, int $offset = 0): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesController.php',
        ]);
    }

    #[Route('/api/articles/{slug}', name: 'app_article_get', methods: ['GET'])]
    public function show(string $slug): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesController.php',
        ]);
    }

    #[Route('/api/articles', name: 'app_article_create', methods: ['POST'])]
    public function create(string $title, string $description, string $body, ?array $tagList): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesController.php',
        ]);
    }

    #[Route('/api/articles/{slug}', name: 'app_article_update', methods: ['PUT'])]
    public function update(string $slug, string $title, string $description, string $body): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesController.php',
        ]);
    }

    #[Route('/api/articles/{slug}', name: 'app_article_delete', methods: ['DELETE'])]
    public function delete(string $slug): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesController.php',
        ]);
    }
}
