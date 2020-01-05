<?php

// TODO: / sync vars & functions with auth_check.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('error_reporting', E_ALL);
error_reporting(E_ALL);

echo "<br>";
echo "Logarr data dir & auth debug:";
echo "<br>";

// TODO / Remove:
// function isRelativePath($path) {
//     if (substr($path, 0, 2) === "./") return true;
//     if (substr($path, 0, 3) === "../") return true;
//     return false;
// }

function isRelativePath($path)
{
    if (substr($path, 0, 2) === "./") {
        return true;
    }
    if (substr($path, 0, 3) === "../") {
        return true;
    }
    return false;
}

if (is_readable(__DIR__ . "/../../data/datadir.json")) {

    echo "<br>";
    echo "is_readable (datadir.json): TRUE";
    echo "<br>";

    $datadir_file = __DIR__ . "/../../data/datadir.json";
    global $datadir_file;
} else {
    echo "<br>";
    echo "is_readable (datadir.json): FALSE";
    echo "<br>";
    //return false;
}

// Data Dir Path (Decode) ($datadir):
if (is_readable($datadir_file)) {

    $str = file_get_contents($datadir_file);
    $json = json_decode($str, true);

    if (!isset($json['datadir'])) {
        echo "<br>";
        echo "is_file datadir_file & DECODE (datadir.json): FALSE";
        echo "<br>";
    } else {
        echo "<br>";
        echo "is_file datadir_file & DECODE (datadir.json): TRUE";
        echo "<br>";

        $datadir = $json['datadir'];
        global $datadir;

        if (isRelativePath($datadir)) {
            $datadir = __DIR__ . DIRECTORY_SEPARATOR . $datadir;
        }
    }
}

// Data dir exists (datadir):
if (is_readable($datadir)) {

    $datadir = rtrim($datadir, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    global $datadir;

    echo "<br>";
    echo "file_exists datadir: TRUE";
    echo "<br>";
    // return true;

} else {
    echo "<br>";
    echo "file_exists datadir: FALSE";
    echo "<br>";
}

// User PDO database exists in Data dir ($datafile &  $db_sqlite_path):
if (is_readable($datadir . "users.db")) {

    $db_type = "sqlite";
    $datafile = $datadir . 'users.db';
    $db_sqlite_path = $datafile;

    echo "<br>";
    echo "file_exists datafile (users.db): TRUE";
    echo "<br>";
    echo "file_exists db_sqlite_path (users.db): TRUE";
    echo "<br>";

    // Outputs the content of the users.db file:
    // create new database connection
    $db_connection = new PDO($db_type . ':' . $db_sqlite_path);

    // query
    $sql = 'SELECT * FROM users';

    // execute query
    $query = $db_connection->prepare($sql);
    $query->execute();

    // show all the data from the "users" table inside the database
    var_dump($query->fetchAll());

    //return true;

} else {
    echo "<br>";
    echo "file_exists datafile (users.db): FALSE";
    echo "<br>";
    echo "file_exists db_sqlite_path (users.db): FALSE";
    echo "<br>";
}

// User Config file present in data dir and has authentication keys (Decode) ($config_file):
if (is_readable($datadir . "config.json")) {

    $config_file = $datadir . "config.json";

    if (!is_readable($config_file)) {
        echo "<br>";
        echo "is_readable (config.json): FALSE";
        echo "<br>";
    } else {
        echo "<br>";
        echo "is_readable (config.json): TRUE";
        echo "<br>";
    }

    $str_config = file_get_contents($config_file);
    $json_config = json_decode($str_config, true);
    $authentication = $json_config['authentication']['settingsEnabled'];

    if (!isset($authentication)) {
        echo "<br>";
        echo "is_readable & DECODE (config.json): FALSE";
        echo "<br>";
    } else {
        echo "<br>";
        echo "is_readable & DECODE (config.json): TRUE";
        echo "<br>";
    }
} else {
    echo "<br>";
    echo "is_readable & DECODE (config.json): FALSE";
    echo "<br>";
}

function debug_function()
{

    global $datadir_file;
    global $str;
    global $datadir;
    global $datafile;
    global $db_sqlite_path;
    global $config_file;
    global $str_config;
    global $authentication;

    echo "<br>";
    echo "datadir_file: " . $datadir_file;
    echo "<br>";
    echo "json str: " . $str;
    echo "<br>";
    echo "datadir: " . $datadir;
    echo "<br>";
    echo "datafile: " . $datafile;
    echo "<br>";
    echo "db_sqlite_path: " . $db_sqlite_path;
    echo "<br>";
    echo "config_file: " . $config_file;
    echo "<br>";
    // echo "json str: " . $str_config;
    //     echo "<br>";
    echo "authentication | settingsEnabled: " . $authentication;
    echo "<br>";
    echo "<br>";
}

debug_function();
