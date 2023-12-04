<?php

namespace App\Entity;

use App\Repository\ReponseOffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToOne(inversedBy: 'reponse_offres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $ref_user = null;

    #[ORM\OneToOne(inversedBy: 'reponse_offres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Offre $ref_offre = null;

    #[ORM\OneToMany(mappedBy: 'ref_reponse_offre', targetEntity: RDV::class)]
    private Collection $RDVs;

    public function __construct()
    {
        $this->RDVs = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, RDV>
     */
    public function getRDVs(): Collection
    {
        return $this->RDVs;
    }

    public function addRDV(RDV $rDV): static
    {
        if (!$this->RDVs->contains($rDV)) {
            $this->RDVs->add($rDV);
            $rDV->setReponseOffre($this);
        }

        return $this;
    }

    public function removeRDV(RDV $rDV): static
    {
        if ($this->RDVs->removeElement($rDV)) {
            // set the owning side to null (unless already changed)
            if ($rDV->getReponseOffre() === $this) {
                $rDV->setReponseOffre(null);
            }
        }

        return $this;
    }
}
