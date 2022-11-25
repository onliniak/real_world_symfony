<?php

namespace App\Entity;

use App\Repository\FollowersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowersRepository::class)]
class Followers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $user1 = null;

    #[ORM\Column(length: 255)]
    private ?string $user2 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser1(): ?string
    {
        return $this->user1;
    }

    public function setUser1(string $user1): self
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getUser2(): ?string
    {
        return $this->user2;
    }

    public function setUser2(string $user2): self
    {
        $this->user2 = $user2;

        return $this;
    }
}
