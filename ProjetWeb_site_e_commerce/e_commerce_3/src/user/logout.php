<?php
require_once '../common/AuthenticationService.php';

$authenticationService = new AuthenticationService();
$authenticationService->logoutUser();
header('Location: /e_commerce_3/src/home/index.php');
