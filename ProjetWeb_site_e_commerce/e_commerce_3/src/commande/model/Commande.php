<?php

class Commande
{
    private $id;
    private $id_user;

    private $nb_article;
    private $quantites = array();
    private $id_articles = array();

    private $total;

    public function __construct($id,$id_user,$nb_article,$id_articles, $quantites, $total){
        $this->id = $id;
        $this->nb_article = $nb_article;
        $this->id_user = $id_user;

        $this->id_articles=$id_articles;
        $this->quantites=$quantites;
        $this->total=$total;
    }
    
    public function getId() : int {
        return $this->id;
    }

    public function getIdUser() : int {
        return $this->id_user;
    }

    public function getNb_article() : int {
        return $this->nb_article;
    }

    
    public function getQuantiteByIndex($i): int {
        return $this->quantites[$i];
    }

    public function getArticle_IdByIndex($i): int {
        return $this->id_articles[$i];
    }
    

    public function getTotal(): float {
        return $this->total;
    }
}


?>
