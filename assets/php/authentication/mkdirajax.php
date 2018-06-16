<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_POST['datadir'])) exit();

$datadir_file = __DIR__ . '/../../data/datadir.json';
$datadir_file_fail = __DIR__ . '/../../data/datadir.fail.txt';

echo '<div class="reglog">';
echo '<div id="loginmessage">';
echo '<br>';

print_r('Form submitted: create data directory: ');
var_dump($_POST['datadir']);
echo "<br>";
print_r('Server received: create data directory:  ');
var_dump($_POST['datadir']);
echo "<br>";
print_r('Server attempting to create data directory:  ');
var_dump($_POST['datadir']);

echo '</div>';
echo '</div>';

$datadir = $_POST['datadir'];
file_put_contents($datadir_file, json_encode($_POST));

// To create the nested structure, the $recursive parameter
// to mkdir() must be specified.

if (!mkdir($datadir, 0777, FALSE)) {

	echo '<div class="reglog">';
	echo "<br>";
	echo '<div id="loginerror">';
	var_dump(!mkdir($datadir));
	echo "<br><br>";
	echo "Failed to create directory: $datadir";
	echo '</div>';
	echo '</div>';

	rename($datadir_file, $datadir_file_fail);
	file_put_contents($datadir_file_fail, $json_encode($_POST));
	die;
} else {

	echo '<div class="reglog">';
	echo '<div id="dbmessagesuccess">';
	echo "<br>";
	echo "Directory created successfully:";
	echo '<div>';
	echo '<div>';

	echo realpath($datadir);


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

		// Create users.db:

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

			file_put_contents($datadir_file, "Failed to create sqlite database in: $structure");
			rename($datadir_file, $datadir_file_fail);
		} else {

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
			echo "Logarr data directory creation complete. You can now create a user below.";
			echo '</div>';

			echo '</div>';

			if (is_file($datadir_file_fail)) unlink($datadir_file_fail);

			// TODO: Temporary OLD config file removal // CHANGE ME //

		}
	}
}

echo "<br>";
exit;