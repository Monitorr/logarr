<?php
include(__DIR__ . '/functions.php');
// CHANGE ME / TODO:  Does authcheck need to be here?
//include("auth_check.php");

$configContents = json_decode(file_get_contents($config_file), 1);
echo json_encode($configContents);
