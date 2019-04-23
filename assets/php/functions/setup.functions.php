<?php
/**
 * LOGARR
 * By: @seanvree, @jonfinley and @rob1998
 * https://github.com/Monitorr/Logarr
 * @return string
 */

/**
 * Appends a log entry to the Logarr log file
 * @param $logentry
 * @return string
 */

 //TODO: Add error logging
function appendLog($logentry) {
	$logfile = 'logarr.log';
	$logdir = 'assets/data/logs/';
	//$logdir = '../data/logs/';
	$logpath = $logdir . $logfile;
	$date = date("D d M Y H:i T ");

	//if (file_exists($logpath)) {
	if (file_exists($logdir)) {
		ini_set('error_reporting', E_ERROR);
		$oldContents = file_get_contents($logpath);
		if (file_put_contents($logpath, $oldContents . $date . " | " . $logentry . "\r\n") === false){
			echo "<script>console.log('%cERROR: Failed writing to Logarr log file.', 'color: #FF0104;');</script>";
			return "Error writing to Logarr log file";
			//return $error;
		}

	} else {
		if (!mkdir($logdir)) {
			echo "<script>console.log('%cERROR: Failed to create Logarr log directory.', 'color: #FF0104;');</script>";
			return "ERROR: Failed to create Logarr log directory";
		} else {
			appendLog( "Logarr log directory created");
			appendLog($logentry);
			return "Logarr log directory created";
		}
	}
}

/**
 * Checks if the current instance is running on Docker
 * @return bool
 */
function isDocker() {

	if (is_file(__DIR__ . "/../../../Dockerfile")) {
		return true;
	} else {
		return "0";
	}
}


/**
 * Creates the datadir
 * @param $datadir
 * @return bool
 */
function createDatadir($datadir) {

	$datadir = trim($datadir, " \t\n\r");
	$datadir = rtrim($datadir, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	$datadir_file = __DIR__ . '/../../data/datadir.json';
	$datadir_file_fail = __DIR__ . '/../../data/datadir.fail.txt';

	appendLog("Logarr is creating data directory: " . json_encode($_POST));

	if (!mkdir($datadir, 0777, FALSE)) {
		file_put_contents($datadir_file_fail, json_encode($_POST));
		appendLog( "ERROR: Logarr failed to create data directory");
		return false;
	} else {
		file_put_contents($datadir_file, json_encode(array("datadir" => $datadir)));
		unlink($datadir_file_fail);
		appendLog("Logarr created data directory: " . $datadir);
		return true;
	}
}

/**
 * Copies the default config and converts the old config if existing
 * @param $datadir
 * @return bool
 */
function copyDefaultConfig($datadir) {

	$default_config_file = __DIR__ . "/../../php/functions/default.json";
	$new_config_file = $datadir . 'config.json';
	$old_config_file = __DIR__ . "/../../config/config.php";
	if (is_file($old_config_file) && !is_file($new_config_file)) {
		appendLog( "Old config file detetected - attempting to convert");
		include_once($old_config_file);

		if((!isset($config) || empty($config)) || !isset($logs)){
			appendLog("ERROR: Old config file detected, failed to convert");
			return false;
		}

		$new_config = json_decode(file_get_contents($default_config_file), 1);

		$old_config_converted = array(
			'settings' => array(
				'rftime' => $config['rftime'],
				'rflog' => $config['rflog'],
				'maxLines' => $config['max-lines'],
			),
			'preferences' => array(
				"sitetitle" => $config['title'],
				"timezone" => $config['timezone'],
				"timestandard" => ($config['timestandard'] == 0 ? "False" : "True"),
				"updateBranch" => $config['updateBranch'],
			),
			"logs" => array()
		);

		if (!empty($logs)) {
			foreach ($logs as $logTitle => $path) {
				array_push($old_config_converted['logs'],
					array(
						"logTitle" => $logTitle,
						"path" => $path,
						"enabled" => "Yes",
						"category" => "Uncategorized"
					)
				);
			}
		}

		$old_config_converted['logs'] = array_merge($old_config_converted["logs"], $new_config["logs"]);

		$old_new_merged = array_replace_recursive($new_config, $old_config_converted);

		$json = json_encode($old_new_merged, JSON_PRETTY_PRINT);
		file_put_contents($new_config_file, $json);
		$copyDefaults = true;
		appendLog("Logarr converted old config file to new config file: " . $new_config_file);

		if (unlink($old_config_file)) {
			appendLog("Old config file has been removed");
		} else {
			appendLog("ERROR: Old config file could not be removed");
			file_put_contents($old_config_file, "Old config was converted to the new format, you can now safely remove this file and directory");
		}

	} else {
		$copyDefaults = copy($default_config_file, $new_config_file);
		appendLog( "Logarr created new default config file: " . $new_config_file);
	}

	return $copyDefaults;
}