<?php

namespace App\Entity;

use App\Repository\ReponseOffreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseOffreRepository::class)]
class ReponseOffre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $cv = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $motivation = null;

    #[ORM\OneToOne(inversedBy: 'reponce_offres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ref_user = null;

    #[ORM\OneToOne(inversedBy: 'reponce_offres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $ref_offre = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(string $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getMotivation(): ?string
    {
        return $this->motivation;
    }

    public function setMotivation(string $motivation): static
    {
        $this->motivation = $motivation;

        return $this;
    }

    public function getRefUser(): ?User
    {
        return $this->ref_user;
    }

    public function setRefUser(User $ref_user): static
    {
        $this->ref_user = $ref_user;

        return $this;
    }

    public function getRefOffre(): ?Offre
    {
        return $this->ref_offre;
    }

    public function setRefOffre(Offre $ref_offre): static
    {
        $this->ref_offre = $ref_offre;

        return $this;
    }
}
