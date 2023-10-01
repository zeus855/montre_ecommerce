<?php

namespace App\Entity;

use App\Repository\MontreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MontreRepository::class)]
#[Vich\Uploadable]

class Montre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    private ?string $image_name = null;

    #[Vich\UploadableField(mapping: 'montres', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    //On rajoute un constructeur
    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->montreCommandes = new ArrayCollection();
        
    }
    

    #[ORM\ManyToOne(inversedBy: 'montres')]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'montre', targetEntity: Image::class, orphanRemoval: true, cascade:['persist', 'remove'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'montre', targetEntity: MontreCommande::class, orphanRemoval: true)]
    private Collection $montreCommandes;

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
    

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }


     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    
    

    public function getImageName(): ?string
    {
        return $this->image_name;
    }

    public function setImageName(?string $image_name): static
    {
        $this->image_name = $image_name;

        return $this;
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setMontre($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getMontre() === $this) {
                $image->setMontre(null);
            }
        }

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
            $montreCommande->setMontre($this);
        }

        return $this;
    }

    public function removeMontreCommande(MontreCommande $montreCommande): static
    {
        if ($this->montreCommandes->removeElement($montreCommande)) {
            // set the owning side to null (unless already changed)
            if ($montreCommande->getMontre() === $this) {
                $montreCommande->setMontre(null);
            }
        }

        return $this;
    }
}
