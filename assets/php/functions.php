<?php
$preferences = json_decode(file_get_contents(__DIR__ . '/../config/config.json'), 1)['preferences'];
$settings = json_decode(file_get_contents(__DIR__ . '/../config/config.json'), 1)['settings'];
$logs = json_decode(file_get_contents(__DIR__ . '/../config/config.json'), 1)['logs'];

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

function readExternalLog($log)
{
    $settings = $GLOBALS['settings'];
    ini_set("auto_detect_line_endings", true);

    if (isset($log['autoRollSize']) && $log['autoRollSize'] != 0) {
        //TODO: INSERT AUTO ROLL LOG FUNCTION
    }


    $log = file($log['path']);
    $log = array_reverse($log);
    $lines = $log;
    $maxLines = $settings['maxLines'];
    if (isset($log['maxLines'])) {
        $maxLines = $log['maxLines'];
    }
    foreach ($lines as $line_num => $line) {
        echo "<b>Line {$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
        if ($maxLines != 0 && $line_num == $maxLines) break;
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
        if ($element == $needle) {
            return true;
        }
    }
    return false;
}

?>
