<?php
include_once '../config/config.php';


// New version download information

$branch = $config['updateBranch'];

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


function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

function delTree($dir) {
   $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
  }

?>
