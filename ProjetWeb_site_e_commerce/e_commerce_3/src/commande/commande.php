<?php
require_once __DIR__ . '../../common/CommonComponents.php';
require_once __DIR__ . '../../common/AuthenticationService.php';
require_once __DIR__ . './controller/CommandeViewController.php';
require_once __DIR__ . './model/BDDCommandeRepository.php';

$commandeController = new CommandeViewController(new AuthenticationService(), new BDDCommandeRepository());
CommonComponents::render($commandeController->viewAction(), 'Visualiser commandes', true,
    (new AuthenticationService())->isUserAdmin());

?>