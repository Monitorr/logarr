<?php
/**
 * LOGARR
 * By: @seanvree, @jonfinley and @rob1998
 * https://github.com/Monitorr/Logarr
 */

function appendLog($logentry) {
	$logfile = 'logarr.log';
	$logdir = 'assets/data/logs/';
	$logpath = $logdir . $logfile;
	//$logentry = "Add this to the file";
	$date = date("D d M Y H:i T ");

	if (file_exists($logdir)) {

		if (!$handle = fopen($logpath, 'a+')) {
			echo "<script>console.log('ERROR: Cannot open file ($logfile)');</script>";
		}

		if (fwrite($handle, $date . " | " . $logentry . "\r\n") === false) {
			echo "<script>console.log('ERROR: Cannot write to file $logfile');</script>";
		} else {
			if (is_writable($logpath)) {
				fclose($handle);
			} else {
				echo "<script>console.log('ERROR: The file $logfile is not writable');</script>";
			}
		}
	} else {
		if (!mkdir($logdir)) {
			echo "<script>console.log('ERROR: Cannot create log dir');</script>";
		} else {
			appendLog($logentry = "Logarr log dir created");
			if (!$handle = fopen($logpath, 'a+')) {
				echo "<script>console.log('ERROR: Cannot open file ($logfile)');</script>";
			}
			if (fwrite($handle, $date . " | " . $logentry . "\r\n") === false) {
				echo "<script>console.log('ERROR: Cannot write to file $logfile');</script>";
			} else {
				if (is_writable($logpath)) {
					fclose($handle);
				} else {
					echo "<script>console.log('ERROR: The file $logfile is not writable');</script>";
				}
			}
		}
	}
}


//Check if Logarr is running on DOCKER, if TRUE, disable datadir change function
function isDocker() {

	if (is_file(__DIR__ . "/../../../Dockerfile")) {

		echo "<script type='text/javascript'>";
		echo "console.log('Logarr detected DOCKER enviroment');";
		echo "</script>";

		appendLog($logentry = "Logarr detected DOCKER enviroment");

		return true;
	}
}

function createDatadir($datadir) {

	$datadir = trim($datadir, " \t\n\r");
	$datadir = rtrim($datadir, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	$datadir_file = __DIR__ . '/../../data/datadir.json';
	$datadir_file_fail = __DIR__ . '/../../data/datadir.fail.txt';

	appendLog($logentry = "Logarr is creating data directory: " . json_encode($_POST));

	if (!mkdir($datadir, 0777, FALSE)) {
		file_put_contents($datadir_file_fail, json_encode($_POST));
		appendLog($logentry = "Logarr failed to create data directory");
		return false;
	} else {
		file_put_contents($datadir_file, json_encode(array("datadir" => $datadir)));
		unlink($datadir_file_fail);
		appendLog($logentry = "Logarr created data dir: " . $datadir);
		return true;
	}
}

function copyDefaultConfig($datadir) {

	//TODO:  Not working

	//$default_config_file = __DIR__ . "/../../data/default.json";
	$default_config_file = __DIR__ . "/../../php/functions/default.json";
	$new_config_file = $datadir . 'config.json';
	if (is_file(__DIR__ . "/../../config/config.php") && !is_file($new_config_file)) {
		include_once(__DIR__ . "/../../config/config.php");
		$new_config = json_decode(file_get_contents($default_config_file), 1);
		$old_config_converted = array(
			'settings' => array(
				'rftime' => $GLOBALS['config']['rftime'],
				'rflog' => $GLOBALS['config']['rflog'],
				'maxLines' => $GLOBALS['config']['max-lines'],
			),
			'preferences' => array(
				"sitetitle" => $GLOBALS['config']['title'],
				"timezone" => $GLOBALS['config']['timezone'],
				"timestandard" => ($GLOBALS['config']['timestandard'] == 0 ? "False" : "True"),
				"updateBranch" => $GLOBALS['config']['updateBranch'],
			),
			"logs" => array()
		);
		foreach ($GLOBALS['logs'] as $logTitle => $path) {
			array_push($old_config_converted['logs'],
				array(
					"logTitle" => $logTitle,
					"path" => $path,
					"enabled" => "Yes",
					"category" => ""
				)
			);
		}
		$json = json_encode(array_replace_recursive($new_config, $old_config_converted), JSON_PRETTY_PRINT);
		file_put_contents($new_config_file, $json);
		$copyDefaults = true;
		appendLog($logentry = "Logarr created new config file: " . $new_config_file);
	} else {
		$copyDefaults = copy($default_config_file, $new_config_file);
		appendLog($logentry = "Logarr created new config file: " . $new_config_file);
	}

	return $copyDefaults;
}
