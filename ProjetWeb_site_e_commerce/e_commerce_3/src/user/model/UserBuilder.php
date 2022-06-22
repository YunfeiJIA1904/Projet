<?php

class UserBuilder
{
  private $firstName = '';
  private $lastName = '';
  private $username = '';

  public function withFirstName(string $firstName): UserBuilder
  {
    $this->firstName = $firstName;
    return $this;
  }

  public function withLastName(string $lastName): UserBuilder
  {
    $this->lastName = $lastName;
    return $this;
  }

  public function withUsername(string $username): UserBuilder
  {
    $this->username = $username;
    return $this;
  }

  public function build(): User
  {
    return new User($this->firstName, $this->lastName, $this->username);
  }
}
