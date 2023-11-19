<?php

namespace App\Entity;

use App\Repository\TypeContratRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeContratRepository::class)]
class TypeContrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'ref_type_contrat', targetEntity: Offre::class)]
    private Collection $Offres;

    public function __construct()
    {
        $this->Offres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->Offres;
    }

    public function addOffre(Offre $offre): static
    {
        if (!$this->Offres->contains($offre)) {
            $this->Offres->add($offre);
            $offre->setTypeContrat($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): static
    {
        if ($this->Offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getTypeContrat() === $this) {
                $offre->setTypeContrat(null);
            }
        }

        return $this;
    }
}
