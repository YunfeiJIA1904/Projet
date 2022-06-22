<?php

interface UserRepository
{
  public function checkUserExistence(string $email, string $password): bool;

  public function getUserByEmail(string $email) : ?User;

    public function getIsAdmin(string $email) : bool;

  public function createUser($firstName, $lastName, $email, $password): void;
}
