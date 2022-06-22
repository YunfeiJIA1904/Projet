<?php

require_once __DIR__ . '/ArticleRepository.php';
require_once __DIR__ . '/../../common/Database.php';
require_once __DIR__ . '/../../admin/model/OrdreParDefaut.php';
require_once __DIR__ . '/Article.php';

class BDDArticleRepository implements \ArticleRepository
{

    public function createArticle($nom, $image, $quantite, $prix, $categorie, $description) : bool {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("INSERT INTO ARTICLE (nom, quantite, categorie, prix, description)
VALUES (:nom, :quantite, :categorie, :prix,:description)");

        $requete->execute( [
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => $quantite,
            'categorie' => $categorie,
            'description' => $description
            ]);
        return true;
    }

    public function deleteArticle($id) : void {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("DELETE FROM ARTICLE WHERE  id_article = :id_article");
        $requete->execute([
            'id_article' => $id]);
    }

    public function getIdArticle($nom, $prix, $categorie) : int {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT id_article FROM ARTICLE WHERE nom = :n AND prix= :p AND categorie = :cat");
        $requete->execute([
            'n' => $nom,
            'p' => $prix,
            'cat' => $categorie]);
        $tab = $requete->fetchAll();
        return $tab[0][0];
    }

    public function getArticleById($id) : ?Article {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT * FROM ARTICLE WHERE id_article= :id_article AND quantite > 0");
        $requete->execute([
            'id_article' => $id]);
        $tab = $requete->fetchAll();
        return new Article($tab[0]['nom'], $tab[0]['image'], $tab[0]['quantite'], $tab[0]['prix'],
            $tab[0]['categorie'], $tab[0]['description']);
    }



    public function getAllArticlesParametre($categorie='all',$trie='default',$min='',$max='') : PDOStatement {
        $bd = Database::getDatabase();
        $sqlQuery ="SELECT * FROM ARTICLE WHERE quantite > 0" ;
        if ($categorie!='all') {
            $sqlQuery =$sqlQuery." AND categorie='$categorie'";
        }
        if ($min != '' and $max == '') {
            $sqlQuery =$sqlQuery." AND prix >= $min";
        }
        elseif ($max != '' and $min == '') {
            $sqlQuery =$sqlQuery." AND prix <= $max";
        }
        elseif ($min != '' and $max != '') {
            $sqlQuery =$sqlQuery." AND prix <= $max AND prix >= $min";
        }
        if ($trie!='default') {
            $sqlQuery =$sqlQuery." $trie";
        }
        if ($trie == 'default') {
            $ordre = new OrdreParDefaut();
            $champ = $ordre->getChampParDefaut();
            if ($ordre->getCroissantParDefaut() == 1)
                $sqlQuery =$sqlQuery." ORDER BY $champ";
            else
                $sqlQuery =$sqlQuery." ORDER BY $champ DESC";
        }



        $stmt = $bd->prepare($sqlQuery);
        $stmt->execute();

        return $stmt;
    }

    public function getAllArticles(): PDOStatement
    {
        $categorie='all';
        $trie='default';
        $min = '';
        $max = '';
        if (isset($_GET['categorie'])) {
            $categorie=$_GET['categorie'];
        }
        if (isset($_GET['trie'])) {
            $trie=$_GET['trie'];
        }
        if (isset($_GET['min'])) {
            $min=$_GET['min'];
        }
        if (isset($_GET['max'])) {
            $max=$_GET['max'];
        }
        return $this->getAllArticlesParametre($categorie,$trie,$min,$max);
    }

    public function getAllCategories(): array {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT DISTINCT CATEGORIE FROM ARTICLE WHERE quantite > 0");
        $requete->execute();
        $requete = $requete->fetchAll();
        $tab = array();
        foreach ($requete as &$rq) {
            $tab[] = $rq['CATEGORIE'];
        }

        return $tab;
    }

    public function ExistArticle($id):bool{
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT id_article FROM ARTICLE WHERE id_article= :id_article AND quantite > 0");
        $requete->execute([
            'id_article' => $id]);
        $tab = $requete->fetchAll();
        if(empty($tab)){
            return false;
        }
        return true;
    }

    public function getIdsIn($ids){
        $bd = Database::getDatabase();
        $requete = $bd->query('SELECT * FROM ARTICLE WHERE id_article IN ('.implode(',',$ids).')');
        $tab = $requete->fetchAll();
        return $tab;

    }

    public function Enough($id, $quantite){
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT * FROM ARTICLE WHERE id_article= :id_article AND quantite > :quantite");
        $requete->execute([
            'id_article' => $id,
            'quantite' =>$quantite]);
        $tab = $requete->fetchAll();
        if(empty($tab)){
            return false;
        }
        return true;


    }
}
