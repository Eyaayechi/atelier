<?php

namespace App\Entity;

use App\Repository\AutherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutherRepository::class)]
class Auther
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrbook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrbook(): ?int
    {
        return $this->nbrbook;
    }

    public function setNbrbook(int $nbrbook): static
    {
        $this->nbrbook = $nbrbook;

        return $this;
    }
}
