<?php

declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\ArticlesRepository;
use App\Repository\TagsRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function show(string $slug): Response
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
        return new Response($this->article->getSingleArticle($slug));
    }

    #[Route('/api/articles', name: 'app_article_create', methods: ['POST'])]
    public function create(#[CurrentUser] ?UserInterface $user, Request $request,
     TagsRepository $tagsRepository): Response
    {
        $json = json_decode($request->getContent(), true)['article'];
        $slug = strtolower(preg_replace('/ /', '-', $json['title']));

        try {
            $this->article->createArticle(
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

            return new Response($this->article->getSingleArticle($slug));
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
    public function update(Request $request, string $slug): Response
    {
        $json = json_decode($request->getContent(), true)['article'];
        $title = $json['title'] ?? '';
        $description = $json['description'] ?? '';
        $body = $json['body'] ?? '';
        $this->article->updateArticle($slug, $title, $description, $body);

        return new Response($this->article->getSingleArticle($slug));
    }

    #[Route('/api/articles/{slug}', name: 'app_article_delete', methods: ['DELETE'])]
    public function delete(string $slug, TagsRepository $tags): Response
    {
        $this->article->deleteArticle($slug, $tags);

        return new Response($this->article->getSingleArticle($slug));
    }
}
