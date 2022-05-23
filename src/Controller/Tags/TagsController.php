<?php
declare(strict_types=1);

namespace App\Controller\Tags;

use App\Repository\ArticlesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagsController extends AbstractController
{
    #[Route('/api/tags', name: 'app_tags', methods: ['GET'])]
    public function index(ArticlesRepository $articles): Response
    {
        return $this->json(
            ['tags' => $articles->getTags()]
        );
    }
}
