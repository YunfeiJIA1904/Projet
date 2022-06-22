<?php
require_once __DIR__ . '../../common/CommonComponents.php';
require_once __DIR__ . '../../common/AuthenticationService.php';
require_once __DIR__ . './controller/ArticleViewController.php';
require_once __DIR__ . './model/BDDArticleRepository.php';

$articleController = new ArticleViewController(new AuthenticationService(), new BDDArticleRepository());
CommonComponents::render($articleController->viewAction(), 'Visualiser un article', (new AuthenticationService())->isUserConnected(),
    (new AuthenticationService())->isUserAdmin());

?>