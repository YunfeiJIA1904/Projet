<?php

require_once __DIR__ . '../../common/CommonComponents.php';
require_once '../admin/controller/ChangeOrderController.php';
require_once __DIR__ . '../../common/AuthenticationService.php';

$changeOrderController = new ChangeOrderController(new AuthenticationService());
CommonComponents::render($changeOrderController->viewAction(), "Modifier l'ordre d'affichage",
    (new AuthenticationService())->isUserConnected(), (new AuthenticationService())->isUserAdmin());

?>