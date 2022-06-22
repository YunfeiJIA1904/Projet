<?php
include 'SessionClient.php';

class AuthenticationService
{
  private $sessionClient;
  private $IS_CONNECTED_KEY = 'isConnected';
  private $IS_ADMIN_KEY = 'isAdmin';

  public function __construct()
  {
    $this->sessionClient = SessionClient::getInstance();
  }

  public function isUserConnected(): bool
  {
    return $this->sessionClient->exists($this->IS_CONNECTED_KEY) && $this->sessionClient->get($this->IS_CONNECTED_KEY);
  }

  public function isUserAdmin(): bool
  {
      return $this->sessionClient->exists($this->IS_CONNECTED_KEY) && $this->sessionClient->get($this->IS_CONNECTED_KEY)
          && $this->sessionClient->exists($this->IS_ADMIN_KEY) && ($this->sessionClient->get($this->IS_ADMIN_KEY) == true);
  }

  public function connectUser(): void
  {
    $this->sessionClient->set($this->IS_CONNECTED_KEY, true);
  }

  public function setAdminUser(): void
  {
      $this->sessionClient->set($this->IS_ADMIN_KEY, true);
  }

  public function logoutUser(): void
  {
    $this->sessionClient->deleteSession();
  }
}
