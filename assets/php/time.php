<?php
    include ('../config/config.php');
    $timezone = $config['timezone'];
    $dt = new DateTime("now", new DateTimeZone("$timezone"));
    $timeStandard = (int) ($config['timestandard']);
    $rftime = $config['rftime'];
    $timezone_suffix = '';
    if(!$timeStandard){
        $dateTime = new DateTime();
        $dateTime->setTimeZone(new DateTimeZone($timezone));
        $timezone_suffix = $dateTime->format('T');
    }
    $serverTime = $dt->format("D d M Y H:i:s");
    $response = array(
        'serverTime' => $serverTime,
        'timeStandard' => $timeStandard,
        'timezoneSuffix' => $timezone_suffix,
        'rftime' => $rftime
    );
    echo json_encode($response);
?>