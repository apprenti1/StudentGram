<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'Offres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprise $ref_entreprise = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'Offres', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeContrat $ref_type_contrat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $valid = false;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: ReponseOffre::class)]
    private Collection $response_offres;

    public function __construct()
    {
        $this->response_offres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRefEntreprise(): ?Entreprise
    {
        return $this->ref_entreprise;
    }

    public function setRefEntreprise(Entreprise $ref_entreprise): static
    {
        $this->ref_entreprise = $ref_entreprise;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getRefTypeContrat(): ?TypeContrat
    {
        return $this->ref_type_contrat;
    }

    public function setRefTypeContrat(TypeContrat $typeDeContrat): static
    {
        $this->ref_type_contrat = $typeDeContrat;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): static
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * @return Collection<int, ReponseOffre>
     */
    public function getResponseOffres(): Collection
    {
        return $this->response_offres;
    }

    public function addResponseOffre(ReponseOffre $responseOffre): static
    {
        if (!$this->response_offres->contains($responseOffre)) {
            $this->response_offres->add($responseOffre);
            $responseOffre->setOffre($this);
        }

        return $this;
    }

    public function removeResponseOffre(ReponseOffre $responseOffre): static
    {
        if ($this->response_offres->removeElement($responseOffre)) {
            // set the owning side to null (unless already changed)
            if ($responseOffre->getOffre() === $this) {
                $responseOffre->setOffre(null);
            }
        }

        return $this;
    }
}
