<?php

namespace Exceptions;

use Exception;

class EmptyFieldException extends Exception
{
  const USERNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut-être vide !";
  const EMAIL_MESSAGE_ERROR_EMPTY = "Ce champ ne peut-être vide !";
  const PASSWORD_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const FIRSTNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const LASTNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const CONTENT_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const SUBJECT_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const FILE_MESSAGE_ERROR_NO_FILE_SELECTED = "Veuillez sélectionner un fichier !";
}
