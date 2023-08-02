<?php

namespace Repository;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;

class CommentRepository
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }
 
}
