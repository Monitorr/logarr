<?php
include('functions.php');
include("auth_check.php");

// TODO : add function to stop log update if log is too large:
// ini_set('max_execution_time', 60);

$log = (isset($_POST['log']) && !empty($_POST['log'])) ? $_POST['log'] : '';
$parsedPath = parseLogPath($log['path']);
$category = isset($log['category']) ? $log['category'] : "";
$result = "

    <div id=\"" . $log['logTitle'] . "-row\" class=\"row2\">

        <div id=\"filedate\" class=\"left\">
            <p class='logdatatitle'>Category:</p> " . $category . "
            <br>
            <p id='logmaxlines'>Max Lines: " . $log['maxLines'] . "</p>
            <p class='logdatatitle'>Modified:</p> " . date(" H:i | D, d M", filemtime($parsedPath)) . "
        </div>

        <div class=\"logheader\">
            <strong>" . $log['logTitle'] . ":</strong>
        </div>

        <div id=\"filepath\" class=\"right\">
            <div class=\"filesize\">
                <p class='logdatatitle'>Log size:</p> " . human_filesize(filesize($parsedPath)) . "
            </div>
            <div class=\"path\" data-service=\"" . $log['logTitle'] . "\">
                " . $parsedPath . "
            </div>
        </div>

    </div>

    <div class=\"slide\">
        <input class=\"expandtoggle\" type=\"checkbox\" name=\"slidebox\" id=\"" . $log['logTitle'] . "\"
                checked>
        <label for=\"" . $log['logTitle'] . "\" class=\"expandtoggle toggle\"
                title=\"Increase/decrease log view\"></label>

        <div id=\"expand\" class=\"expand\">
            <p id=\"" . $log['logTitle'] . "-log\"> " . readExternalLog($log) . " </p>
        </div>
    </div>

    <div id=\"" . $log['logTitle'] . "-buttons\" class=\"log-buttons\">
        <button type=\"button\" id=\"" . $log['logTitle'] . "-unlinkBtn\" class=\"log-action-button slidebutton btn btn-primary\"
                data-action=\"unlink-log\" data-service=\"" . $log['logTitle'] . "\"
                title=\"Attempt log file roll. NOTE: This function will copy the current log file to '[logfilename].bak', delete the original log file, and create a new blank log file with the original log filename. This function may not succeed if log file is in use.\">
            Roll Log
        </button>
        <button type=\"button\" id=\"" . $log['logTitle'] . "-downloadBtn\" class=\"log-action-button download-button slidebutton btn btn-primary indexBtn logBtn\"
                data-action=\"download-log\" data-service=\"" . $log['logTitle'] . "\"
                title=\"Download full log file\">Download
        </button>
        <button type=\"button\" id=\"" . $log['logTitle'] . "-updateLogBtn\" class=\"log-action-button slidebutton btn btn-primary\"
                data-action=\"update-log\" data-index=\"" . $log['logTitle'] . "\"
                title=\"Update individual log\">Update
        </button>
    </div>

    ";

echo $result;

if (!readExternalLog($log)) {
    echo "<script>console.log('%cERROR: Log not found','color: #FF0000;');</script>";
    echo ('<div id="logmissing"> <i class="fas fa-exclamation-triangle"> </i> <p id="logmissingtxt">Log not found </p></div>');
    //Disable log buttons if log NOT found:
    echo ("<script>$('#" . $log['logTitle'] . "-unlinkBtn').prop('disabled', true);</script>");
    echo ("<script>$('#" . $log['logTitle'] . "-downloadBtn').prop('disabled', true);</script>");
    echo ("<script>$('#" . $log['logTitle'] . "-updateLogBtn').prop('disabled', true);</script>");
    echo "<script>logerror();</script>";
    appendLog(
        $logentry = "ERROR: Log not found: " . $log['logTitle']
    );
}


// //TODO / in development
    // if (human_filesize(filesize($parsedPath)) > 60) {
    //     echo "<script>console.log('%cWARNING: Log file size is large','color: #FF0000;');</script>";
    //     appendLog(
    //         $logentry = "WARNING: Log file size is large: " . $log['logTitle'] . ": " . human_filesize(filesize($parsedPath))
    //     );
    // } else {
    //     echo "<script>console.log('%cWARNING: Log file size is NORMAL','color: #FF0000;');</script>";

    //     appendLog(
    //         // $logentry = "WARNING: Log file size is NORMAL: " . filesize($log);
    //         $logentry = "WARNING: Log file size is NORMAL: " . human_filesize(filesize($parsedPath))
    //     );
// }
