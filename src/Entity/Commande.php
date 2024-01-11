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

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Adresse $livraison = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Adresse $facturation = null;

    #[ORM\Column(nullable: true)]
    private ?int $total = null;

    // Créer une fonction raccourci pour compter la quatité 
    // des montres qu'on a par commande
    public function getTotalMontre(): int
    {
        $montre = 0;

        foreach($this->montreCommandes as $montreCommande) {
            $montre += $montreCommande->getQuantite();
        }

        return $montre;
    }

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

    public function getLivraison(): ?Adresse
    {
        return $this->livraison;
    }

    public function setLivraison(?Adresse $livraison): static
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getFacturation(): ?Adresse
    {
        return $this->facturation;
    }

    public function setFacturation(?Adresse $facturation): static
    {
        $this->facturation = $facturation;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(?int $total): static
    {
        $this->total = $total;

        return $this;
    }
}
