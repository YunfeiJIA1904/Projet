<?php

require_once __DIR__ . '/../user/model/BDDUserRepository.php';
require_once __DIR__ . '/../commande/model/BDDCommandeRepository.php';
require_once __DIR__ . '/../common/AuthenticationService.php';

$authentification = (new AuthenticationService());
$bd_commande = (new BDDCommandeRepository());

$email = $_SESSION["pseudo"];
$id_user =(new BDDUserRepository())->getUserIDByEmail($email);
$max = $bd_commande->getMaxId();

$quantites=array();
$ids=array();
foreach($_SESSION['panier'] as $key => $value){
    array_push($quantites, $value);
    array_push($ids, $key);
}


$bd_commande->createCommande($max+1,$id_user,$quantites, $ids);




/*lancer une alert genre vous avez payer puis retourner page de base*/
header('Location: /e_commerce_3/src/home/index.php');
?>