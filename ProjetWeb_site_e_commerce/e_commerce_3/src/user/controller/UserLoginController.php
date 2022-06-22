<?php
require_once __DIR__ . '/../view/buildLoginForm.php';

class UserLoginController
{
  private $authenticationService;
  private $userRepository;

  public function __construct(AuthenticationService $authenticationService, UserRepository $userRepository)
  {
    $this->authenticationService = $authenticationService;
    $this->userRepository = $userRepository;
  }

  public function loginAction(): string {
    $error = '';

    if ($this->authenticationService->isUserConnected()) {
      $this->redirectToHomepage();
    }
    if ($this->isLoginFormFilledAndValid()) {
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);
        if ($this->userRepository->checkUserPassword($email, $password)) {
            $this->authenticationService->connectUser();
            if($this->userRepository->getIsAdmin($email))
                $this->authenticationService->setAdminUser();
            $this->redirectToHomepage();
        } else {
        $error = 'Adresse mail ou mot de passe incorrect';
        }
    } elseif ($this->isOneOfTheFieldsMissing()) {
      $error = 'Veuillez remplir tous les champs';
    }
    return buildLoginForm($error);
  }

  private function isLoginFormFilledAndValid(): bool
  {
    return isset($_POST['email'], $_POST['password']) && $_POST['email'] !== '' && $_POST['password'] !== '';
  }

  private function isOneOfTheFieldsMissing(): bool
  {
    return (isset($_POST['email']) && $_POST['email'] === '')
      || (isset($_POST['password']) && $_POST['password'] === '');
  }

  private function redirectToHomepage(): void {
    $_SESSION["pseudo"] = $_POST['email'];
    header('Location: /e_commerce_3/src/home/');
  }
}
