<?php
declare(strict_types=1);

namespace App\Controller\Articles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesFavoriteController extends AbstractController
{
    #[Route('/api/articles/{slug}/favorite', name: 'app_articles_favorite', methods: ['POST'])]
    public function create(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesFavoriteController.php',
        ]);
    }
    #[Route('/api/articles/{slug}/favorite', name: 'app_articles_unfavorite', methods: ['DELETE'])]
    public function delete(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesFavoriteController.php',
        ]);
    }
}
