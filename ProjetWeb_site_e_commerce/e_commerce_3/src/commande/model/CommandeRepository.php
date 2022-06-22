<?php


interface CommandeRepository
{
    // array of article
    public function createCommande($id_commande,$id_user,$quantite, $articles) : bool;

    public function deleteCommande($id) : void;

    public function getCommandeById($id) : ?Commande;

    public function getAllCommande($id_user): ?array;

    //public function getAllArticlesParametre($categorie='all',$trie='default') : PDOStatement;
}