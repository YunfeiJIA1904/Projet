<?php

require_once __DIR__ . '/UserRepository.php';
require_once __DIR__ .'../../../common/Database.php';

class BDDUserRepository implements UserRepository
{

    public function checkUserExistence(string $email, string $password): bool
    {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT * FROM COMPTE WHERE EMAIL = :em");
        $requete->execute([
            'em' => $email
        ]);
        if (empty($requete->fetchAll()))
            return false;
        else
            return true;
    }

    public function checkUserPassword(string $email, string $password): bool {
        if ($this->checkUserExistence($email, $password)) {
            $bd = Database::getDatabase();
            $requete = $bd->prepare("SELECT PASSWORD FROM COMPTE WHERE EMAIL = :em");
            $requete->execute([
                'em' => $email
            ]);
            $mdp_bdd = $requete->fetchAll();
            if(password_verify($password, $mdp_bdd[0]['PASSWORD']))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

    public function getUserByEmail(string $email) : ?User
    {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT * FROM COMPTE WHERE EMAIL = :us");
        $requete->execute([
            'us' => $email
        ]);
        $requete = $requete->fetchAll();
        if(!empty($requete)) {
            $userBuilder = new UserBuilder();
            return $userBuilder
                ->withFirstName($requete[0]['firstName'])
                ->withLastName($requete[0]['lastName'])
                ->withUsername($requete[0]['username'])
                ->build();
        }
        return null;

    }

    public function getUserIDByEmail(string $email) : ?int
    {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT * FROM COMPTE WHERE EMAIL = :us");
        $requete->execute([
            'us' => $email
        ]);
        $requete = $requete->fetchAll();
        if(!empty($requete)) {
            return $requete[0]['id_user'];
        }
        return null;

    }

    public function createUser($firstName, $lastName, $email, $password): void
    {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("INSERT INTO COMPTE (nom, prenom, email, password) VALUES (:f, :l, :e, :p)");

        $requete->execute([
            'f' => $firstName,
            'l' => $lastName,
            'e' => $email,
            'p' => $password
        ]);
    }

    public function getIsAdmin(string $email): bool
    {
        $bd = Database::getDatabase();
        $requete = $bd->prepare("SELECT ISADMIN FROM COMPTE WHERE EMAIL = :us");
        $requete->execute(['us' => $email]);
        $requete = $requete->fetchAll();
        if ($requete[0]['ISADMIN'] == '1') return true;
        else return false;
    }
}