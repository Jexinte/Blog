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

  const FIRSTNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT = "La première lettre de votre prénom doit être en majuscule et ne doit pas contenir de caractères spéciaux tels que les éléments suivant : ?,!, etc... ";

  const LASTNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const LASTNAME_MESSAGE_ERROR_WRONG_FORMAT = "La première lettre de votre nom doit être en majuscule et ne doit pas contenir de caractères spéciaux tels que les éléments suivant : ?,!, etc... ";

  const CONTENT_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const CONTENT_MESSAGE_ERROR_LESS_THAN_50 = "Le contenu de votre message doit être supérieur à 50 caractères !";
  const CONTENT_MESSAGE_ERROR_ABOVE_THAN_500 = "Le contenu de votre message ne peut excéder 500 caractères !";
  const CONTENT_MESSAGE_SUCCESS = "Votre message a bien été envoyé !";

  const SUBJECT_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const SUBJECT_MESSAGE_ERROR_LESS_THAN_20 = "Le contenu de l'objet doit être supérieur à 20 caractères !";
  const SUBJECT_MESSAGE_ERROR_ABOVE_THAN_100 = "Le contenu de l'objet ne peut excéder 100 caractères !";

  const FORM_MESSAGE_ERROR_DELIVERY_FAILED = "Votre message n'a pu être envoyé ! Merci de réessayez plus tard !";
}
