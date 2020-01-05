<?php

/**
 * LOGARR
 * By: @seanvree, @jonfinley and @rob1998
 * https://github.com/Monitorr/Logarr
 * @return string
 */

    //TODO / IMPORTANT / Change to E_WARN:
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

//Append Logarr errors to webserver's PHP error log file IF defined in php.ini:
function phpLog($phpLogMessage)
{
    if (!get_cfg_var('error_log')) {
    } else {
        error_log($errstr = $phpLogMessage);
    }
};

/**
 * Appends a log entry to the Logarr log file
 * @param $logentry
 * @return string
 */

function appendLog($logentry)
{
    global $phpLogMessage;
    $logfile = 'logarr.log';
    $logdir = 'assets/data/logs/';
    $logpath = $logdir . $logfile;
    $date = date("D d M Y H:i T ");

    if (file_exists($logdir)) {
        ini_set('error_reporting', E_ERROR);
        $oldContents = file_get_contents($logpath);
        if (file_put_contents($logpath, $oldContents . $date . " | " . $logentry . "\r\n") === false) {
            phpLog($phpLogMessage = "Logarr ERROR: Failed writing to Logarr log file");
            echo "<script>console.log('%cERROR: Failed writing to Logarr log file.', 'color: red;');</script>";
            return "Error writing to Logarr log file";
            //return $error;
        }
    } else {
        if (!mkdir($logdir)) {
            echo "<script>console.log('%cERROR: Failed to create Logarr log directory.', 'color: red;');</script>";
            phpLog($phpLogMessage = "Logarr ERROR: Failed to create Logarr log directory");
            return "ERROR: Failed to create Logarr log directory";
        } else {
            appendLog("Logarr log directory created");
            appendLog($logentry);
            return "Logarr log directory created";
        }
    }
}

/**
 * Checks if the current instance is running on Docker
 * @return bool
 */

function isDocker()
{
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
function createDatadir($datadir)
{
    $datadir = trim($datadir, " \t\n\r");
    $datadir = rtrim($datadir, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $datadir_file = __DIR__ . '/../../data/datadir.json';
    $datadir_file_fail = __DIR__ . '/../../data/datadir.fail.txt';

    appendLog("Logarr is creating a data directory and user database: " . json_encode($_POST));

    if (!mkdir($datadir, 0777, FALSE)) {
        file_put_contents($datadir_file_fail, json_encode($_POST));
        phpLog($phpLogMessage = "Logarr ERROR: Failed to create data directory");
        appendLog("ERROR: Logarr failed to create data directory");
        return false;
    } else {
        file_put_contents($datadir_file, json_encode(array("datadir" => $datadir)));
        unlink($datadir_file_fail);
        phpLog($phpLogMessage = "Logarr created data directory: " . $datadir);
        appendLog("Logarr created data directory: " . $datadir);
        return true;
    }
}

/**
 * Copies the default config and converts the old config if existing
 * @param $datadir
 * @return bool
 */

//TODO:  make two seperate functions for copying config file, and converting old config file

function copyDefaultConfig($datadir)
{

    $default_config_file = __DIR__ . "/../../php/functions/default.json";
    $new_config_file = $datadir . 'config.json';
    $old_config_file = __DIR__ . "/../../config/config.php";
    $old_config_file_renamed = __DIR__ . "/../../config/config.old.php";
    $old_config_file_note = __DIR__ . "/../../config/remove_this_dir.txt";

    appendLog("Logarr is copying the default config file to the new data directory: " . $new_config_file);

    if (is_file($old_config_file) && !is_file($new_config_file)) {
        appendLog("Logarr legacy config file detected - attempting to convert");
        include_once($old_config_file);

        //Copy default files if conversion fails:

        if ((!isset($config) || empty($config)) || !isset($logs)) {
            phpLog($phpLogMessage = "Logarr ERROR: Legacy config file detected, failed to convert");
            appendLog("ERROR: Logarr legacy config file detected and failed to convert. The default config file will be created.");

            $copyDefaults = copy($default_config_file, $new_config_file);

            appendLog("Logarr created new default config file (Legacy config file conversion failed): " . $new_config_file);
            appendLog("Logarr Setup is complete!");

            //TODO:  Add config file and dir fail note
            //TODO:  Add datadir and data dir warning (From Monitorr functions)

            return true;
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
                array_push(
                    $old_config_converted['logs'],
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
        appendLog("Logarr converted legacy config file to new config file: " . $new_config_file);

        //if (unlink($old_config_file)) {
        if (rename($old_config_file, $old_config_file_renamed)) {
            appendLog("Logarr legacy config file has been converted and renamed to config.old.php. The '/assets/config' directory and all contents can be safely removed. ");
            file_put_contents($old_config_file_note, "Logarr legacy config file was converted to new format and renamed to config.old.php, this file and the 'config' directory can be safely removed.");
        } else {
            appendLog("WARNING: Logarr legacy config file was converted to new format, however, could not be renamed. The 'assets/config' directory and all contents can be safely removed");
            file_put_contents($old_config_file_note, "Logarr legacy config file was converted to new format, however, could not be renamed.  This file and the 'config' directory can be safely removed.");
        }

        appendLog("Logarr Setup is complete!");
    } else {
        $copyDefaults = copy($default_config_file, $new_config_file);
        appendLog("Logarr created new default config file: " . $new_config_file);
        appendLog("Logarr Setup is complete!");
    }

    return $copyDefaults;
}
