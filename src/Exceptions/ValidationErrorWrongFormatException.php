<?php

namespace Exceptions;

use Exception;

class ValidationErrorWrongFormatException extends Exception
{
  const VALIDATION_MESSAGE_ERROR_WRONG_FORMAT = "L'explication doit commencer par une lettre majuscule et ne peut excéder 500 caractères";
}
