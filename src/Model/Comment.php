<?php

namespace Model;

use Config\DatabaseConnection;

class Comment
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }
}
