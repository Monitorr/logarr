<?php
    $config = json_decode(file_get_contents(__DIR__ . '/../config/config.json'),1);
    if(isset($_POST)){
        $config['config'] = $_POST['config'];
        $json = json_encode($config, JSON_PRETTY_PRINT);
        file_put_contents(__DIR__ . '/../config/config.json', $json);
    }
    echo json_encode($config['config']);