<?php

class Article
{
    private $nom;
    private $image;
    private $quantite;
    private $prix;
    private $categorie;
    private $description;

    public function __construct($nom, $image, $quantite, $prix, $categorie, $description){
        $this->nom = $nom;
        $this->image = $image;
        $this->quantite = $quantite;
        $this->prix = $prix;
        $this->categorie = $categorie;
        $this->description = $description;
    }

    public function getIdArticle() : int {
        return (new BDDArticleRepository())->getIdArticle($this->nom, $this->prix, $this->categorie);
    }

    public function getNom() : String {
        return $this->nom;
    }

    public function getImage(): String {
        return $this->image;
    }

    public function getQuantite(): int {
        return $this->quantite;
    }

    public function getPrix(): float {
        return $this->prix;
    }

    public function getCategorie() : String {
        return $this->categorie;
    }

    public function getDescription() : String {
        return $this->description;
    }

}


?>
