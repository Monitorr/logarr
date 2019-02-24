<?php
include(__DIR__ . '/../../functions.php');
include(__DIR__ . '/../../auth_check.php');

$str = file_get_contents($config_file);

$json = json_decode($str, true);

$preferences = $json['authentication'];

$return = json_encode($preferences, true);

echo $return;

