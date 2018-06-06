<?php
include('functions.php');
$hash = $_POST['hash'];
$category = (isset($_POST['hash']) && !empty($_POST['hash'])) ? substr($_POST['hash'], 1) : '';
echo "<!--";
print_r($category);
echo "-->";
$categories = array();
$result = "<div id=\"logwrapper\" class=\"flex\">";
foreach ($logs as $log) {
    if(isset($log['category']) && !empty($log['category']) && !in_array(strtolower($log['category']), $categories)) array_push($categories, strtolower($log['category']));
    if(empty($category) || (!empty($category) && isset($log['category']) && strtolower($log['category']) == strtolower($category))){
        if($log['enabled'] == "Yes") {
            $result .= "
                <div id=\"".str_replace(" ", "-", $log['logTitle'])."-log-container\" class=\"flex-child\">
    
                    <div class=\"row2\">
    
                        <div id=\"filedate\" class=\"left\">
                            <br>
                            Last modified: ". date(" H:i | D, d M", filemtime( $log['path'] ))."
                        </div>
    
                        <div class=\"logheader\">
                            <strong>" . $log['logTitle'] . ":</strong>
                        </div>
    
                        <div id=\"filepath\" class=\"right\">
                            <div class=\"filesize\">
                                Log file size: ".human_filesize(filesize($log['path']))."
                            </div>
                            <div class=\"path\" data-service=\"" . $log['logTitle'] . "\">
                                " . $log['path'] . "
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
                               ".readExternalLog($log)."
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
$result .= "</div>";
$categoryNavigation = "<nav id='categoryFilter'>";
$categoryNavigation .= "<a href='#' class='category-filter-item'>All</a>";
foreach ($categories as $categoryLink) {
    $categoryNavigation .= "<a href='#$categoryLink' class='category-filter-item'>". ucfirst($categoryLink)."</a>";
}
$categoryNavigation .= "</nav>";
echo $categoryNavigation;
echo $result;
?>