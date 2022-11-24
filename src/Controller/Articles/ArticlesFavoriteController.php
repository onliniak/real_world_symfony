<?php
declare(strict_types=1);

namespace App\Controller\Articles;

use App\Repository\ArticlesRepository;
use App\Repository\FavoritedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ArticlesFavoriteController extends AbstractController
{
    #[Route('/api/articles/{slug}/favorite', name: 'app_articles_favorite', methods: ['POST'])]
    public function create(FavoritedRepository $favoritedRepository, Security $security, string $slug, 
                           ArticlesRepository $articlesRepository): Response
    {
        $favoritedRepository->favorite($slug, $security->getUser()->getUserIdentifier());
        return $this->json($articlesRepository->getSingleArticle($slug));
    }
    #[Route('/api/articles/{slug}/favorite', name: 'app_articles_unfavorite', methods: ['DELETE'])]
    public function delete(FavoritedRepository $favoritedRepository, Security $security, string $slug,
                           ArticlesRepository $articlesRepository): Response
    {
        $favoritedRepository->unfavorite($slug, $security->getUser()->getUserIdentifier());
        return $this->json($articlesRepository->getSingleArticle($slug));
    }
}
