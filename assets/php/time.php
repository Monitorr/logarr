<?php
include('functions.php');
if (!$GLOBALS['preferences']['timezone']) {
    date_default_timezone_set('UTC');
    $timezone = date_default_timezone_get();
    appendLog("ERROR: Logarr could not load timezone values. Default values have been set");
} else {
    $timezone = $GLOBALS['preferences']['timezone'];
}

$dt = new DateTime("now", new DateTimeZone("$timezone"));
$timeStandard = (int) ($GLOBALS['preferences']['timestandard'] === "True" ? true : false);
$rftime = $GLOBALS['settings']['rftime'];
$timezone_suffix = '';
if (!$timeStandard) {
    $dateTime = new DateTime();
    $dateTime->setTimeZone(new DateTimeZone($timezone));
    $timezone_suffix = $dateTime->format('T');
    //appendLog("ERROR: Logarr could not load timestandard values. Default values have been set");
}
$serverTime = $dt->format("D d M Y H:i:s");
$response = array(
    'serverTime' => $serverTime,
    'timeStandard' => $timeStandard,
    'timezoneSuffix' => $timezone_suffix,
    'rftime' => $rftime
);
echo json_encode($response);
