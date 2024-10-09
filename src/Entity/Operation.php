<?php

namespace App\Entity;

use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?bool $typeOperation = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $compte = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function isTypeOperation(): ?bool
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(bool $typeOperation): static
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    public function getCompte(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setCompte(?Utilisateur $compte): static
    {
        $this->utilisateur = $compte;

        return $this;
    }

}
