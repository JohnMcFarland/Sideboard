<?php
  // PRIVACY
  // privacy flags. this will determine what we're allowed to view
  define("PRIVACY_SELF",            1 << 0);
  define("PRIVACY_FRIENDS",         1 << 1);
  define("PRIVACY_FRIEND_FRIENDS",  1 << 2);
  define("PRIVACY_PUBLIC",          1 << 3);
  define("PRIVACY_CUSTOM",          1 << 4);
  define("PRIVACY_DEFAULT",         15);  // SELF | FRIENDS | FRIEND_FRIENDS | PUBLIC

  // LOGIN
  // define("ERROR_LOGIN_EMAIL_NO_EXIST",    1 << 0);  // 00000001
  // define("ERROR_LOGIN_PASSWORD_WRONG",    1 << 1);  // 00000010

  // SIGNUP
  // define("ERROR_SIGNUP_USERNAME_BAD_LENGTH",  1 << 0);
  // define("ERROR_SIGNUP_USERNAME_NOT_UNIQUE",  1 << 1);
  // define("ERROR_SIGNUP_EMAIL_NOT_UNIQUE",     1 << 2);
  // define("ERROR_SIGNUP_EMAIL_INCORRECT",      1 << 3);
  // define("ERROR_SIGNUP_BIRTHDAY_INVALID",     1 << 4);
  // define("ERROR_SIGNUP_EMAIL_NO_EXIST",       1 << 5);
  // define("ERROR_SIGNUP_USERNAME_NO_EXIST",    1 << 6);
  // define("ERROR_SIGNUP_FIRSTNAME_NO_EXIST",   1 << 7);
  // define("ERROR_SIGNUP_LASTNAME_NO_EXIST",    1 << 8);
  // define("ERROR_SIGNUP_BIRTHDAY_NO_EXIST",    1 << 9);
  // define("ERROR_SIGNUP_PASSWORD_INCORRECT",   1 << 10);

  // USERNAME
  // define("ERROR_USERNAME_LENGTH_INCORRECT",   1 << 0);
  // define("ERROR_USERNAME_NO_USERNAME",        1 << 1);

  // PASSWORD
  // define("ERROR_PASSWORD_LENGTH_INCORRECT",   1 << 0);
  // define("ERROR_PASSWORD_NO_CAPITAL",         1 << 1);
  // define("ERROR_PASSWORD_NO_NUMBER",          1 << 2);
  // define("ERROR_USERNAME_NO_PASSWORD",        1 << 3);

  // CHANGE PASSWORD
  // define("ERROR_CHANGE_PASSWORD_SAME",                1 << 0);
  // define("ERROR_CHANGE_PASSWORD_CURRENT_INCORRECT",   1 << 1);
  // define("ERROR_CHANGE_PASSWORD_NO_NEW_PASSWORD",     1 << 2);
  // define("ERROR_CHANGE_PASSWORD_NO_CURRENT_PASSWORD", 1 << 3);


?>
