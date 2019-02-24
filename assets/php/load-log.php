<?php
include('functions.php');
$log = (isset($_POST['log']) && !empty($_POST['log'])) ? $_POST['log'] : '';
$parsedPath = parseLogPath($log['path']);
$category = isset($log['category']) ? $log['category'] : "";
$result = "

        <div class=\"row2\">

            <div id=\"filedate\" class=\"left\">
            	 Category: " . $category . "
                <br>
                Last modified: " . date(" H:i | D, d M", filemtime($parsedPath)) . "
            </div>

            <div class=\"logheader\">
                <strong>" . $log['logTitle'] . ":</strong>
            </div>

            <div id=\"filepath\" class=\"right\">
                <div class=\"filesize\">
                    Log file size: " . human_filesize(filesize($parsedPath)) . "
                </div>
                <div class=\"path\" data-service=\"" . $log['logTitle'] . "\">
                    " . $parsedPath . "
                </div>
            </div>

        </div>

        <div class=\"slide\">
            <input class=\"expandtoggle\" type=\"checkbox\" name=\"slidebox\" id=\"" . $log['logTitle'] . "\"
                   checked>
            <label for=\"" . $log['logTitle'] . "\" class=\"expandtoggle\"
                   title=\"Increase/decrease log view\"></label>

            <div id=\"expand\" class=\"expand\">
                <p id=\"" . $log['logTitle'] . "-log\">
                   " . readExternalLog($log) . "
                </p>
            </div>

        </div>
		<div class='log-buttons'>
	       <button type=\"button\" class=\"log-action-button slidebutton btn btn-primary\"
	               data-action=\"unlink-log\" data-service=\"" . $log['logTitle'] . "\"
	               title=\"Attempt log file roll. NOTE: This function will copy the current log file to '[logfilename].bak', delete the original log file, and create a new blank log file with the orginal log filename. This function may not succeed if log file is in use.\">
	           Roll Log
	       </button>
	       <button type=\"button\" class=\"log-action-button slidebutton btn btn-primary\"
	               data-action=\"download-log\" data-service=\"" . $log['logTitle'] . "\"
	               title=\"Download full log file\">Download
	       </button>
	       <button type=\"button\" class=\"log-action-button slidebutton btn btn-primary\"
	               data-action=\"update-log\" data-index=\"" . $log['logTitle'] . "\"
	               title=\"Update individual log\">Update
	       </button>
       </div>";
echo $result;