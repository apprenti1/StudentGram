<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_entreprise = null;

    #[ORM\Column(length: 255)]
    private ?string $fonction_employe = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $cp = null;

    #[ORM\OneToOne(inversedBy: 'entreprise', cascade: ['persist', 'remove'])]
    private ?User $ref_user = null;

    #[ORM\OneToMany(mappedBy: 'ref_entreprise', cascade: ["persist", "remove"], targetEntity: Offre::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $offres;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }
    public function __toString()
    {
        return $this->nom_entreprise;
    }



    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setRefEntreprise($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getRefEntreprise() === $this) {
                $offre->setRefEntreprise(null);
            }
        }

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nom_entreprise;
    }

    public function setNomEntreprise(string $nom_entreprise): static
    {
        $this->nom_entreprise = $nom_entreprise;

        return $this;
    }

    public function getFonctionEmploye(): ?string
    {
        return $this->fonction_employe;
    }

    public function setFonctionEmploye(string $fonction_employe): static
    {
        $this->fonction_employe = $fonction_employe;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getRefUser(): ?User
    {
        return $this->ref_user;
    }

    public function setRefUser(?User $ref_user): static
    {
        $this->ref_user = $ref_user;

        return $this;
    }


    public function setOffres(?Offre $offres): static
    {
        $this->offres = $offres;

        return $this;
    }
}
