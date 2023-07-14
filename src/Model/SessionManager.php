<?php 

namespace Model;

class SessionManager{

  public function startSession(){
    if(session_status() !== PHP_SESSION_ACTIVE ) session_start();
  }

  public function sessionAdminExist(){
    if(session_status() === PHP_SESSION_ACTIVE) return ["session_active" => 1];
  }
  public function destroySession(){
    
    if(session_status() === PHP_SESSION_ACTIVE ) {
      session_destroy();
    }
  }
}