<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', 'http://');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

/**
 * Configuration for: Database
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', 'mysql');
define('DB_NAME', 'expense');
define('DB_USER', 'root');
define('DB_PASS', 'admin');
define('DB_CHARSET', 'utf8');
