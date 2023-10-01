<?php

namespace App\Entity;

use App\Entity\MontreCommande;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Constante\CommandeConstante;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;
    

    #[ORM\Column(length: 255)]
    private ?string $statut = CommandeConstante::EN_COURS;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: MontreCommande::class)]
    private Collection $montreCommandes;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->montreCommandes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, MontreCommande>
     */
    public function getMontreCommandes(): Collection
    {
        return $this->montreCommandes;
    }

    public function addMontreCommande(MontreCommande $montreCommande): static
    {
        if (!$this->montreCommandes->contains($montreCommande)) {
            $this->montreCommandes->add($montreCommande);
            $montreCommande->setCommande($this);
        }

        return $this;
    }

    public function removeMontreCommande(MontreCommande $montreCommande): static
    {
        if ($this->montreCommandes->removeElement($montreCommande)) {
            // set the owning side to null (unless already changed)
            if ($montreCommande->getCommande() === $this) {
                $montreCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
