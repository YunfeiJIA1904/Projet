<?php
require_once '../common/CommonComponents.php';
require_once '../common/AuthenticationService.php';
require_once './controller/UserSigninController.php';
require_once './model/BDDUserRepository.php';

$userController = new UserSigninController(new AuthenticationService(), new BDDUserRepository());
CommonComponents::render($userController->signinAction(), 'Inscription', (new AuthenticationService())->isUserConnected());