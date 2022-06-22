<?php

require_once __DIR__ . '/CommandeRepository.php';
require_once __DIR__ . '/../../common/Database.php';
require_once __DIR__ . '/Commande.php';


require_once __DIR__ . '/../../article/model/BDDArticleRepository.php';

class BDDCommandeRepository implements \CommandeRepository
{

    // doit crée la commande dans la table commande + 
    // remplie la table commande/user/item
    
    public function createCommande($id_commande,$id_user,$quantite, $id_articles) : bool {
        $bd = Database::getDatabase();

        if (count($quantite) != count($id_articles)) {
            throw new Exception('Les tailles des tableaux sont différents');
        }

   
        $nb_article=array_sum($quantite);
        if($nb_article==0){
            return false;
        }
        $requete = $bd->prepare("INSERT INTO COMMANDE (id_commande, nb_article, id_user)
        VALUES (:id_commande, :nb_article, :id_user)");
        $requete->execute( [
            'id_commande' => $id_commande,
            'nb_article' => $nb_article,
            'id_user' => $id_user,
            ]);


     
        $arr_length = count($quantite);
        for($i=0;$i<$arr_length;$i++)
        {
            $requete = $bd->prepare("INSERT INTO COMMANDE_ARTICLE (id_commande, id_user, id_article, quantite )
            VALUES (:id_commande, :id_user, :id_article, :quantite )");
            
            $requete->execute( [
                'id_commande' => $id_commande,
                'id_user' => $id_user,
                'id_article' => $id_articles[$i],
                'quantite' => $quantite[$i],
                ]);
        }            
        return true;
    }

    public function deleteCommande($id) : void {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("DELETE FROM COMMMANDE WHERE  id_commande = :id_commande");
        $requete->execute([
            'id_commande' => $id]);

        $requete = $bd->prepare("DELETE FROM COMMMANDE_ARTICLE WHERE  id_commande = :id_commande");
        $requete->execute([
            'id_commande' => $id]);
    }



    public function getCommandeById($id) : ?Commande {
        $id = intval($id);
        $bd = Database::getDatabase();

        $quantites = array();
        $id_articles = array();
        $requete = $bd->prepare("SELECT * FROM COMMANDE_ARTICLE WHERE  id_commande = :id_commande");
        $requete->execute([
            'id_commande' => $id]);
        $tab = $requete->fetchAll();
        $arr_length = count($tab);

        for($i=0;$i<$arr_length;$i++){
            array_push($quantites, $tab[$i]['quantite']);
            array_push($id_articles, $tab[$i]['id_article']);
        }
    
        $total = 0;
        for ($i=0;$i<$arr_length;$i++) {
            $requete = $bd->prepare("SELECT * FROM ARTICLE WHERE id_article= :id_article AND quantite > 0");
            $requete->execute(['id_article' => $id_articles[$i]]);
            $tab = $requete->fetchAll();
            for ($j = 0; $j < $quantites[$i]; $j++)
                $total += $tab[0]['prix'];
        }
        
        //var_dump($id);
        $requete = $bd->prepare("SELECT * FROM COMMANDE WHERE id_commande= :id_commande");
        $requete->execute(['id_commande' => $id]);
        $tab = $requete->fetchAll();
        
        return new Commande($tab[0]['id_commande'], $tab[0]['id_user'], $tab[0]['nb_article'], $id_articles, $quantites, $total);
        
    }

    public function getAllCommande($id_user): ?array
    {
        $bd = Database::getDatabase();

        // on pourrait juste select les id ?↓
        $sqlQuery ="SELECT id_commande FROM COMMANDE WHERE id_user = :id_user";
        $stmt = $bd->prepare($sqlQuery);
        $stmt->execute(['id_user' => $id_user]);

        $tab = $stmt->fetchAll();
        $arr = array();
        foreach($tab as &$c) {
            $arr[] = $this->getCommandeById($c['id_commande']);
        }

        return $arr;
    }

    public function getMaxId(): int
    {
        $bd = Database::getDatabase();

        // on pourrait juste select les id ?↓
        $sqlQuery ="SELECT MAX(id_commande) FROM COMMANDE";
        $stmt = $bd->prepare($sqlQuery);
        $stmt->execute();
        $tab = $stmt->fetch(PDO::FETCH_ASSOC);
        return $tab['MAX(id_commande)'];
    }
}