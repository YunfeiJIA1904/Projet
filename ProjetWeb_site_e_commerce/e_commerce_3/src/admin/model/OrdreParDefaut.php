<?php

require_once __DIR__ . '/../../common/Database.php';

class OrdreParDefaut
{
    public function getOrdreParDefaut(): array {
        $ordre = array();
        $ordre[] = $this->getChampParDefaut();
        $ordre[] = $this->getCroissantParDefaut();
    }

    public function setOrdreParDefaut(string $champ, int $croissant): void {
        $this->setChampParDefaut($champ);
        $this->setCroissantParDefaut($croissant);
    }

    public function getChampParDefaut() {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT CHAMP FROM ADMINISTRATION WHERE ID = 1");
        $requete->execute();
        $requete = $requete->fetchAll();
        return $requete[0]['CHAMP'];
    }

    public function getCroissantParDefaut() {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT CROISSANT FROM ADMINISTRATION WHERE ID = 1");
        $requete->execute();
        $requete = $requete->fetchAll();
        return $requete[0]['CROISSANT'];
    }

    private function setChampParDefaut(string $champ): void {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("UPDATE ADMINISTRATION SET CHAMP = :champ WHERE ID = 1");
        $requete->execute(['champ'=>$champ]);
    }

    private function setCroissantParDefaut($croissant): void {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("UPDATE ADMINISTRATION SET CROISSANT = :cr WHERE ID = 1");
        $requete->execute(['cr'=>$croissant]);
    }


}