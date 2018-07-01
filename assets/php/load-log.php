<?php
include('functions.php');
$hash = $_POST['hash'];
$category = (isset($_POST['hash']) && !empty($_POST['hash'])) ? substr($_POST['hash'], 1) : '';
echo "<!--";
print_r($category);
echo "-->";
$categories = array();
$result = "<div id='logwrapper' class='flex' style='display: none;'>";
$rolledLogs = "";
foreach ($logs as $log) {
    if (isset($log['category']) && !empty($log['category']) && !in_array(strtolower($log['category']), $categories)) array_push($categories, strtolower($log['category']));
    $parsedPath = parseLogPath($log['path']);
	if (!startsWith($parsedPath, 'Error') && (empty($category) || (!empty($category) && isset($log['category']) && strtolower($log['category']) == strtolower($category)))) {

        //auto role check
        if (isset($log['autoRollSize']) && $log['autoRollSize'] != 0) { //check if it should be checked
            if (file_exists($parsedPath) && filesize($parsedPath) > convertToBytes($log['autoRollSize'])) {
                $rolledLogs .= $log['logTitle'] . ', ';
                unlinkLog($parsedPath, false);
            }
        }

        //showing the log
        if ($log['enabled'] == "Yes") {
            $result .= "
                <div id=\"" . str_replace(" ", "-", $log['logTitle']) . "-log-container\" class=\"flex-child\">
    
                    <div class=\"row2\">
    
                        <div id=\"filedate\" class=\"left\">
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
    
                    <table id=\"slidebottom\">
                        <tr>
                            <td id=\"unlinkform\">
                                <button type=\"button\" class=\"log-action-button slidebutton btn btn-primary\"
                                        data-action=\"unlink-log\" data-service=\"" . $log['logTitle'] . "\"
                                        title=\"Attempt log file roll. NOTE: This function will copy the current log file to '[logfilename].bak', delete the original log file, and create a new blank log file with the orginal log filename. This function may not succeed if log file is in use.\">
                                    Roll Log
                                </button>
                            </td>
                            <td id=\"downloadform\">
                                <button type=\"button\" class=\"log-action-button slidebutton btn btn-primary\"
                                        data-action=\"download-log\" data-service=\"" . $log['logTitle'] . "\"
                                        title=\"Download full log file\">Download
                                </button>
                            </td>
                        </tr>
                    </table>
    
                </div>";
        }
    }
}
$notifications = '<script>
                    
                    console.log("Automatically rolled the following logs: ' . substr($rolledLogs, 0, -2) . '");

                    setTimeout(function () {
                        $("#modalContent").html(
                            "Automatically rolled the following logs:<br><br>" + 
                            "' . str_replace(", ", "<br>", $rolledLogs) . '"
                        );

                        var modal = $("#responseModal");
                        var span = $(".closemodal");
                        modal.fadeIn("slow"); //fadein
                        
                        span.click(function() { //fadeout when close button is clicked
                            modal.fadeOut("slow");
                        });
                        $(body).click(function(event) { // fade out when clicked outside the modal
                            if (event.target != modal) {
                                modal.fadeOut("slow");
                            }
                        });
                        setTimeout(function () { //auto fade out after 3 seconds
                            modal.fadeOut("slow");
                        }, 3000);
                    
                    },500); // delay for fade in
                    </script>';

$result .= "</div>";
$categoryNavigation = "<nav id='categoryFilter'>";
$categoryNavigation .= "<a href='#' class='category-filter-item'>All</a>";
sort($categories);
foreach ($categories as $categoryLink) {
    $categoryNavigation .= "<a href='#$categoryLink' class='category-filter-item'>" . ucfirst($categoryLink) . "</a>";
}
$categoryNavigation .= "</nav>";
echo $categoryNavigation;
echo $result;
if (!empty($rolledLogs)) echo $notifications;
