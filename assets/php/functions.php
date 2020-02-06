<?php

ini_set('error_reporting', E_ERROR);

// TODO / IMPORTANT / Are we sure we want to set this??
ini_set('memory_limit', '-1');

// Append webserver's PHP errors to Logarr Log file:
function LogarrErrorHandler($errno, $errstr, $errfile, $errline)
{
	$date = date("D d M Y H:i T ");
	if (!(error_reporting() & $errno)) {
		return false;
	}
	$logFile = __DIR__ . "/../data/logs/logarr.log";
	switch ($errno) {
		case E_USER_ERROR:
			$logFile;
			exit(1);
			break;

		case E_USER_WARNING:
			$logFile;
			break;

		case E_USER_NOTICE:
			$logFile;
			break;

		default:
			$logFile;
			break;
	}

	error_log($date . " | " . "Webserver PHP ERROR log: " . $errstr . "\r\n", 3, $logFile);
	return true;
}

//TODO: / Add this function to all PHP files:
set_error_handler("LogarrErrorHandler");

//Append Logarr errors to webserver's PHP error log file IF defined in php.ini:

//TODO : Add this function to all critial Logarr errors:
function phpLog($phpLogMessage)
{
	if (!get_cfg_var('error_log')) {
	} else {
		error_log($errstr = $phpLogMessage);
	}
};

function appendLog($logentry)
{

	$logfile = 'logarr.log';
	$logdir = __DIR__ . '/../data/logs/';
	$logpath = $logdir . $logfile;
	//$logentry = "Add this to the file";
	$date = date("D d M Y H:i T ");

	if (!file_exists($logdir)) {
		if (!mkdir($logdir)) {
			phpLog($phpLogMessage = "Logarr ERROR: Failed to create Logarr log directory");
			echo "<script>console.log('%cERROR: Failed to create Logarr log directory.', 'color: red;');</script>";
			return "ERROR: Failed to create Logarr log directory";
		} else {
			appendLog("Logarr log directory created");
			appendLog($logentry);
			return "Logarr log directory created";
		}
	};

	if (!$handle = fopen($logpath, 'a+')) {
		phpLog($phpLogMessage = "Logarr ERROR: Failed to open Logarr log file ");
		echo "<script>console.log('%cERROR: Failed to open Logarr log file: ($logfile).', 'color: red;');</script>";
	}

	if (fwrite($handle, $date . " | " . $logentry . "\r\n") === false) {
		phpLog($phpLogMessage = "Logarr ERROR: Failed to write to Logarr log file");
		echo "<script>console.log('%cERROR: Cannot write to Logarr log file: ($logfile).', 'color: red;');</script>";
	} else {
		if (is_writable($logpath)) {
			fclose($handle);
		} else {
			phpLog($phpLogMessage = "Logarr ERROR: The Logarr log file $logfile is not writable");
			echo "<script>console.log('%cERROR: The Logarr log file is not writable: ($logfile).', 'color: red;');</script>";
		}
	}
}

// Data Dir
$datadir_json = json_decode(file_get_contents(__DIR__ . '../../data/datadir.json'), 1);
$datadir = $datadir_json['datadir'];

$config_file = $datadir . '/config.json';
$preferences = json_decode(file_get_contents($config_file), 1)['preferences'];
$settings = json_decode(file_get_contents($config_file), 1)['settings'];
$logs = json_decode(file_get_contents($config_file), 1)['logs'];
$authentication = json_decode(file_get_contents($config_file), 1)['authentication'];

global $preferences, $settings, $logs, $authentication;

if ($preferences['timezone'] == "") {
	date_default_timezone_set('UTC');
	$timezone = date_default_timezone_get();
} else {
	date_default_timezone_set($preferences["timezone"]);
	$timezone = date_default_timezone_get();
}

if (!$settings['rfconfig'] || !$settings['rftime'] || !$settings['rflog'] || !$settings['maxLines'] || !$settings['logRefresh'] || !$settings['autoHighlight'] || !$settings['jumpOnSearch'] || !$settings['liveSearch']) {
	appendLog("ERROR: Invalid Settings value!");
} else {
}


// New version download information

$branch = $preferences['updateBranch'];

// location to download new version zip
$remote_file_url = 'https://github.com/monitorr/logarr/zipball/' . $branch . '';

// rename version location/name
$local_file = __DIR__ . '/../data/tmp/logarr-' . $branch . '.zip'; //download path for udpate zip file

// version check information
// url to external verification of version number as a .TXT file

$ext_version_loc = 'https://raw.githubusercontent.com/monitorr/logarr/' . $branch . '/assets/js/version/version.txt';

// users local version number:
$vnum_loc = __DIR__ . '/../js/version/version.txt';


function configExists()
{
	return is_file($GLOBALS['config_file']);
}

function isDocker()
{

	if (is_file(__DIR__ . "/../../../Dockerfile")) {

		echo "<script type='text/javascript'>";
		echo "console.log('Logarr detected DOCKER environment');";
		echo "</script>";

		appendLog("Logarr detected DOCKER environment");

		return true;
	} else {
		return false;
	}
}

// When user logs into SETTINGS, check config.json for all required AUTHENTICATION values and validity:
function isMissingKeys()
{

	$setupEnabled = $GLOBALS['authentication']['setupEnabled'];
	$settingsEnabled = $GLOBALS['authentication']['settingsEnabled'];
	$logsEnabled = $GLOBALS['authentication']['logsEnabled'];

	if (!$setupEnabled || !$settingsEnabled || !$logsEnabled) {
		echo "<script>console.log('%cError: Invalid Authentication Settings value!', 'color: #FF0104;');</script>";
		echo "<script>$('#sidebarAuthTitle').addClass('sidebarTitleError');</script>";
		phpLog($phpLogMessage = "Logarr ERROR: Invalid Authentication Settings value!");
		appendLog("ERROR: Invalid Authentication Settings value!");
	} else {
	};

	// Check if Logs Enabled has valid value:
	if ($logsEnabled == 'true') {
	} else {
		if ($logsEnabled == 'false') {
		} else {
			echo "<script>console.log('%cError: Invalid Authentication Settings value: logsEnabled', 'color: #FF0104;');</script>";
			echo "<script>$('#sidebarAuthTitle').addClass('sidebarTitleError');</script>";
			appendLog("ERROR: Invalid Authentication Settings value: 'logsEnabled'.");
		}
	}

	// Check if Settings Enabled has valid value:
	if ($settingsEnabled == 'true') {
	} else {
		if ($settingsEnabled == 'false') {
		} else {
			echo "<script>console.log('%cError: Invalid Authentication Settings value: settingsEnabled', 'color: #FF0104;');</script>";
			echo "<script>$('#sidebarAuthTitle').addClass('sidebarTitleError');</script>";
			appendLog("ERROR: Invalid Authentication Settings value: 'settingsEnabled'.");
		}
	}

	// Check if Setup Access has valid value:
	if ($setupEnabled == 'true') {
	} else {
		if ($setupEnabled == 'false') {
		} else {
			echo "<script>console.log('%cError: Invalid Authentication Settings value: setupEnabled', 'color: #FF0104;');</script>";
			echo "<script>$('#sidebarAuthTitle').addClass('sidebarTitleError');</script>";
			appendLog("ERROR: Invalid Authentication Settings value: 'setupEnabled'.");
		}
	}
}

// When sync-config executes, check if required values are missing from config.json and write to Logarr Log:
function isMissingKeyslog()
{

	$setupEnabled = $GLOBALS['authentication']['setupEnabled'];
	$settingsEnabled = $GLOBALS['authentication']['settingsEnabled'];
	$logsEnabled = $GLOBALS['authentication']['logsEnabled'];

	if (!$setupEnabled || !$settingsEnabled || !$logsEnabled) {
		appendLog("ERROR: Invalid Authentication Settings value!");
	} else {
	};
}

function isMissingPrefs()
{

	$sitetitle = $GLOBALS['preferences']['sitetitle'];
	$updateBranch = $GLOBALS['preferences']['updateBranch'];
	$timezone = $GLOBALS['preferences']['timezone'];
	$timestandard = $GLOBALS['preferences']['timestandard'];

	if (!$sitetitle || !$updateBranch || !$timezone || !$timestandard) {
		appendLog("ERROR: Invalid Preferences Settings value!");
		echo "<script>console.log('%cError: Invalid Preferences Settings value!', 'color: #FF0104;');</script>";
		echo "<script>$('#sidebarUserPrefsTitle').addClass('sidebarTitleError');</script>";
	} else {
	};

	// Check if Update Branch has valid value:
	if ($updateBranch == 'master') {
	} else {
		if ($updateBranch == 'develop') {
		} else {
			if ($updateBranch == 'alpha') {
			} else {
				echo "<script>console.log('%cError: Invalid Preferences Settings value: updateBranch', 'color: #FF0104;');</script>";
				echo "<script>$('#sidebarUserPrefsTitle').addClass('sidebarTitleError');</script>";
				appendLog("ERROR: Invalid Preferences Settings value: 'updateBranch'.");
			}
		}
	}

	// Check if Time Standard has valid value:
	if ($timestandard == 'True') {
	} else {
		if ($timestandard == 'False') {
		} else {
			echo "<script>console.log('%cError: Invalid Preferences Settings value: timestandard', 'color: #FF0104;');</script>";
			echo "<script>$('#sidebarUserPrefsTitle').addClass('sidebarTitleError');</script>";
			appendLog("ERROR: Invalid Preferences Settings value: 'timestandard'.");
		}
	}
}

// When sync-config executes, check if required User preferences values are missing from config.json and write to Logarr Log:
function isMissingPrefslog()
{

	$sitetitle = $GLOBALS['preferences']['sitetitle'];
	$updateBranch = $GLOBALS['preferences']['updateBranch'];
	$timezone = $GLOBALS['preferences']['timezone'];
	$timestandard = $GLOBALS['preferences']['timestandard'];

	if (!$sitetitle || !$updateBranch || !$timezone || !$timestandard) {
		appendLog("ERROR: Invalid Preferences Settings value!");
	} else {
	};
}

// Check for valid SETTINGS values in config.json when settings.php or index.php is loaded:
function isMissingSettings()
{

	global $settings;

	if (!$settings['rfconfig'] || $settings['rfconfig'] < 1001 || !$settings['rftime'] || $settings['rftime'] < 1001 || !$settings['rflog'] || $settings['rflog'] < 3001 || !$settings['maxLines'] || !$settings['logRefresh'] || !$settings['autoHighlight'] || !$settings['jumpOnSearch'] || !$settings['liveSearch']) {
		appendLog("ERROR: Invalid Settings value!");
		echo "<script>console.log('%cError: Invalid Settings value', 'color: red;');</script>";
		echo "<script>$('#sidebarSettingsTitle').addClass('sidebarTitleError');</script>";
	} else {
	}
}

// Check if Logarr authenticaiton is enabled / if TRUE, check login status every 10s:
function checkLoginindex()
{

	$logsEnabled = $GLOBALS['authentication']['logsEnabled'];

	if (!$logsEnabled) {

		echo "<script type='text/javascript'>";
		echo "console.log('ERROR: Logarr could not check authentication settings');";
		echo "</script>";
		appendLog(
			$logentry = "ERROR: Logarr could not check authentication settings. An invalid authentication string for 'logsEnabled' is detected in 'config.json'."
		);
		echo "ERROR: Logarr could not check authentication settings";
		// If authentication settings are invalid forward to settings.php:
		echo "<script type='text/javascript'>";
		echo "window.location.href = 'settings.php';";
		echo "</script>";
	} else {
		if ($logsEnabled == "false") {
			echo "<script type='text/javascript'>";
			echo "console.log('Logarr auth: DISABLED');";
			echo "</script>";
			appendLog(
				$logentry = "Logarr auth: DISABLED"
			);
		} else if ($logsEnabled == "true") {
			echo "<script type='text/javascript'>";
			echo "console.log('Logarr auth: ENABLED');";
			echo "</script>";
			appendLog(
				$logentry = "Logarr auth: ENABLED"
			);
			echo "<script src='assets/js/login-status.js'></script>";
		} else {
			echo "<script type='text/javascript'>";
			echo "console.log('ERROR: Logarr could not check authentication settings');";
			echo "</script>";
			appendLog(
				$logentry = "ERROR: Logarr could not check authentication settings. An invalid authentication value in 'config.json' is set for 'logsEnabled'. Access to the Logarr UI is DISABLED."
			);
			echo "ERROR: Logarr could not check authentication settings";
			echo "<script type='text/javascript'>";
			echo "window.location.href = 'settings.php';";
			echo "</script>";
		};
	}
}

// Check if Logarr Settings authenticaiton is enabled / if TRUE, check login status every 10s:
function checkLoginsettings()
{

	echo "<script type='text/javascript'>";
	echo "console.log('Logarr authentication settings check');";
	echo "</script>";

	$settingsEnabled = $GLOBALS['authentication']['settingsEnabled'];
	$setupEnabled = $GLOBALS['authentication']['setupEnabled'];

	if (!$settingsEnabled) {

		echo "<script type='text/javascript'>";
		echo "console.log('ERROR: Logarr could not check authentication settings');";
		echo "</script>";
		appendLog(
			$logentry = "ERROR: Logarr could not check authentication settings. An invalid authentication string for 'settingsEnabled' is detected in 'config.json'. Access to the Logarr Settings page is DISABLED."
		);
		echo "ERROR: Logarr could not check authentication settings";
		// If authentication settings are invalid forward to unauthorized.php:
		echo "<script type='text/javascript'>";
		// echo "window.location.href = 'settings.php';";
		echo "window.location.href = './assets/php/authentication/unauthorized.php';";
		echo "</script>";
	} else {

		if ($setupEnabled == "true") {
			echo "<script type='text/javascript'>";
			echo "console.log('WARNING: Logarr Setup Access is ENABLED');";
			echo "</script>";
			appendLog(
				$logentry = "WARNING: Logarr Setup Access is ENABLED. This authentication setting should be DISABLED ('false') after initial Setup"
			);
		} else {
			echo "<script type='text/javascript'>";
			echo "console.log('Logarr Setup Access: DISABLED');";
			echo "</script>";
		}
		if ($settingsEnabled == "false") {
			echo "<script type='text/javascript'>";
			echo "console.log('Logarr settings auth: DISABLED');";
			echo "</script>";
			appendLog(
				$logentry = "Logarr settings auth: DISABLED"
			);
		} else if ($settingsEnabled == "true") {
			echo "<script type='text/javascript'>";
			echo "console.log('Logarr settings auth: ENABLED');";
			echo "</script>";
			appendLog(
				$logentry = "Logarr settings auth: ENABLED"
			);
			echo "<script src='assets/js/login-status-settings.js'></script>";
		} else {
			echo "<script type='text/javascript'>";
			echo "console.log('ERROR: Logarr could not check authentication settings');";
			echo "</script>";
			appendLog(
				$logentry = "ERROR: Logarr could not check authentication settings. An invalid authentication value in 'config.json' is set for 'settingsEnabled'. Access to the Logarr Settings page is DISABLED."
			);
			echo "ERROR: Logarr could not check authentication settings";
			echo "<script type='text/javascript'>";
			echo "window.location.href = './assets/php/authentication/unauthorized.php';";
			echo "</script>";
		}
	}
}

function settingsValues()
{
	appendLog("Timezone: " . $GLOBALS['preferences']['timezone']);

	appendLog("Config refresh interval: " . $GLOBALS['settings']['rfconfig'] . " ms");

	appendLog("Time refresh interval: " . $GLOBALS['settings']['rftime'] . " ms");

	if ($GLOBALS['settings']['logRefresh'] == "true") {
		appendLog("Log auto update: Enabled | Interval: " . $GLOBALS['settings']['rflog'] . " ms");
	} else {
		appendLog("Log auto update: DISABLED");
	}

	if ($GLOBALS['authentication']['setupEnabled'] == "true") {
		appendLog("WARNING: Logarr Setup Access is ENABLED. This authentication setting should be DISABLED ('false') after initial Setup");
		echo "<script type='text/javascript'>";
		echo "console.log('WARNING: Logarr Setup Access is ENABLED');";
		echo "</script>";
	}
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
				if (
					!is_dir($dir . DIRECTORY_SEPARATOR . $file)  //check if the file is a file and not a dir
					&& startsWith($file, $filename_start)   //check if it starts with the correct string
					&& endsWith($file, $filename_end)   //check if it ends with the correct string
					&& (filemtime($dir . DIRECTORY_SEPARATOR . $file) > $file_time) //check if the file is edited later than the previously checked file
				) {
					$file_time = filemtime($dir . DIRECTORY_SEPARATOR . $file); //set filetime to filemtime of current file, for later checks
					$last_edited_file = $file;
				}
			}
			if ($file_time == 0 || !isset($last_edited_file)) {
				return 'ERROR: Log not found'; //Using this in other code to see if a file exists 
			}
			return $dir . DIRECTORY_SEPARATOR . $last_edited_file; //return the merged dir and filename
		} else {
			appendLog(
				$logentry = "ERROR: path is dynamic, only dynamic filenames are allowed!"
			);
			return "ERROR: path is dynamic, only dynamic filenames are allowed!";
		}
	} else {
		return $path; //if path doesn't contain *, return the path
	}
}

function readExternalLog($log)
{
	$settings = $GLOBALS['settings'];
	ini_set("auto_detect_line_endings", true);
	$result = "";
	$logContents = file(parseLogPath($log['path']));
	$logContents = array_reverse($logContents);
	$maxLines = isset($log['maxLines']) ? $log['maxLines'] : $settings['maxLines'];

	foreach ($logContents as $line_num => $line) {
		$result .= "<font color='white'><strong><i>Line {$line_num}</i></strong></font> : " . htmlspecialchars($line) . "<br />\n";
		if ($maxLines != 0 && $line_num == $maxLines) {
			break;
		}
	}
	unset($logContents);
	return $result;
}

function unlinkLog($file, $print)
{
	appendLog(
		$logentry = "Attempting log roll: " . $file
	);

	if ($print) {
		echo ('Unlink file: ' . $file . '<br>');
		echo ('Server received unlink file: ' . $file . '<br>');
		echo ('Server attempting to unlink: ' . $file . '<br>');
	};

	$today = date("D d M Y H:i T ");
	if ($print) echo "<br><br>";

	if (in_array_recursive($file, $GLOBALS['logs'])) {   // check if log file exists:
		if (is_file($file)) { // check if log file exists:
			$newfile = "$file.bak";

			if (!copy($file, $newfile)) {   // copy log file failed:
				if ($print) {
					echo "Copy log file: FAIL: $newfile";
				};
				$fh = fopen($file, 'a');
				fwrite($fh, "$today | ERROR: Logarr was unable to copy log file:  $file\n");
				fclose($fh);

				if ($print) {
					echo "<script type='text/javascript'>";
					echo "console.log('ERROR: Logarr failed to copy log file:  $file');";
					echo "</script>";

					echo ('<br> <p class="rolllogfail"> Roll log file FAIL: ' . $file . '</p>');
				};

				appendLog(
					$logentry = "ERROR: Failed to copy and backup original log file: $file "
				);
			} else {  // copy log file success:
				if ($print) {
					echo "Copy log file: SUCCESS: $newfile<br>";
					echo "<script type='text/javascript'>";
					echo "console.log('Copy log file: SUCCESS: $newfile');";
					echo "</script>";
				};

				appendLog(
					$logentry = "Roll Log: Copy log file: SUCCESS: $newfile"
				);

				$delete = unlink($file); // delete orginal log file:

				if ($delete == true) {
					if ($print) {
						echo "Delete original log file: SUCCESS: $file <br>";
						echo "<script type='text/javascript'>";
						echo "console.log('Delete original log file: SUCCESS: $file');";
						echo "</script>";
					};

					appendLog(
						$logentry = "Roll Log: Delete original log file: SUCCESS: $file"
					);

					$newlogfile = $file;

					// Write log entry in new log file:
					$current = $today . " | Logarr created new log file: " . $newlogfile . "\n";
					$createfile = file_put_contents($newlogfile, $current);

					if ($createfile == true) {
						if ($print) {
							echo "Create new log file: SUCCESS: " . $newlogfile . "<br>";
							echo "<script type='text/javascript'>";
							echo "console.log('Create new log file: SUCCESS:  $newlogfile');";
							echo "</script>";

							echo ('<br> <p class="rolllogsuccess">Roll log file SUCCESS: ' . $file . '</p>');
						};

						echo "<script type='text/javascript'>";
						echo "console.log('Roll log file SUCCESS: $file');";
						echo "</script>";

						appendLog(
							$logentry = "Roll Log file SUCCESS: $file"
						);
					} else {
						if ($print) {
							echo "Create new log file: FAIL: " . $newlogfile . "<br>";
							echo "<script type='text/javascript'>";
							echo "console.log('ERROR: Create new log file: FAIL:  $newlogfile');";
							echo "</script>";

							echo ('<br> <p class="rolllogfail"> Roll log file FAIL: ' . $file . '</p>');
						};

						appendLog(
							$logentry = "Roll Log: ERROR: Create new log file:  $newlogfile"
						);
					}
				} else {
					if ($print) {
						echo "Delete original log file: FAIL: $file<br>";
						echo "<script type='text/javascript'>";
						echo "console.log('ERROR: Delete original log file: FAIL: $file');";
						echo "</script>";
					};

					appendLog(
						$logentry = "Roll Log: ERROR: Delete original log file: $file"
					);

					//write log file entry if unlink of original log file fails:
					$fh = fopen($file, 'a');
					fwrite($fh, "$today | Logarr delete original log file: FAIL (ERROR):  $file\n");
					fclose($fh);

					//remove copied log file if unlink of original log file fails:
					$deletefail = unlink($newfile);

					if ($deletefail == true) {
						if ($print) {
							echo "Delete log file backup: SUCCESS: $newfile";
							echo "<script type='text/javascript'>";
							echo "console.log('Delete log file backup: SUCCESS: $newfile');";
							echo "</script>";
						};

						appendLog(
							$logentry = "Roll Log: Delete log file backup: SUCCESS: $newfile"
						);
					} else {
						if ($print) {
							echo "Delete log file backup: FAIL: $newfile";
							echo "<script type='text/javascript'>";
							echo "console.log('ERROR: Delete log file backup: FAIL: $newfile');";
							echo "</script>";
						};

						appendLog(
							$logentry = "Roll Log: ERROR: Delete log file backup: FAIL: $newfile"
						);
					};

					echo "<script type='text/javascript'>";
					echo "console.log('ERROR: Roll log FAILED: $file');";
					echo "</script>";

					appendLog(
						$logentry = "Roll Log: ERROR: Roll log file:  $file "
					);

					if ($print) {
						echo ('<br> <p class="rolllogfail"> Roll log file FAIL: ' . $file . '</p>');
					};
				}
			}
		} else {
			if ($print) {
				echo 'file: ' . $file . ' does not exist.';
				echo "<script type='text/javascript'>";
				echo "console.log('ERROR: file: $file does not exist.');";
				echo "</script>";

				echo ("<br> <p class='rolllogfail'> ERROR: file: ' " . $file . " ' does not exist. </p>");
			};

			appendLog(
				$logentry = "Roll Log: ERROR: file: $file does not exist "
			);

			phpLog($phpLogMessage = "Logarr Roll Log ERROR: file: $file does not exist");
		}
	} else {  // Deny access if log file does NOT exist:
		if ($print) {
			echo 'ERROR:  Illegal File';
			echo "<script type='text/javascript'>";
			echo "console.log('ERROR:  Illegal File');";
			echo "</script>";

			echo ("<br> <p class='rolllogfail'> ERROR:  Illegal File </p>");
		};

		appendLog(
			$logentry = "Roll Log: ERROR: Illegal File "
		);

		phpLog($phpLogMessage = "Logarr Roll Log ERROR: Illegal file.");
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

	// TODO / Testing:

	@mkdir($dst);

	// TODO / Does not work:

	// if (@mkdir($dst) === false) {
	// 	//throw new \RuntimeException('The directory '.$dir.' could not be created.');
	// 	appendLog(
	// 		$logentry = "ERROR: Logarr update failed: The update directory could not be created:  $dst "
	// 	);
	// 	echo "<script>console.log('%cERROR: Logarr update failed: The update directory could not be created', 'color: red;');</script>";
	// }

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
{   //Check if file is valid configured log file
	$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));
	foreach ($it as $element) {
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
