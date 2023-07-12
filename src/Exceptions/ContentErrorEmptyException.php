<?php

namespace Exceptions;

use Exception;

class ContentErrorEmptyException extends Exception{    
  const CONTENT_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
}