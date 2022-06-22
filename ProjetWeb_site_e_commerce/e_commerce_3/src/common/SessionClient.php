<?php
class SessionClient {
  private static $instance = null;

  private function __construct()
  {
    session_start();
  }

  public function set($key, $value): void {
    $_SESSION[$key] = $value;
  }

  public function get($key) {
    return $_SESSION[$key];
  }

  public function exists($key) {
    return isset($_SESSION[$key]);
  }

  public function delete($key) {
    unset($_SESSION[$key]);
  }

  public function deleteSession() {
      session_destroy();
  }

  public static function getInstance() {
    if(is_null(self::$instance)) {
      self::$instance = new SessionClient();
    }
    return self::$instance;
  }
}
