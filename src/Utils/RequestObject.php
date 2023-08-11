<?php

namespace Utils;


class RequestObject
{

  public function actionSet()
  {
    if(isset($_GET["action"])) {
      return true;
    }
  }
  public function selectionSet()
  {
    if(isset($_GET["selection"])) {
      return true;
    }
  }
  public function post()
  {
    if(isset($_POST) && !empty($_POST))
    {
      return $_POST;
    }
  }
  public function get()
  {
    if(isset($_GET) && !empty($_GET))
    {
      return $_GET;
    }
  }


  public function files()
  {
    if(isset($_FILES) && !empty($_FILES))
    {
      return $_FILES;
    }
  }
}