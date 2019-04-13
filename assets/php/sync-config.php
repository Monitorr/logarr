<?php
include(__DIR__ . '/functions.php');
include("auth_check.php");
$configContents = json_decode(file_get_contents($config_file), 1);
echo json_encode($configContents);
isMissingKeyslog();
isMissingPrefslog();