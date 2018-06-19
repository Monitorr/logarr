<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$datadir_file = __DIR__ . '/../../data/datadir.json';
$datadir_file_fail = __DIR__ . '/../../data/datadir.fail.txt';
$db_file_fail = __DIR__ . '/../../data/db.fail.txt';

echo '<div class="reglog">';
echo '<div id="loginmessage">';
echo '<br>';

print_r('Form submitted:  create user database: ');
var_dump($_POST['dbfile']);
echo "<br>";
print_r('Server received: create user database:  ');
var_dump($_POST['dbfile']);
echo "<br>";
print_r('Server attempting to create user database:  ');
var_dump($_POST['dbfile']);

echo '</div>';
echo '</div>';

$config_file = $datadir . 'config.json';


// Check if user json data files are in data directory before creating user database:

if (!is_file($config_file)) {


	//$structure = $datadir['datadir'];

	echo "<br> <br>";

	echo '<div id="loginmessage">';
	echo "User JSON files NOT detected in data directory. ";
	echo $datadir;
	echo "<br> <br>";
	echo '<div>';

	echo "<br>";

	// Copy default json files to user spcified data dir:

	echo "<br> <br>";

	echo '<div id="loginmessage">';
	echo "Copying default data files to user specified data dir:";
	echo "<br> <br>";
	echo '<div>';

	$default_config_file = __DIR__ . "/../../data/default.json";
	$new_config_file = $datadir . 'config.json';

	if (!copy($default_config_file, $new_config_file)) {
		echo '<div class="reglog">';
		echo '<div id="loginerror">';
		echo "failed to copy $default_config_file...\n";
		echo '</div">';
		echo '</div">';

		file_put_contents($datadir_file, "Failed to copy default json files to: $structure");
		rename($datadir_file, $datadir_file_fail);

		die;
	} else {

		echo '<div class="reglog">';
		echo '<div id="dbmessagesuccess">';
		echo "Default data file succesfully copied to user data dir:";
		echo "<br>";
		echo realpath($new_config_file);
		echo '</div>';
		echo '</div>';

		// Create user database:

		// check if user database exists, if true rename //

		$db_file_new = $datadir . 'users.db';
		$db_file_old = $datadir . 'users.db.old';

		if (is_file($db_file_new)) { //check if file exists
			rename($db_file_new, $db_file_old); //rename file if does exist
			echo '<div class="reglog">';
			echo '<div id="loginmessage">';
			echo "current user database renamed to: $db_file_old";
			echo "<br>";
			echo "creating new user database: $db_file_new";
			echo '</div">';
			echo '</div>';
		}


		//Wait for two seconds for old user database file rename before creating a new
		sleep(2);
		//echo "Done\n";

	}

}
// Create user database:


// check if user database exists, if true rename //

$db_file_new = $datadir . 'users.db';
$db_file_old = $datadir . 'users.db.old';

if (is_file($db_file_new)) { //check if file exists
	rename($db_file_new, $db_file_old); //rename file if does exist
	echo '<div class="reglog">';
	echo '<div id="loginmessage">';
	echo "current user database renamed to: $db_file_old";
	echo "<br>";
	echo "creating new user database: $db_file_new";
	echo '</div">';
	echo '</div>';
}

//Wait for two seconds for old user database file rename before creating a new
sleep(2);
//echo "Done\n";

// Create users.db: //

// config
$db_type = "sqlite";
$db_sqlite_path = $datadir . 'users.db';
// create new database file / connection (the file will be automatically created the first time a connection is made up)
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);
// create new empty table inside the database (if table does not already exist)
$sql = 'CREATE TABLE IF NOT EXISTS `users` (
                            `user_id` INTEGER PRIMARY KEY,
                            `user_name` varchar(64),
                            `user_password_hash` varchar(255),
                            `user_email` varchar(64));
                            `auth_token` varchar(64));
                            CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);
                            CREATE UNIQUE INDEX `user_email_UNIQUE` ON `users` (`user_email` ASC);
                            ';

// execute the above query

$query = $db_connection->prepare($sql);
$query->execute();

echo "<br>";

if (!$query) {

	echo '<div class="reglog">';
	echo '<div id="loginerror">';
	echo "failed to create user database";
	echo '</div">';
	echo '</div>';

	$fp = fopen($db_file_fail, 'w');
	fwrite($fp, "Failed to create sqlite database in: $datadir");
	fclose($fp);

} else {

	$monitorrcwd = $datadir . 'monitorr_install_path.txt';
	$fp = fopen($monitorrcwd, 'w');
	fwrite($fp, "Monitorr application install path: " . realpath('../../../'));
	fclose($fp);

	echo '<div class="reglog">';

	echo '<div id="dbmessagesuccess">';
	echo "User database creation complete: ";
	echo realpath($db_sqlite_path);
	echo '</div>';
	echo "<br>";

	echo '<div id="dbmessagesuccess">';
	echo "All required data files succesfully copied to user data dir:";
	echo "<br>";
	echo realpath($datadir);
	echo '</div>';
	echo "<br>";

	echo '<div id="loginmessage">';
	echo "Monitorr data directory creation complete. You can now create a user below.";
	echo '</div>';

	echo '</div>';


	if (is_file($db_file_fail)) unlink($dbfail);
}

echo "<br>";

// ...

exit;

?>