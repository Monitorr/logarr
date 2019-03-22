<?php
require("functions.php");
include("auth_check.php");

ini_set('max_execution_time', 60);

//TODO: Migrate to functions.php
//TODO: Append outputs to appendLog function

// copy the file from source server
mkdir('../../assets/data/tmp');
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

	$extractPath = $base_path . '/assets/data/tmp/';

	// unzip update
	$zip = new ZipArchive;
	$res = $zip->open($local_file);
	if ($res === true) {
		$zip->extractTo($extractPath);
		$zip->close();

		//backup users custom files:

		$filecss = $base_path . '/assets/data/custom.css';
		$filecssbk = $base_path . '/assets/data/tmp/custom.css';

		$filejs = $base_path . '/assets/data/custom.js';
		$filejsbk = $base_path . '/assets/data/tmp/custom.js';

		if (!copy($filecss, $filecssbk)) {
			//echo "failed to copy $filecss...\n";
		}

		if (!copy($filejs, $filejsbk)) {
			//echo "failed to copy $filejs...\n";
		}

		// copy files from /assets/data/temp to Logarr root:
		$scanPath = array_diff(scandir($extractPath), array('..', '.'));
		$fullPath = $extractPath . $scanPath[2];
		recurse_copy($fullPath, $base_path);

		//restore users custom files:

		if (!copy($filecssbk, $filecss)) {
			//echo "failed to copy $filecssnk...\n";
		}

		if (!copy($filejsbk, $filejs)) {
			//echo "failed to copy $filejsbk...\n";
		}

		// update users local version number file
		$userfile = fopen("../js/version/version.txt", "w");
		$user_vnum = fgets($userfile);
		fwrite($userfile, $_POST['version']);
		fclose($userfile);
		delTree($fullPath);

		// success updating files:

		appendLog(
			$logentry = "Logarr update: SUCCESSFUL"
		);

		$data = array("unzip" => 1);
	} else {
		// error updating files
		$data = array("unzip" => 0);

		appendLog(
			$logentry = "ERROR: Logarr update could not update local files"
		);

		// delete potentially corrupt file
		unlink($local_file);
	}
}
// send the json data
echo json_encode($data);