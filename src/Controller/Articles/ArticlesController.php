<?php

declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\ArticlesRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArticlesController extends AbstractController
{
    private ArticlesRepository $article;

    public function __construct(?ArticlesRepository $article)
    {
        $this->article = $article;
    }

    #[Route('/api/articles', name: 'app_articles', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $tag = $request->query->get('tag');
        $author = $request->query->get('author');
        $favorited = $request->query->get('favorited');
        $limit = $request->query->get('tag') ?? 20;
        $offset = $request->query->get('tag') ?? 0;

        return $this->json($this->article->listArticles($limit, $offset, $tag, $author, $favorited));
    }

    #[Route('/api/articles/{slug}', name: 'app_article_get', methods: ['GET'])]
    public function show(string $slug): Response
    {
        if (is_null($this->article->getSingleArticle($slug))) {
            return $this->json([
                'errors' => [
                    'body' => [
                        'Article not found',
                    ],
                ],
            ], 422);
        }

        return $this->json(
            $this->article->getSingleArticle($slug)
        );
    }

    #[Route('/api/articles', name: 'app_article_create', methods: ['POST'])]
    public function create(Request $request, ArticlesRepository $article, Security $security): Response
    {
        $json = json_decode($request->getContent(), true)['article'];
        $authorEmail = $security->getUser()?->getUserIdentifier();

        try {
            return $this->json(
                $article->createArticle(
                    $json['title'],
                    $json['description'],
                    $json['body'],
                    $authorEmail,
                    $json['tagList']
                ),
            );
        } catch (UniqueConstraintViolationException) {
            return $this->json([
                'errors' => [
                    'body' => [
                        'Article already exists',
                    ],
                ],
            ], 422);
        }
    }

    #[Route('/api/articles/{slug}', name: 'app_article_update', methods: ['PUT'])]
    public function update(Request $request, ArticlesRepository $article): Response
    {
        $title = $request->request->get('title');
        $slug = strtolower(preg_replace('/ /', '-', $title));
        $description = $request->request->get('description');
        $body = $request->request->get('body');
        $article->updateArticle($title, $description, $body);

        return $this->json($article->getSingleArticle($slug));
    }

    #[Route('/api/articles/{slug}', name: 'app_article_delete', methods: ['DELETE'])]
    public function delete(string $slug): Response
    {
        $this->article->deleteArticle($slug);

        return $this->json([]);
    }
}
