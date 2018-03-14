<?php
error_reporting(E_ALL);
date_default_timezone_set('US/Pacific');
ini_set('display_errors', 'On');

// start session for session vars (logged in state, etc)
session_start();
?>
<?php include('./Application.php') ?>
<?php include('./view/layout/' . $GLOBALS['app']->layout . '.php'); ?>