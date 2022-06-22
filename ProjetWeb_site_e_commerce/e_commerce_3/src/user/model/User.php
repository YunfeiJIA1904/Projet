<?php

class User {
  private $firstName;
  private $lastName;
  private $username;

  public function __construct($firstName, $lastName, $username)
  {
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->username = $username;
  }
}
