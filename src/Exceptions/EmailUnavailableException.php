<?php

namespace Exceptions;

use Exception;

class EmailUnavailableException extends Exception
{
  const EMAIL_UNAVAILABLE_MESSAGE_ERROR = "L'adresse e-mail";
}
