<?php
require_once '../common/CommonComponents.php';
require_once '../common/AuthenticationService.php';
require_once '../home/controller/IndexController.php';
require_once '../article/model/BDDArticleRepository.php';

$indexController = new IndexController(new AuthenticationService(), new BDDArticleRepository());
CommonComponents::render($indexController->viewAction(),'Index', (new AuthenticationService())->isUserConnected(),
    (new AuthenticationService())->isUserAdmin());
?>