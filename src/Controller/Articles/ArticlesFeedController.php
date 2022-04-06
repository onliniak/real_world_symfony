<?php
declare(strict_types=1);

namespace App\Controller\Articles;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesFeedController extends AbstractController
{
    #[Route('/api/articles/feed', name: 'app_articles_feed', methods: ['GET'])]
    public function index(?int $limit, ?int $offset): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesFeedController.php',
        ]);
    }
}
