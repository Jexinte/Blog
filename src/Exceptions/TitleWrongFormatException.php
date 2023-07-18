<?php

namespace Exceptions;

use Exception;

class TitleWrongFormatException extends Exception
{
  const TITLE_MESSAGE_ERROR_MAX_500_CHARS = "Le titre doit commencer par une majuscule et ne peut excéder 500 caractères !";
}
