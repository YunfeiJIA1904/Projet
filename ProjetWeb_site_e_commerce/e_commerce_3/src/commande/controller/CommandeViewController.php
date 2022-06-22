<?php
require_once __DIR__ . '/../view/buildAllCommandeView.php';
require_once __DIR__ . '/../../user/model/BDDUserRepository.php';
class CommandeViewController
{
    private $authenticationService;
    private $commandeRepository;
    
    public function __construct(AuthenticationService $authenticationService, CommandeRepository $commandeRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->commandeRepository = $commandeRepository;
    }

    public function viewAction(): string
    {

        if (!$this->authenticationService->isUserConnected()) {
            $this->redirectToLogin();
        } else if ($this->authenticationService->isUserAdmin()) {
            $this->redirectToHomepage();
        }
       // session_start();
        $email = $_SESSION["pseudo"];
        $id_user = (new BDDUserRepository())->getUserIDByEmail($email);

        return buildAllCommandeView($this->commandeRepository->getAllCommande($id_user));

    }

    private function redirectToLogin(): void {
        header('Location: /e_commerce_3/src/user/login.php');
    }

    private function redirectToHomepage(): void {
        header('Location: /e_commerce_3/src/home');
    }
}
?>