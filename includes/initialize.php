<?php
defined('DS') ? null: define('DS', DIRECTORY_SEPARATOR);
defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:'.DS.'xampp'.DS.'htdocs'.DS.'photo_gallery');
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once LIB_PATH.DS.'config.php';
// load functions next so that everything after that can use them
require_once LIB_PATH.DS.'functions.php';
// load core objects
require_once LIB_PATH.DS.'session.php';
require_once LIB_PATH.DS.'database.php';
require_once LIB_PATH.DS.'database_object.php';
require_once LIB_PATH.DS.'pagination.php';
// load database-related classes
require_once LIB_PATH.DS.'user.php';
require_once LIB_PATH.DS.'photograph.php';
require_once LIB_PATH.DS.'comment.php';

require_once LIB_PATH.DS.'phpmailer'.DS.'PHPMailerAutoload.php';

