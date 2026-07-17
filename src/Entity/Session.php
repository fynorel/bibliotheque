<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_admin = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTime $expiration = null;

    #[ORM\Column(nullable: true)]
    private ?array $donnees = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdAdmin(): ?int
    {
        return $this->id_admin;
    }

    public function setIdAdmin(int $id_admin): static
    {
        $this->id_admin = $id_admin;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiration(): ?\DateTime
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTime $expiration): static
    {
        $this->expiration = $expiration;

        return $this;
    }

    public function getDonnees(): ?array
    {
        return $this->donnees;
    }

    public function setDonnees(?array $donnees): static
    {
        $this->donnees = $donnees;

        return $this;
    }
}
