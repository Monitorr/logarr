<?php
include('functions.php');
include("auth_check.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
$date = date("D d M Y H:i T ");

set_error_handler("LogarrErrorHandler");

$file = $_GET['file'];

//TODO / BUG / Browser will hang after dowloading log during next log update using Chrome or IE Edge on mobile device:

// check if log file exists in config.json:

if (in_array_recursive($file, $logs)) {
    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        //ob_clean();
        flush();
        readfile($file);
        appendLog(
            $logentry = "Downloading log file: " . $file
        );
        exit;
    } else {
        echo 'ERROR: file: ' . $file . ' does not exist.';

        echo "<script>console.log('%cERROR: Log file does not exist: ', 'color: red;');</script>";
        echo "<script>console.log('%c" . $file . "', 'color: red;');</script>";

        phpLog($phpLogMessage = "Logarr ERROR: Downloading log file: " . $file . " does NOT exist");

        appendLog(
            $logentry = "ERROR: Downloading log file: " . $file . " does NOT exist"
        );
    }
} // Deny access if log file does NOT exist in config.json:

else {
    echo 'ERROR: Illegal File';

    echo "<script>console.log('%cERROR: Illegal file', 'color: red;');</script>";

    phpLog($phpLogMessage = "Logarr ERROR: Downloading log file: Illegal File");

    appendLog(
        $logentry = "ERROR: Downloading log file: Illegal File"
    );
}
