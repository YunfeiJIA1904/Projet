<?php
require_once __DIR__ . '/../view/buildSigninForm.php';

class UserSigninController
{
  private $authenticationService;
  private $userRepository;

  public function __construct(AuthenticationService $authenticationService, UserRepository $userRepository)
  {
    $this->authenticationService = $authenticationService;
    $this->userRepository = $userRepository;
  }

  public function signinAction(): string {
    $error = '';
    $values = [
      'firstName' => '',
      'lastName' => '',
      'email' => ''
    ];

    if ($this->authenticationService->isUserConnected()) {
      $this->redirectToHomepage();
    }
    if ($this->isSigninFormFilledAndValid()) {
      $firstName = htmlspecialchars(ucfirst(strtolower($_POST['firstName'])));
      $lastName = htmlspecialchars(ucfirst(strtolower($_POST['lastName'])));
      $email = htmlspecialchars(strtolower($_POST['email']));
      $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_BCRYPT);
      if (is_null($this->userRepository->getUserByEmail($email))) {
        $this->userRepository->createUser($firstName, $lastName, $email, $password);
        $this->authenticationService->connectUser();
        $this->redirectToHomepage();
        $_SESSION["pseudo"]=$_POST['email'];
      } else {
        $values['firstName'] = $firstName;
        $values['lastName'] = $lastName;
        $values['email'] = $email;
        $error = 'Cette adresse mail est déjà utilisée';
      }
    } elseif ($this->isOneOfTheFieldsMissing()) {
      $values['firstName'] = htmlspecialchars($_POST['firstName']);
      $values['lastName'] = htmlspecialchars($_POST['lastName']);
      $values['email'] = htmlspecialchars($_POST['email']);
      $error = 'Veuillez remplir tous les champs';
    }

    return buildSigninForm($values, $error);
  }

  private function isSigninFormFilledAndValid(): bool
  {
    return isset($_POST['firstName'], $_POST['lastName'], $_POST['email'], $_POST['password'])
      && $_POST['firstName'] !== ''
      && $_POST['lastName'] !== ''
      && $_POST['email'] !== ''
      && $_POST['password'] !== '';
  }

  private function isOneOfTheFieldsMissing(): bool
  {
    return (isset($_POST['firstName']) && $_POST['firstName'] === '')
      || (isset($_POST['lastName']) && $_POST['lastName'] === '')
      || (isset($_POST['email']) && $_POST['email'] === '')
      || (isset($_POST['password']) && $_POST['password'] === '');
  }

  private function redirectToHomepage(): void {
    header('Location: /e_commerce_3/src/home/');
  }
}
