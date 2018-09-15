<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Development Environment **/

// when Set to false, no error will be throw out, but saved in temp/log.txt file.

define('DEVELOPMENT_ENVIRONMENT',true);

/** Site Root **/

// Domain name of the site (no slash at the end!)
// define('SITE_ROOT' , 'http://You domain name');

define('SITE_ROOT', 'http://hyper.local');

/** SEO friendly URL's **/

define('SEO_URL', true);

/** Database credentials **/

define('DATABASE_SERVER', 'localhost');
define('DATABASE_USER', 'root');
define('DATABASE_PASSWORD', 'C0mputer3');
define('DATABASE_NAME', 'hyper');
define('TABLE_PREFIX', '');

/** Set mail protocal and/or SMTP credentials **/

/**
* Default is set to PHP mail function.
* Change 'mail' to 'SMTP' and add your mail server settings to SMTP_HOST, SMTP_USER, SMTP_PASS, SMTP_PORT
**/

define('MAIL_PROTOCOL', 'mail');
define('SMTP_HOST', '');
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_PORT', '25');

/** Fall back to system controller. Change to your custom controller when application is ready**/

define('DEFAULT_CONTROLLER', "hello_hyper");
define('DEFAULT_ACTION', "index");

/** Auto load helpers **/

// You can find a list of available helpers in the Hyper documentation.
// List helpers to autoload separated by comma. i.e.('url', 'string', 'file').

define('LOAD_HELPER', 'url');

/** Auto load library's **/

// You can find a list of available library's in the Hyper documentation.
// List library's to autoload separated by comma. i.e.('database', 'mail', 'curl').

define('LOAD_LIBRARY', 'database');

/** Cache view files for better performance **/

define('CACHE_FILE', true);
define('CACHE_EXPIRATION', 0);

/** Application folder **/

// Recommended this stays the way this is unless you know what your doing.

define('APP', 'application');