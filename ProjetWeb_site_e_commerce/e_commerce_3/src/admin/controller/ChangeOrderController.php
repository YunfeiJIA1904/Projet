<?php

require_once __DIR__ . '/../view/buildChangeOrderForm.php';
require_once __DIR__ . '/../model/OrdreParDefaut.php';

class ChangeOrderController
{

    private $authenticationService;
    private $ordreParDefaut;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
        $this->ordreParDefaut = new OrdreParDefaut();
    }

    public function viewAction(): string
    {
        $success = '';
        if (!$this->authenticationService->isUserAdmin()) {
            $this->redirectToHomepage();
        }

        if ($this->isChangeFormFilledAndValid()) {
            $croissant = 0;
            $champ = htmlspecialchars(trim($_POST['champ']));
            if (isset($_POST['croissant']) && $_POST['croissant'] = 'on')
            if ($croissant = 'on')
                $croissant = 1;
            $this->ordreParDefaut->setOrdreParDefaut($champ, $croissant);
            $success = "Les paramètres ont bien été changés.";
        }

        return buildChangeOrderForm($success);
    }

    private function isChangeFormFilledAndValid() : bool {
        return isset($_POST['champ']) && $_POST['champ'] !== '';
    }

    private function redirectToHomepage(): void {
        header('Location: /e_commerce_3/src/home');
    }

}
?>