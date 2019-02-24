<?php
include(__DIR__ . '/functions.php');
$configContents = json_decode(file_get_contents($config_file), 1);
echo json_encode($configContents);