<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heure = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $duree = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Salle $salle = null;

    #[ORM\Column]
    private ?bool $valide = false;



    #[Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        // Vérifier si la date est un jour de la semaine (lundi = 1, vendredi = 5)
        if ($this->getDate()->format('N') < 1 || $this->getDate()->format('N') > 5) {
            $context->buildViolation("L'événement doit être du lundi au vendredi")
                ->atPath('date')
                ->addViolation();
        }

        # Validation heure début à partir de 18h et max à 22h
        if ($this->getHeure()->format('H') < 18 || $this->getHeure()->format('H') > 22) {

            $context->buildViolation("L'événement doit commencer entre 18h et 22h")
                ->atPath('heure')
                ->addViolation();
        }

        # Validation durée éxcède pas 23h
        $heureFinPlusDuree = clone ($this->getHeure());
        $dureeHeures = intval( $this->duree->format('H') );
        $dureeMin = intval( $this->duree->format('i') );
        $heureFinPlusDuree->modify('+' . $dureeHeures . 'hour');
        $heureFinPlusDuree->modify('+' . $dureeMin . 'minute');
        $hFin = intval( $heureFinPlusDuree->format('H') );
        $minFin = $heureFinPlusDuree->format('i');
        $hFin += $minFin/60;
        if ($hFin<18 || $hFin>23  ) {

            $context->buildViolation("L'événement doit se terminer maxi à 23h")
                ->atPath('duree')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;

        return $this;
    }

}
