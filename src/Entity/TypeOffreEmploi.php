<?php

namespace App\Entity;

use App\Repository\TypeOffreEmploiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeOffreEmploiRepository::class)]
class TypeOffreEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_de_contrat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeDeContrat(): ?string
    {
        return $this->type_de_contrat;
    }

    public function setTypeDeContrat(string $type_de_contrat): static
    {
        $this->type_de_contrat = $type_de_contrat;

        return $this;
    }
}
