<?php

include(__DIR__ . '/../../functions.php');
$str = file_get_contents($config_file);

$json = json_decode($str, true);
$json['logs'] = $_POST['data'];
file_put_contents($config_file, json_encode($json, JSON_PRETTY_PRINT));

