<?php
/**
 * Created by PhpStorm.
 * User: maciek
 * Date: 29.12.17
 * Time: 15:43
 */

function classAutoloader($class) {

    $the_path = INCLUDES_PATH . DS . "{$class}.php";

    if(is_file($the_path) && !class_exists($class)) {                            //jeśli istnieje plik o takiej nazwie
        include($the_path);
    } else {
        die("This file named {$class}.php was not found.");
    }

}

function redirect($location) {
    header("Location: {$location}");
}

spl_autoload_register('classAutoloader');