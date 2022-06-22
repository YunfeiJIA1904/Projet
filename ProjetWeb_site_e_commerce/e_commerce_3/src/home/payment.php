<?php
require_once '../common/CommonComponents.php';
require_once '../common/AuthenticationService.php';
require_once '../home/controller/PaymentController.php';
require_once '../article/model/BDDArticleRepository.php';



$PaymentController = new PaymentController(new AuthenticationService());
CommonComponents::render($PaymentController->viewAction(),'Payment', (new AuthenticationService())->isUserConnected());


?>