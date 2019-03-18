<?php
ini_set('error_reporting', E_WARNING);

include('functions.php');

$logfile = 'logarr.log';
$logdir = __DIR__ . '/../data/logs/';
$logpath = $logdir . $logfile;
$somecontent = "This is a Logarr logging TEST";
$date = date("D d M Y H:i T ");

echo "logpath:" . $logpath;
echo "<br>";
echo "logdir:" . $logdir;
echo "<br>";

if (!$handle = fopen($logpath, 'a+')) {
    echo "Cannot open file ($logpath)";
    echo "<script>console.log('ERROR: Cannot open file ($logfile)');</script>";
    exit;
}

if (fwrite($handle, $date . " | " . $somecontent . "\r\n") === false) {

    echo "Cannot write to file ($logpath)";
    echo "<script>console.log('ERROR: Cannot write to file $logfile');</script>";
    exit;

} else {

    if (is_writable($logpath)) {

        echo "Success, wrote: ($somecontent) to file ($logpath)";
        echo "<script>console.log('Success, wrote: $somecontent to file: $logfile');</script>";

        fclose($handle);

    } else {
        echo "The file $logpath is not writable";
        echo "<script>console.log('ERROR: The file $logfile is not writable');</script>";
    }

}