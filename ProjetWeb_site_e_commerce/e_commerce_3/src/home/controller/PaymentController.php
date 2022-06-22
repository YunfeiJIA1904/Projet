<?php
require_once __DIR__ . '/../view/buildPaymentView.php';
require_once __DIR__ . '/../../user/model/BDDUserRepository.php';
class PaymentController
{
    
    private $authenticationService;
    
    
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }
    
    public function viewAction(): string
    {

        if (!$this->authenticationService->isUserConnected()) {
            $this->redirectToLogin();
        } else if ($this->authenticationService->isUserAdmin()) {
            $this->redirectToHomepage();
        }

        $email = $_SESSION["pseudo"];
        $id_user = (new BDDUserRepository())->getUserIDByEmail($email);

        $total = $_SESSION["total"];
        return buildPaymentView($id_user,$total);

    }

    private function redirectToLogin(): void {
        header('Location: /e_commerce_3/src/user/login.php');
    }

    private function redirectToHomepage(): void {
        header('Location: /e_commerce_3/src/home');
    }

}
?>