<?php

namespace Exceptions;

use Exception;

class CommentEmptyException extends Exception
{
  const COMMENT_EMPTY_EXCEPTION = "Ce champ ne peut être vide !";
}
