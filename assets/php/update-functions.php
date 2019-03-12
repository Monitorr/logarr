<?php

require("functions.php");
include("auth_check.php");
// copy the file from source server

//TODO / Change to /assets/data dir:
//TODO: Migrate to functions.php

//TODO: Change update path to /assets/data/

// mkdir('../../tmp');

mkdir('../../assets/data/tmp');
$copy = copy($remote_file_url, $local_file);
// check for success or fail
if (!$copy) {
	// data message if failed to copy from external server
	$data = array("copy" => 0);
	//TODO:  Creat failure log file
} else {
	// success message, continue to unzip
	$copy = 1;
}
// check for verification
if ($copy == 1) {

	//$base_path = dirname(__DIR__, 2);

	$base_path = dirname(__DIR__, 2);

	//$extractPath = $base_path . '/tmp/';

	$extractPath = $base_path . '/assets/data/tmp/';

	// unzip update
	$zip = new ZipArchive;
	$res = $zip->open($local_file);
	if ($res === true) {
		$zip->extractTo($extractPath);
		$zip->close();
		
		// copy config.php to safe place while we update

		//CHANGE ME / TODO: / Remove old config:

		rename('../config/config.php', $extractPath . 'config.php');

		// copy files from /assets/data/temp to Logarr root:
		$scanPath = array_diff(scandir($extractPath), array('..', '.'));
		$fullPath = $extractPath . $scanPath[2];
		recurse_copy($fullPath, $base_path);

		// restore config.php file
		rename($extractPath . 'config.php', '../config/config.php');

		// update users local version number file
		$userfile = fopen("../js/version/version.txt", "w");
		$user_vnum = fgets($userfile);
		fwrite($userfile, $_POST['version']);
		fclose($userfile);
		delTree($fullPath);

		// success updating files:
		$data = array("unzip" => 1);
	} else {
		// error updating files
		$data = array("unzip" => 0);

		// delete potentially corrupt file
		unlink($local_file);
		//TODO:  Creat failure log file
	}
}
// send the json data
echo json_encode($data);