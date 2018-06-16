<?php
include(__DIR__ . '/functions.php');
$configContents = json_decode(file_get_contents($config_file), 1);
/*
 * enable for syncing settings back to the file
 *
 * if(isset($_POST)){
    $configContents['config'] = $_POST['config'];
    $json = json_encode($configContents, JSON_PRETTY_PRINT);
    file_put_contents(__DIR__ . '/../config/config.json', $json);
}*/
echo json_encode($configContents);