<?php
require_once __DIR__ . '../../common/CommonComponents.php';
require_once __DIR__ . '../../common/AuthenticationService.php';
require_once __DIR__ . './controller/UserLoginController.php';
require_once __DIR__ . './model/BDDUserRepository.php';

$userController = new UserLoginController(new AuthenticationService(), new BDDUserRepository());
CommonComponents::render($userController->loginAction(), 'Connexion', (new AuthenticationService())->isUserConnected());

?>