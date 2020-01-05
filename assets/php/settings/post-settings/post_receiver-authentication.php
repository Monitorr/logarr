<?php
if (isset($_POST) && !empty($_POST)) {
    include(__DIR__ . '/../../functions.php');
    include(__DIR__ . '/../../auth_check.php');
    $str = file_get_contents($config_file);

    $json = json_decode($str, true);
    $json['authentication'] = $_POST;

    file_put_contents($config_file, json_encode($json, JSON_PRETTY_PRINT));
    appendLog("Logarr Settings changed: Authentication");
}
