<?php

namespace Exceptions;

use Exception;
class FormMessageNotSentException extends Exception{

  const MESSAGE_SENT_FAILED = "Votre message n'a pu être envoyé , veuillez réessayez plus tard !";
}