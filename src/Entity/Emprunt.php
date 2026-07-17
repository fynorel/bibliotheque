<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $id_livre = null;

    #[ORM\Column]
    private ?int $id_copain = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_emprunt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_retour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLivre(): ?int
    {
        return $this->id_livre;
    }

    public function setIdLivre(int $id_livre): static
    {
        $this->id_livre = $id_livre;

        return $this;
    }

    public function getIdCopain(): ?int
    {
        return $this->id_copain;
    }

    public function setIdCopain(int $id_copain): static
    {
        $this->id_copain = $id_copain;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTime
    {
        return $this->date_emprunt;
    }

    public function setDateEmprunt(\DateTime $date_emprunt): static
    {
        $this->date_emprunt = $date_emprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTime
    {
        return $this->date_retour;
    }

    public function setDateRetour(?\DateTime $date_retour): static
    {
        $this->date_retour = $date_retour;

        return $this;
    }
}
