<?php

require("functions.php");
// copy the file from source server
mkdir('../../tmp');
$copy = copy($remote_file_url, $local_file);
// check for success or fail
if (!$copy) {
    // data message if failed to copy from external server
    $data = array("copy" => 0);
} else {
    // success message, continue to unzip
    $copy = 1;
}
// check for verification
if ($copy == 1) {

    $base_path = dirname(__DIR__, 2);
    $extractPath = $base_path . '/tmp/';

    // unzip update
    $zip = new ZipArchive;
    $res = $zip->open($local_file);
    if ($res === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
	    // copy config.json to safe place while we update
	    rename($datadir . '/config.json', $extractPath . 'config.json');
	    // copy config.json to safe place while we update
	    rename(__DIR__ . '/../data/custom.css', $extractPath . 'custom.css');
        // copy files from temp to Logarr root
        $scanPath = array_diff(scandir($extractPath), array('..', '.'));
        $fullPath = $extractPath . $scanPath[2];
        recurse_copy($fullPath, $base_path);
	    // restore config.json file
	    rename($extractPath . 'config.json', $datadir . '/config.json');
	    // restore custom.css file
	    rename($extractPath . 'custom.css', __DIR__ . '/../data/custom.css');
        // update users local version number file
        $userfile = fopen("../js/version/version.txt", "w");
        $user_vnum = fgets($userfile);
        fwrite($userfile, $_POST['version']);
        fclose($userfile);
        delTree($fullPath);
        // success updating files
        $data = array("unzip" => 1);
    } else {
        // error updating files
        $data = array("unzip" => 0);
        // delete potentially corrupt file
        unlink($local_file);
    }
}
// send the json data
echo json_encode($data);



