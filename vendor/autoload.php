<?php

$conf = require_once '/config/app.php';
define('BASEPATH', $conf['basePath']);
$customNamespace = $conf['classMap'];


spl_autoload_register(function ($className) {

    $classPath = BASEPATH . '\\' . $className . '.php';
  
    if(file_exists($classPath)) {
        require_once $classPath;
    }
});