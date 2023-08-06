<?php

namespace Enumeration;

enum Regex: string
{
  const USERNAME = "/^[A-Z][A-Za-z\d]{2,10}$/";

  const PASSWORD ="/^(?=.*[A-Z])(?=.*\d).{8,}$/";
  const TITLE = "/^(?=.{1,255}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ, .'-]*$/u";

  const SHORT_PHRASE = "/^(?=.{1,255}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ, .'-]*$/u";

  const CONTENT_ARTICLE = "/^(?=.{1,5000}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ, .'-]*$/u";
  const TAGS = "/^#([\p{L} '-]{1,20})\s#([\p{L} '-]{1,20})\s#([\p{L} '-]{1,20})$/";

  const FIRSTNAME = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
  const LASTNAME = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";

  const EMAIL = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

  const SUBJECT = "/^.{1,100}$/";

  const FORM_MESSAGE = "/^.{1,500}$/";

  const COMMENT = "/^[A-ZÀ-ÿ][A-ZÀ-ÿa-zÀ-ÿ0-9\s\-\_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]{0,498}[A-ZÀ-ÿa-zÀ-ÿ0-9\s\-\_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]$/";

  const FEEDBACK = "/^[A-ZÀ-ÿa-zÀ-ÿ0-9\s\-_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]{0,500}$/";
}
