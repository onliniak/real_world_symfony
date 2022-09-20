<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticlesRepository::class)]
class Articles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private $slug;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $body;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updatedAt;

    #[ORM\Column(type: 'string')]
    private $authorID;

    #[ORM\OneToMany(mappedBy: 'article_id', targetEntity: FavouritedArticles::class, orphanRemoval: true)]
    private $favourited;

    #[ORM\Column(type: 'integer')]
    private $favoritesCount;

    #[ORM\Column(type: 'array', nullable: true)]
    private $tagList = [];

    public function __construct()
    {
        $this->favourited = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug ?? strtolower(preg_replace('/ /', '-', $this->title));

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt ?? new \DateTimeImmutable();

        return $this;
    }

    public function getAuthorID(): ?string
    {
        return $this->authorID;
    }

    public function setAuthorID(string $authorID): self
    {
        $this->authorID = $authorID;

        return $this;
    }

    /**
     * @return Collection<int, FavouritedArticles>
     */
    public function getFavourited(): Collection
    {
        return $this->favourited;
    }

    public function addFavourited(FavouritedArticles $favourited): self
    {
        if (!$this->favourited->contains($favourited)) {
            $this->favourited[] = $favourited;
            $favourited->setArticleId($this);
        }

        return $this;
    }

    public function removeFavourited(FavouritedArticles $favourited): self
    {
        if ($this->favourited->removeElement($favourited)) {
            // set the owning side to null (unless already changed)
            if ($favourited->getArticleId() === $this) {
                $favourited->setArticleId(null);
            }
        }

        return $this;
    }

    public function getFavoritesCount(): ?int
    {
        return $this->favoritesCount;
    }

    public function setFavoritesCount(int $favoritesCount): self
    {
        $this->favoritesCount = $favoritesCount;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tagList;
    }

    public function setTags(?array $tagList): self
    {
        $this->tagList = $tagList;

        return $this;
    }
}
