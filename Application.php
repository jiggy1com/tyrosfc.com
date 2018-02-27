<?php

// phpmailer
include_once('phpmailer/PHPMailerAutoload.php');

// load controllers and models
include('./load/index.php');

// create app
$init = new ApplicationController();
//$app->isLoggedIn = isset($_COOKIE['isLoggedIn']) && $_COOKIE['isLoggedIn'] === true ? true : false;

// set uri used to grab route element
if( isset($_REQUEST['path']) ){
    $uri = '/' . $_REQUEST['path']; // "test/route"
}else{
    $uri = '/';
}

// remove trailing slash (that's how my router works!)
if( strlen($uri) > 1){
    if( substr($uri, -1) === '/'){
        $uri = substr($uri, 0, strlen($uri)-1);
    }
}

$app = $init->setNextRoute($uri);
//$app = $init->setNextRoute($_SERVER['REQUEST_URI']);

?>