<?php
require_once __DIR__ . '../../common/CommonComponents.php';
require_once __DIR__ . '../../common/AuthenticationService.php';
require_once __DIR__ . './controller/AddArticleController.php';
require_once __DIR__ . '../../article/model/BDDArticleRepository.php';

$addarticleController = new AddArticleController(new AuthenticationService(), new BDDArticleRepository());
CommonComponents::render($addarticleController->viewAction(), 'Ajouter un article', (new AuthenticationService())->isUserConnected(),
    (new AuthenticationService())->isUserAdmin());

?>