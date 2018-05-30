<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $file = '../config/config.php';
    //Use the function is_file to check if the config file already exists or not.
    if(!is_file($file)){
        copy('../config/config.sample-12feb18.php', $file);
    }

    include ('../config/config.php');

    $file = $_GET['file'];
    if(in_array($file, $logs)){ //otherwise everyone will have access to more files than you should want
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            //ob_clean();
            flush();
            readfile($file);
            exit;
        } else {
            echo 'file: ' . $file . ' does not exist.';
        }
    } else {
        echo 'Illegal File';
    }


?>