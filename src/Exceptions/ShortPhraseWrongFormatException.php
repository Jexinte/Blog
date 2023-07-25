<?php

namespace Exceptions;

use Exception;

class ShortPhraseWrongFormatException extends Exception
{
  const SHORT_PHRASE_MESSAGE_ERROR_MAX_500_CHARS = "Le chapô doit commencer par une majuscule et ne peut excéder 500 caractères !";
}
