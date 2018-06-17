<?php

include(__DIR__ . '/../../functions.php');
$str = file_get_contents($config_file);

$json = json_decode($str, true);

$preferences = $json['settings'];

$return = json_encode($preferences, true);

echo $return;

