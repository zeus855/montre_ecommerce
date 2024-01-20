<?php

namespace App\Model;

use App\Entity\Categorie;

class Search
{
    /**
     * @var ?string
     */
    private $recherche;

     /**
     * @var ?Categorie
     */
    private $categorie;

    public function getRecherche(): ?string
    {
        return $this->recherche;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setRecherche(?string $term): void
    {
        $this->recherche = $term;
    }

    public function setCategorie(?Categorie $categorie): void
    {
        $this->categorie = $categorie;
    }
}