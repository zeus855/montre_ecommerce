<?php

namespace App\Entity;

use App\Repository\MontreCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MontreCommandeRepository::class)]
class MontreCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'montreCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Montre $montre = null;

    #[ORM\ManyToOne(inversedBy: 'montreCommandes')]
    private ?Commande $commande = null;

    #[ORM\Column]
    // On rajoute le statut
    private ?bool $statut = false;

    #[ORM\ManyToOne(inversedBy: 'montreCommandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    //On rajoute un constructeur
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();        
        
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    // FONCTION QUI NE SERVIRA PAS CAR ON NE FERRA PAS DE MODIF 
    
    // public function setCreatedAt(\DateTimeImmutable $createdAt): static
    // {
    //     $this->createdAt = $createdAt;

    //     return $this;
    // }

    public function getMontre(): ?Montre
    {
        return $this->montre;
    }

    public function setMontre(?Montre $montre): static
    {
        $this->montre = $montre;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

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
