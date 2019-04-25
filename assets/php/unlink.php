<?php
include('functions.php');
include("auth_check.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

set_error_handler("LogarrErrorHandler");

$file = ($_POST['file']);
unlinkLog($file, true);