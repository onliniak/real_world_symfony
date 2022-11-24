<?php

declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\ArticlesRepository;
use App\Repository\FavoritedRepository;
use App\Repository\TagsRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

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
        $favorited = $request->query->get('favorited') ?? '';
        $limit = $request->query->get('limit') ?? 20;
        $offset = $request->query->get('offset') ?? 0;

        return new Response($this->article->listArticles($limit, $offset, $tag, $author, $favorited));
    }

    #[Route('/api/articles/{slug}', name: 'app_article_get', methods: ['GET'])]
    public function show(string $slug, TagsRepository $tagsRepository, 
    FavoritedRepository $favoritedRepository): Response
    {
        if (empty($this->article->getSingleArticle($slug))) {
            return $this->json([
                'errors' => [
                    'body' => [
                        'Article not found',
                    ],
                ],
            ], 404);
        }           
        return $this->json([
            // GROUP_CONCAT in pure PHP
            'article' =>
            array_merge(
                $this->article->getSingleArticle($slug),
                $tagsRepository->getTagsFromSingleArticle($slug),
            $favoritedRepository->getFavoritesFromSingleArticle($slug)
                )
        ]);
    }

    #[Route('/api/articles', name: 'app_article_create', methods: ['POST'])]
    public function create(#[CurrentUser] ?UserInterface $user, Request $request,
     ArticlesRepository $article, TagsRepository $tagsRepository): Response
    {
        $json = json_decode($request->getContent(), true)['article'];
        $slug = strtolower(preg_replace('/ /', '-', $json['title']));

        try {
            $article->createArticle(
                $json['title'],
                $json['description'],
                $json['body'],
                $user->getUserIdentifier()
            );
            if (!empty($json['tagList'])) {
                foreach ($json['tagList'] as $tag) {
                    $tagsRepository->addTag($tag, $slug);
                }
            }

            return $this->json(
                $article->getSingleArticle($slug)
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
    public function delete(string $slug, TagsRepository $tags): Response
    {
        $this->article->deleteArticle($slug, $tags);

        return $this->json([]);
    }
}
