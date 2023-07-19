<?php 

namespace Exceptions;

use Exception;
class CommentWrongFormatException extends Exception {
const COMMENT_WRONG_FORMAT_EXCEPTION = "Un commentaire doit commencer par une lettre majuscule et ne peut excéder 500 caractères";
}