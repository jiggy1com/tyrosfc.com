<?php

// get the current working directory

$dir = getcwd();

// load config, required for mysql (and possibly other settings)
require($dir . '/config.php');

// load models

$modelDir = $dir . '/model/';
$modelSort 	= defined('SCANDIR_SORT_ASCENDING') ? SCANDIR_SORT_ASCENDING : 2;
$modelFiles = scandir($modelDir, $modelSort);
foreach($modelFiles as $file){
    if($file !== '.' && $file !== '..'){
        require($modelDir . $file);
    }
}

// load controllers

$controllerDir = $dir . '/controller/';
$controllerSort 	= defined('SCANDIR_SORT_ASCENDING') ? SCANDIR_SORT_ASCENDING : 2;
$controllerFiles = scandir($controllerDir, $controllerSort);
foreach($controllerFiles as $file){
    if($file !== '.' && $file !== '..'){
        require($controllerDir . $file);
    }
}

