<?php

namespace Exceptions;

use Exception;

class UserException extends Exception
{
  const USERNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut-être vide !";
  const USERNAME_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Merci de suivre le format ci-dessous pour votre nom d'utilisateur !";

  const FILE_MESSAGE_ERROR_TYPE_FILE = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !";
  const FILE_MESSAGE_ERROR_NO_FILE_SELECTED = "Veuillez sélectionner un fichier !";

  const EMAIL_MESSAGE_ERROR_EMPTY = "Ce champ ne peut-être vide !";
  const EMAIL_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Le format de votre saisie est incorrect. Merci de suivre le format requis : nomadressemail@domaine.extension";


  const PASSWORD_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const PASSWORD_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Le format de votre mot de passe est incorrect. Merci de suivre le format ci-dessous";
}
