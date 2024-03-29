<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $author_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $body;

    #[ORM\Column(type: 'string')]
    private $createdAt;

    #[ORM\Column(type: 'string')]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $article_slug;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthorId(): ?string
    {
        return $this->author_id;
    }

    public function setAuthorId(string $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getArticleSlug(): string
    {
        return $this->article_slug;
    }

    public function setArticleSlug(string $article_slug): self
    {
        $this->article_slug = $article_slug;

        return $this;
    }
}
