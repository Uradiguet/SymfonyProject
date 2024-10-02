<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[NotBlank]
    private ?string $login = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[PasswordStrength(message: "Mot de passe pas assez fort !")]
    private ?string $password = null;

    /**
     * @var Collection<int, Compte>
     */
    #[ORM\OneToMany(targetEntity: Compte::class, mappedBy: 'titulaire', orphanRemoval: true)]
    private Collection $comptes;

    /**
     * @var Collection<int, Famille>
     */
    #[ORM\OneToMany(targetEntity: Famille::class, mappedBy: 'utilisateur')]
    private Collection $familles;

    /**
     * @var Collection<int, Partage>
     */
    #[ORM\OneToMany(targetEntity: Partage::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $partages;

    public function __construct()
    {
        $this->comptes = new ArrayCollection();
        $this->familles = new ArrayCollection();
        $this->partages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Compte>
     */
    public function getComptes(): Collection
    {
        return $this->comptes;
    }

    public function addCompte(Compte $compte): static
    {
        if (!$this->comptes->contains($compte)) {
            $this->comptes->add($compte);
            $compte->setTitulaire($this);
        }

        return $this;
    }

    public function removeCompte(Compte $compte): static
    {
        if ($this->comptes->removeElement($compte)) {
            // set the owning side to null (unless already changed)
            if ($compte->getTitulaire() === $this) {
                $compte->setTitulaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Famille>
     */
    public function getFamilles(): Collection
    {
        return $this->familles;
    }

    public function addFamille(Famille $famille): static
    {
        if (!$this->familles->contains($famille)) {
            $this->familles->add($famille);
            $famille->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFamille(Famille $famille): static
    {
        if ($this->familles->removeElement($famille)) {
            // set the owning side to null (unless already changed)
            if ($famille->getUtilisateur() === $this) {
                $famille->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partage>
     */
    public function getPartages(): Collection
    {
        return $this->partages;
    }

    public function addPartage(Partage $partage): static
    {
        if (!$this->partages->contains($partage)) {
            $this->partages->add($partage);
            $partage->setUtilisateur($this);
        }

        return $this;
    }

    public function removePartage(Partage $partage): static
    {
        if ($this->partages->removeElement($partage)) {
            // set the owning side to null (unless already changed)
            if ($partage->getUtilisateur() === $this) {
                $partage->setUtilisateur(null);
            }
        }

        return $this;
    }
}
