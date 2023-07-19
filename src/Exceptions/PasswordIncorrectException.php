<?php

namespace Exceptions;

use Exception;

class PasswordIncorrectException extends Exception
{
  const PASSWORD_INCORRECT_MESSAGE_ERROR = "Le mot de passe est incorrect !";
}
