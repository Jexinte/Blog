<?php

namespace Exceptions;

use Exception;

class EmailUnexistException extends Exception
{
  const EMAIL_UNEXIST_MESSAGE_ERROR = "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez";
}
