<?php

// Data Dir
$authentication = json_decode(file_get_contents(__DIR__ . '../../data/datadir.json'), 1);
$datadir = $authentication['datadir'];


$config_file = $datadir . '/config.json';
$preferences = json_decode(file_get_contents($config_file), 1)['preferences'];
$settings = json_decode(file_get_contents($config_file), 1)['settings'];
$logs = json_decode(file_get_contents($config_file), 1)['logs'];



// New version download information

$branch = $preferences['updateBranch'];

// location to download new version zip
$remote_file_url = 'https://github.com/Monitorr/logarr/zipball/' . $branch . '';
// rename version location/name
$local_file = '../../tmp/monitorr-' . $branch . '.zip'; #example: version/new-version.zip
//
// version check information
//
// url to external verification of version number as a .TXT file
$ext_version_loc = 'https://raw.githubusercontent.com/Monitorr/logarr/' . $branch . '/assets/js/version/version.txt';
// users local version number
// added the 'uid' just to show that you can verify from an external server the
// users information. But it can be replaced with something more simple
$vnum_loc = "../js/version/version.txt"; #example: version/vnum_1.txt

if ($GLOBALS['preferences']['timezone'] == "") {
    date_default_timezone_set('UTC');
    $timezone = date_default_timezone_get();
} else {
    $timezoneconfig = $GLOBALS['preferences']['timezone'];
    date_default_timezone_set($timezoneconfig);
    $timezone = date_default_timezone_get();
}

function configExists() {
	return is_file($GLOBALS['config_file']);
}

function parseLogPath($path)
{
    if (substr_count($path, '*') == 1) { //check to see if the path contains only 1 *
        $dir = dirname($path); //store the dir so we can merge it with the filename in the end
        $filename_pattern = explode(DIRECTORY_SEPARATOR, $path); //split the path so we can get the filename
        $filename_pattern = end($filename_pattern); //get the filename, without the path
        if (substr_count($filename_pattern, '*') == 1) { //check again to see if the * is not in the path itself, but only in the filename/extension
            $filename_pattern = explode("*", $filename_pattern); //split the filename string to get the filename_start and filename_end variables
            $filename_start = $filename_pattern[0];
            $filename_end = $filename_pattern[1];
            $files = array_diff(scandir($dir), array('.', '..')); //remove useless stuff
            $file_time = 0; //start with 0, every date is greater than 0
            foreach ($files as $file) {
                if (!is_dir($dir . DIRECTORY_SEPARATOR . $file)  //check if the file is a file and not a dir
                    && startsWith($file, $filename_start)   //check if it starts with the correct string
                    && endsWith($file, $filename_end)   //check if it ends with the correct string
                    && (filemtime($dir . DIRECTORY_SEPARATOR . $file) > $file_time) //check if the file is edited later than the previously checked file
                ) {
                    $file_time = filemtime($dir . DIRECTORY_SEPARATOR . $file); //set filetime to filemtime of current file, for later checks
                    $last_edited_file = $file;
                }
            }
            if ($file_time == 0 || !isset($last_edited_file)) return 'ERROR: Something went wrong, no file found'; //Using this in other code to see if a file exists

            return $dir . DIRECTORY_SEPARATOR . $last_edited_file; //return the merged dir and filename
        } else {
            return "ERROR: path is dynamic, only dynamic filenames are allowed!";
        }
    } else {
        return $path; //if path doesn't contain *, just return the path. Nothing fancy here
    }
}

function readExternalLog($log)
{
    $settings = $GLOBALS['settings'];
    ini_set("auto_detect_line_endings", true);
    $result = "";
    $log = file(parseLogPath($log['path']));
    $log = array_reverse($log);
    $lines = $log;
    $maxLines = isset($log['maxLines']) ? $log['maxLines'] : $settings['maxLines'];

    foreach ($lines as $line_num => $line) {
        $result .= "<b>Line {$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
        if ($maxLines != 0 && $line_num == $maxLines) break;
    }
    return $result;
}

function unlinkLog($file, $print)
{


    if ($print) echo('Form submitted:  unlink file:<br>' . $file);
    if ($print) echo('Server received: unlink file:<br>' . $file);
    if ($print) echo('Server attempting to unlink:<br>' . $file);


    $today = date("D d M Y | H:i:s");
    if ($print) echo "<br><br>";

    if (in_array_recursive($file, $GLOBALS['logs'])) {   // check if log file exists in config.php:
        if (is_file($file)) { // check if log file exists:
            $newfile = "$file.bak";

            if (!copy($file, $newfile)) {   // copy log file failed:
                if ($print) echo "Copy log file: FAIL: $newfile";
                $fh = fopen($file, 'a');
                fwrite($fh, "$today | ERROR: Logarr was unable to copy log file:  $file\n");
                fclose($fh);

                if ($print) echo "<script type='text/javascript'>";
                if ($print) echo "console.log('ERROR: Logarr was unable to copy log file:  $file');";
                if ($print) echo "</script>";
            } else {  // copy log file success:
                if ($print) echo "Copy log file: success: $newfile<br>";
                if ($print) echo "<script type='text/javascript'>";
                if ($print) echo "console.log('Copy log file: success: $newfile');";
                if ($print) echo "</script>";

                $delete = unlink($file);    // delete orginal log file:

                if ($delete == true) {
                    if ($print) echo "Delete original log file: success: $file <br>";
                    if ($print) echo "<script type='text/javascript'>";
                    if ($print) echo "console.log('Delete original log file: success: $file');";
                    if ($print) echo "</script>";
                    $newlogfile = $file;

                    // Write log entry in new log file:
                    $current = $today . " | Logarr created new log file: " . $newlogfile . "\n";
                    $createfile = file_put_contents($newlogfile, $current);

                    if ($createfile == true) {
                        if ($print) echo "Create new log file: success: " . $newlogfile . "<br>";
                        if ($print) echo "<script type='text/javascript'>";
                        if ($print) echo "console.log('Create new log file: success:  $newlogfile');";
                        if ($print) echo "refreshblockUI();";
                        if ($print) echo "</script>";
                    } else {
                        if ($print) echo "Create new log file: FAIL: " . $newlogfile . "<br>";
                        if ($print) echo "<script type='text/javascript'>";
                        if ($print) echo "console.log('ERROR: Create new log file: FAIL:  $newlogfile');";
                        if ($print) echo "</script>";
                    }
                } else {
                    if ($print) echo "Delete original log file: FAIL: $file<br>";
                    if ($print) echo "<script type='text/javascript'>";
                    if ($print) echo "console.log('ERROR: Delete original log file: FAIL: $file');";
                    if ($print) echo "</script>";

                    //write log file entry if unlink of original log file fails:
                    $fh = fopen($file, 'a');
                    fwrite($fh, "$today | Logarr delete original log file: FAIL (ERROR):  $file\n");
                    fclose($fh);

                    //remove copied log file if unlink of original log file fails:
                    $deletefail = unlink($newfile);

                    if ($deletefail == true) {
                        if ($print) echo "Delete log file backup: Success: $newfile";
                        if ($print) echo "<script type='text/javascript'>";
                        if ($print) echo "console.log('Delete log file backup: Success: $newfile');";
                        if ($print) echo "</script>";
                    } else {
                        if ($print) echo "Delete log file backup: FAIL: $newfile";
                        if ($print) echo "<script type='text/javascript'>";
                        if ($print) echo "console.log('ERROR: Delete log file backup: FAIL: $newfile');";
                        if ($print) echo "</script>";
                    }
                }
            }
        } else {
            if ($print) echo 'file: ' . $file . ' does not exist.';
            if ($print) echo "<script type='text/javascript'>";
            if ($print) echo "console.log('ERROR: file: '" . $file . "' does not exist.');";
            if ($print) echo "</script>";
        }
    } else {  // Deny access if log file does NOT exist in config.php:
        if ($print) echo 'ERROR:  Illegal File';
        if ($print) echo "<script type='text/javascript'>";
        if ($print) echo "console.log('ERROR:  Illegal File');";
        if ($print) echo "</script>";
    }
}

function human_filesize($bytes, $decimals = 2)
{
    $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
}

function recurse_copy($src, $dst)
{
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                recurse_copy($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function delTree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function in_array_recursive($needle, $haystack)
{   //For checking if the file is indeed a configured log file
    $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));
    foreach ($it AS $element) {
        $element = parseLogPath($element);
        if ($element == $needle) {
            return true;
        }
    }
    return false;
}

function startsWith($haystack, $needle)
{
    return strncmp(strtolower($haystack), strtolower($needle), strlen($needle)) === 0;
}

function endsWith($haystack, $needle)
{
    return $needle === '' || substr_compare(strtolower($haystack), strtolower($needle), -strlen($needle)) === 0;
}

function convertToBytes($from)
{
    $number = substr($from, 0, -2);
    switch (strtoupper(substr($from, -2))) {
        case "KB":
            return $number * 1024;
        case "MB":
            return $number * pow(1024, 2);
        case "GB":
            return $number * pow(1024, 3);
        case "TB":
            return $number * pow(1024, 4);
        case "PB":
            return $number * pow(1024, 5);
        default:
            return $from;
    }
}


