<?php

namespace App\Entity;

use App\Repository\FavoritedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritedRepository::class)]
class Favorited
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $article_slug;

    #[ORM\Column(type: 'string')]
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticleSlug(): ?string
    {
        return $this->article_slug;
    }

    public function setArticleSlug(string $article_slug): self
    {
        $this->article_slug = $article_slug;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->user_id;
    }

    public function setUserId(string $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
