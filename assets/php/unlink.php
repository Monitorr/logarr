<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('functions.php');

$file = ($_POST['file']);
unlinkLog($file, true);

?>