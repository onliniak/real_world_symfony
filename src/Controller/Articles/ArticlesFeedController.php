<?php
declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesFeedController extends AbstractController
{
    #[Route('/api/articles/feed', name: 'app_articles_feed', methods: ['GET'])]
    public function index(Request $request, ArticlesRepository $article): Response
    {
        $tag = $request->query->get('tag');
        $author = $request->query->get('author');
        $favorited = $request->query->get('favorited') ?? '';
        $limit = $request->query->get('limit') ?? 20;
        $offset = $request->query->get('offset') ?? 0;

        return new Response($article->listArticles($limit, $offset, $tag, $author, $favorited, true));
    }
}
