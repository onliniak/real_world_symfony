<?php

declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\ArticlesRepository;
use App\Repository\Exceptions\IsNullException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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

    /**
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\DBAL\Exception\UniqueConstraintViolationException
     */
    #[Route('/api/articles', name: 'app_article_create', methods: ['POST'])]
    public function create(Request $request, ArticlesRepository $article, UserInterface $user): Response
    {
        $title = $request->request->get('title');
        $slug = strtolower(preg_replace('/ /', '-', $title));
        $description = $request->request->get('description');
        $body = $request->request->get('body');
        $authorEmail = $user->getUserIdentifier();
        $tagList = $request->request->all('tagList');

        try {
            $article->createArticle($title, $description, $body, $authorEmail, $tagList);
            $newArticle = $article->getSingleArticle($slug);
        } catch (UniqueConstraintViolationException|OptimisticLockException|NonUniqueResultException|ORMException $e) {
            return $this->json([
                'errors' => [
                    'body' => [
                        $e,
                    ],
                ],
            ], 422);
        }

        return $this->json([
            'article' => [
                'title' => $newArticle['title'],
                'description' => $newArticle['description'],
                'body' => $newArticle['body'],
                'tagList' => $newArticle['tagList'],
            ],
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

    /**
     * @throws OptimisticLockException
     * @throws \Doctrine\ORM\ORMException
     * @throws IsNullException
     */
    #[Route('/api/articles/{slug}', name: 'app_article_delete', methods: ['DELETE'])]
    public function delete(string $slug, ArticlesRepository $article): Response
    {
        try {
            $article->deleteArticle($slug);
        } catch (IsNullException $e) {
            return $this->json([
                'errors' => [
                    'body' => [
                        $e,
                    ],
                ],
            ], 422);
        }

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticlesController.php',
        ]);
    }
}
