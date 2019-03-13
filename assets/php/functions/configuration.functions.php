<?php
/**
 * Project: Logarr
 * By: @seanvree, @jonfinley and @rob1998
 * URL: https://github.com/Monitorr/Logarr
 * Date: 13-Mar-19
 * Time: 16:54
 */

function createDatadir($datadir) {
	$datadir = trim($datadir, " \t\n\r");
	$datadir = rtrim($datadir, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	$datadir_file = __DIR__ . '/../../data/datadir.json';
	$datadir_file_fail = __DIR__ . '/../../data/datadir.fail.txt';

	file_put_contents($datadir_file, json_encode(array("datadir" => $datadir)));

	if (!mkdir($datadir, 0777, FALSE)) {
		rename($datadir_file, $datadir_file_fail);
		file_put_contents($datadir_file_fail, json_encode($_POST));
		return false;
	} else {
		return true;
	}
}

function copyDefaultConfig($datadir) {

	$default_config_file = __DIR__ . "/../../data/default.json";
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
	} else {
		$copyDefaults = copy($default_config_file, $new_config_file);
	}

	return $copyDefaults;
}