<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);       //DS is path separator "/"

defined('SITE_ROOT') ? null : define('SITE_ROOT', DS . 'var' . DS . 'www' . DS . 'html' . DS . 'gallery');     //SITE_ROOT - path to main file

defined('INCLUDES_PATH') ? null : define('INCLUDES_PATH', SITE_ROOT.DS.'admin'.DS.'includes');      //INCLUDES_PATH - path to includes

require_once ("functions.php");
require_once ("config.php");
require_once ("database.php");
require_once ("db_object.php");
require_once ("User.php");
require_once("Photo.php");
require_once ("session.php");
//require_once ("Comment.php");

