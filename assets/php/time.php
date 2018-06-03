<?php
    include('functions.php');
    $timezone = $preferences['timezone'];
    $dt = new DateTime("now", new DateTimeZone("$timezone"));
    $timeStandard = (int) ($preferences['timestandard'] === "True" ? true:false);
    $rftime = $settings['rftime'];
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