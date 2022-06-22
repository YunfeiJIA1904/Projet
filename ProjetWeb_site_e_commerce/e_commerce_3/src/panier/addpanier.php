<?php
require_once '../common/CommonComponents.php';
require_once '../common/AuthenticationService.php';
require_once '../article/model/BDDArticleRepository.php';
require_once '../panier/controller/AddPanierController.php';

$addPanierController = new addPanierController(new AuthenticationService());
CommonComponents::render($addPanierController->viewAction(), "Ajouter au Panier",
    (new AuthenticationService())->isUserConnected(), (new AuthenticationService())->isUserAdmin());

?>