<?php

namespace Exceptions;

use Exception;

class InvalidFieldException extends Exception
{
  const USERNAME_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Merci de suivre le format ci-dessous pour votre nom d'utilisateur !";
  const FILE_MESSAGE_ERROR_TYPE_FILE = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !";
  const EMAIL_MESSAGE_ERROR_WRONG_FORMAT = "Oops! Le format de votre saisie est incorrect, merci de suivre le format requis : nomadressemail@domaine.extension";

  const PASSWORD_MESSAGE_ERROR_WRONG_FORMAT = "Oops! Le format de votre mot de passe est incorrect, merci de suivre le format ci-dessous :";
  const FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT = "La première lettre de votre prénom doit être en majuscule et ne doit pas contenir de caractères spéciaux tels que les éléments suivant : ?,!, etc... ";

  const LASTNAME_MESSAGE_ERROR_WRONG_FORMAT = "La première lettre de votre nom doit être en majuscule et ne doit pas contenir de caractères spéciaux tels que les éléments suivant : ?,!, etc... ";
  const CONTENT_MESSAGE_ERROR_LESS_THAN_50 = "Le contenu de votre message doit être supérieur à 50 caractères !";
  const CONTENT_MESSAGE_ERROR_ABOVE_THAN_500 = "Le contenu de votre message ne peut excéder 500 caractères !";

  const CONTENT_MESSAGE_ERROR_MIN_50_CHARS_MAX_500_CHARS = "Le texte doit contenir minimum 50 caractères et ne peut en excéder 500 ";
  const SUBJECT_MESSAGE_ERROR_MIN_20_CHARS_MAX_100_CHARS = "Le texte doit contenir minimum 20 caractères et ne peut en excéder 100 !";
  const FORM_MESSAGE_ERROR_DELIVERY_FAILED = "Votre message n'a pu être envoyé ! Merci de réessayez plus tard !";
}
