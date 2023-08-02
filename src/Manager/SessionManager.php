<?php

namespace Manager;


class SessionManager
{
  private array $session;
  private string $cookieId;

  public function __construct()
  {
  }
  public function startSession(): void
  {

    if (session_status() != PHP_SESSION_ACTIVE) {
      session_start();
      $this->session = &$_SESSION;
    } 

  }

  public function destroySession(): void
  {

    if (session_status() === PHP_SESSION_ACTIVE) {
      session_unset();
      session_destroy();
    }
  }

  public function getIdInCookie():string
  {
    $this->cookieId = $_COOKIE["PHPSESSID"];
    return $this->cookieId;
  }


  public function initializeKeyAndValue(string $key, string|null|int $value): void
  {
    $this->session[$key] = $value;
  }

  public function getSessionData(): array
  {
    return $this->session;
  }
}
