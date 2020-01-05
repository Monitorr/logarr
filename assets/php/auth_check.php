<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

//error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);

error_reporting(E_ERROR);
ini_set('error_reporting', E_ERROR);

/**
* Class OneFileLoginApplication
*
* An entire php application with user registration, login and logout in one file.
* Uses very modern password hashing via the PHP 5.5 password hashing functions.
* This project includes a compatibility file to make these functions available in PHP 5.3.7+ and PHP 5.4+.
*
* @author Panique
* @link https://github.com/panique/php-login-one-file/
* @license http://opensource.org/licenses/MIT MIT License
*/
class OneFileLoginApplication
{
	/**
		* @var string System messages, likes errors, notices, etc.
		*/
		public $feedback = "";
		/**
		* @var string Path of the data dir definition file (datadir.json)
		*/
		public $datadir_file = "";
		/**
		* @var string Parsed data dir path in the data dir definition file (datadir.json)
		*/
		public $datadir_path = "";
		/**
		* @var string Path of the data dir
		*/
		public $datadir = "";
		/**
		* @var string Path of the database flat file (users.db)
		*/
		private $datafile = "";
		/**
		* @var string Path of the user PDO database file (users.db)
		*/
		private $db_sqlite_path = "";
		/**
		* @var string Type of used database (currently only SQLite, but feel free to expand this with mysql etc)
		*/
		private $db_type = "sqlite";
		/**
		 *@var string Path of the user config file in the data dir (config.json)
		*/
		private $config_file_path = "";
		/**
		* @var object Database connection
		*/
		private $db_connection = null;
		/**
		* @var bool Login status of user
		*/
		private $user_is_logged_in = false;
		/**
		* @var bool Setup status.
		*/
		private $is_setup = false;
		/**
		* @var array Copy of authentication settings.
	*/

	private $auth_settings = false;

	/**
		* Does necessary checks for PHP version and PHP password compatibility library and runs the application
		*/
	public function __construct()
	{
		if ($this->isSetup()) {
			// $this->appendLog("(construct) SUCCESS: Logarr Setup is complete");
			$config_file = $this->datadir . '/config.json';
			$this->auth_settings = json_decode(file_get_contents($config_file), 1)['authentication'];
		} else {
			$this->appendLog("(construct) WARNING: Logarr Setup is NOT complete");
			$urlParts = explode("/", strtok($_SERVER["REQUEST_URI"], '?'));
			$currentPage = strtok(end($urlParts), ".");
			if ($currentPage != "setup") {
				// TODO: / BUG / This works for index and settings, but not for nested pages like /assets/php/settings/authentication.php
				// Determine how many directories we need to go up
				// appendLog("(isSetup) WARNING: Logarr Setup is not complete");
				header("Location: setup.php");
			}
		}

		if ($this->performMinimumRequirementsCheck()) {
			$this->runApplication();
		}
	}

	/**
	 * Appends a log entry to the Logarr log file
	 * @param $logentry
	 * @return string
	 */
	private function appendLog($logentry)
	{
		ini_set('error_reporting', E_ERROR);
		$logfile = 'logarr.log';
		$logdir = __DIR__ . '/../data/logs/';
		$logpath = $logdir . $logfile;
		//$logentry = "Add this to the file";
		$date = date("D d M Y H:i T ");

		if (is_readable($logdir)) {
			$oldContents = file_get_contents($logpath);
			if (file_put_contents($logpath, $oldContents . $date . " | " . $logentry . "\r\n") === false) {
				$this->phpLog("Logarr ERROR: Failed writing to Logarr log file");
				return "ERROR writing to Logarr log file";
			}
		} else {
			if (!mkdir($logdir)) {
				$this->phpLog("Logarr ERROR: Failed to create Logarr log directory");
				return "ERROR: Failed to create Logarr log directory";
			} else {
				$this->appendLog("Logarr log directory created: " . $logdir);
				$this->appendLog($logentry);
				return "Logarr log directory created";
			}
		}
		return "Success";
	}

	/**
	 * Append Logarr errors to webserver's PHP error log file IF defined in php.ini:
	 * @param $phpLogMessage
	 * @return string
	 */
	private function phpLog($phpLogMessage)
	{
		if (!get_cfg_var('error_log')) { } else {
			error_log($errstr = $phpLogMessage);
		}
	}

	/**
	 * Get's the user's ip address
	 * @return mixed
	 */
	public function getUserIpAddr()
	{
		ini_set('error_reporting', E_ERROR);

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			//ip from share internet
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			//ip pass from proxy
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}

	/**
		* Checks if Logarr is setup
		* @return bool
		*/
	public function isSetup()
	{
		if (!$this->datadirfileExist()) return false;
		if (!$this->isDatadirSetup()) return false;
		if (!$this->databaseExists()) return false;
		if (!$this->doesUserExist()) return false;
		if (!$this->configFileExists()) return false;
		// $this->appendLog("isSetup(): TRUE | SUCCESS: Logarr is setup");
		return true;
	}
	
	/**
		* Checks if data dir definition file exists (datadir.json)
		* Checks for proper JSON value for $datadir
		* Sets $datadir_file & $datadir_path
		* @var string Path of the datadir definition file
	*/
	public function datadirfileExist()
	{
		if (is_readable(__DIR__ . "/../data/datadir.json")) {

			$datadir_file = __DIR__ . "/../data/datadir.json";
			$this->datadir_file = $datadir_file;

			$str = file_get_contents($datadir_file);
			$datadir_file_json = json_decode($str, true);

			if (!isset($datadir_file_json['datadir'])) {
				$this->appendLog("ERROR: datadir.json exists but is NOT valid");
				return false;
			} else {
				$datadir_path = $datadir_file_json['datadir'];
				$this->datadir_path = $datadir_path;
				
				// $this->appendLog("datadirfileExist4 : TRUE | is_readable : datadir.json: datadir_path: " . $datadir_path);
				return true;
			}
			} else {
				$this->appendLog("datadirfileExist | ERROR: datadir.json does NOT exist");
				// TODO / BUG / does not work:
				// header("Location: setup.php");
				return false;
			}
	}

	/**
		* Checks if a path is relative
		* @param $path
		* @return bool
	*/
	private function isRelativePath($path)
	{
		if (substr($path, 0, 2) === "./") return true;
		if (substr($path, 0, 3) === "../") return true;
		return false;
	}

	/**
		 * Checks if datadir exists
		 * Sets $datadir var
		 * @return bool
	*/
	public function doesDataDirExist()
	{
		
		$datadir_path = $this->datadir_path;

		if (is_readable($datadir_path)) {
			// $this->appendLog("doesDataDirExist(): TRUE | is_readable datadir_path : " . $datadir_path);

			if ($this->isRelativePath($datadir_path)) {
				$datadir_path = __DIR__ . DIRECTORY_SEPARATOR . $datadir_path;
				// $this->appendLog("doesDataDirExist(): TRUE | isRelativePath datadir_path : " . $datadir_path);
			}

			if (is_readable($datadir_path)) {
				$datadir_path = rtrim($this->datadir_path, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
				// $this->appendLog("doesDataDirExist() : TRUE | is_readable & rtrim: datadir_path: " . $datadir_path);

				if (is_readable($datadir_path)) {
					$datadir = $datadir_path;
					$this->datadir = $datadir;
					// $this->appendLog("doesDataDirExist() : TRUE | is_readable: datadir_path: " . $datadir_path);
					return true;
				}
			}
		} else {
			return "0";
			$this->appendLog("doesDataDirExist() | ERROR: The data dir does NOT exist: " . $datadir_path);
			return false;
		}
	}

	/**
		* Checks if the datadir exists and sets some necessary paths
		* @return bool
	*/
	public function isDatadirSetup()
    {
		// TODO / TESTING / REMOVE / Old method:

			// $str = file_get_contents($this->datadir_file);
			// $json = json_decode($str, true);

			// if(!isset($json['datadir'])) return false;
			// $datadir = $json['datadir'];


		if ($this->doesDataDirExist()) {

			$datadir = $this->datadir;

			if ($this->isRelativePath($datadir)) {
				$datadir = __DIR__ . DIRECTORY_SEPARATOR . $datadir;
			}
			if (is_readable($datadir)) {
				$datadir = rtrim($datadir, "\\/" . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
				$datadir = $datadir;

				if (is_readable($datadir)) {
					$datafile = $datadir . 'users.db';
					$this->db_sqlite_path = $datafile;
					// $this->appendLog("isDatadirSetup : TRUE | is_readable: datadir | SUCCESS: Logarr data dir is established: " . $datadir);
					return true;
				} else {
					$this->appendLog("(isDatadirSetup) | ERROR: Logarr data dir is NOT established: " . $datadir);
					return false;
				}
			}

			$this->appendLog("(isDatadirSetup) | WARNING: Logarr data dir is NOT established: " . $datadir);
			return false;

		} else {
			//$this->appendLog("(isDatadirSetup) | ERROR: The user data dir is NOT set up: " . $this->datadir);
			return false;
		}
	}

	/**
		* Checks if the database file exists
		* @return bool
	*/
	public function databaseExists()
	{
		$db_sqlite_path = $this->db_sqlite_path;

		if (is_readable($db_sqlite_path)) {
			// $this->appendLog("databaseExists : TRUE | is_readable: db_sqlite_path: " . $db_sqlite_path);
			return true;
		} else {
			$this->appendLog("(databaseExists) | ERROR: The PDO database file (users.db) does NOT exist in the data dir: " . $this->db_sqlite_path);
			return false;
		}
	}

	/**
	 * Checks if there's already a user
	 * @return bool
	 */
	public function doesUserExist()
	{
		if ($this->doesDataDirExist()) {

			if ($this->createDatabaseConnection()) {
				$sql = "SELECT COUNT(*) as count FROM users;";
				$query = $this->db_connection->prepare($sql);

				$query->execute();
				$result = $query->fetch();
				$rows = $result["count"];
				if ($rows > 0) {
					//$this->appendLog("doesUserExist | TRUE | SUCCESS: User is established in the PDO database file (users.db)");
					return true;
				}

				$this->appendLog("doesUserExist : Warning: User NOT established in the PDO database file (users.db)");
				return "0";
				return false;
			}
		} else {
			//$this->appendLog("(doesUserExist) | ERROR: Failed to create user becuase the data dir is not setup");
			return "0";
			return false;
		}
	}

	/**
	 * 
	 * Creates a PDO database connection to a SQLite flat-file database (users.db)
	 * If the file does not exist, it will be automatically created on first connection.
	 * @return bool Database creation success status, false by default
	 */
	private function createDatabaseConnection()
	{
		// $this->appendLog("(createDatabaseConnection1) | Logarr is connecting to the user PDO database: " . $this->db_sqlite_path);

		try {

			// TODO / Remove / old meathod:
				//$this->appendLog("(createDatabaseConnection1) | ERROR: Failed connection to the user PDO database (users.db). Logarr is creating a new user PDO database.");
				//if (!$this->isDatadirSetup()) return false;
				// $this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);
				// $this->databaseSetup();
				// return true;

			//TODO **IMPORTANT **/ this can be moved to a diff function and only executed if 0 users exists or file does not exist. / Move to databasesetup?

			$createDatafile = $this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);

			if ($createDatafile) {

				// $this->appendLog("(createDatabaseConnection) | SUCCESS: Logarr connected to the PDO database : " . $this->db_sqlite_path);
				$this->databaseSetup();
				return true;

			} else {

				$this->appendLog("(createDatabaseConnection) | ERROR: Failed connection to create the  PDO database (users.db) : " . $this->db_sqlite_path);
				return false;
			}
			
		} catch (PDOException $e) {
			$this->feedback = "Logarr PDO database connection ERROR: " . $e->getMessage();
			$this->phpLog("Logarr ERROR: PDO database connection failure");
			$this->appendLog("ERROR: PDO database connection failure");
		} catch (Exception $e) {
			$this->feedback = "Logarr PDO database general failure: " . $e->getMessage();
			$this->phpLog("Logarr ERROR: PDO database general failure");
			$this->appendLog("ERROR: PDO database general failure");
		}
		return false;
	}

	/**
	 * Makes sure the database is setup
	 * @return bool
	 */
	private function databaseSetup() {
		// create new empty table inside the database (if table does not already exist)
		
		$sql = 'CREATE TABLE IF NOT EXISTS `users` (
			`user_id` INTEGER PRIMARY KEY,
			`user_name` varchar(64),
			`user_password_hash` varchar(255),
			`user_email` varchar(64),
			`auth_token` varchar(64));
			CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);
			CREATE UNIQUE INDEX `user_email_UNIQUE` ON `users` (`user_email` ASC);
			';

		$db_sqlite_path = $this->db_sqlite_path;

		// create empty table in database file:
		$query = $this->db_connection->prepare($sql);


		// TODO / BUG / IMPORTANT // if users.db exists but contents are not valid, will throw error:
		$query->execute();

			//  TODO / testing / REMOVE / old method:
			// if (!$query) {
			// 	echo "<script>console.log('%cERROR: users.db is invalid','color: #FF0000;');</script>";
			// 	$this->phpLog("Logarr ERROR: PDO database setup!");
			// 	$this->appendLog( "ERROR: PDO database setup!");
			// 	//return false;
			// } else {
			// 	//echo "<script>console.log('%cLogarr created new PDO databas','color: #FF0000;');</script>";
			// 	$this->appendLog( "Logarr created new PDO database!");
			// 	//return true;
			// }

		if (is_readable($db_sqlite_path)) {
			//echo "<script>console.log('%cSUCCESS: Logarr PDO database exists','color: #FF0000;');</script>";
			//$this->appendLog("databaseSetup | TRUE | is_readable: db_sqlite_path  | SUCCESS: Logarr PDO database exists: db_sqlite_path: " . $db_sqlite_path);
			return true;

		} else {
			echo "<script>console.log('%cWARNING: PDO database has not been setup','color: #FF0000;');</script>";
			$this->appendLog("(databaseSetup) | WARNING: PDO database has not been setup");
			return false;
		}
	}

	/**
	 * Checks if all config keys are accounted for in the user data dir (config.json)
	 * Sets $config_file_path var
	 * @return bool
	 */
	public function configFileExists()
	{
		$config_file_path = $this->datadir . DIRECTORY_SEPARATOR . "config.json";
		if (is_readable($config_file_path)) {
			$this->config_file_path = $config_file_path;
			// $this->appendLog("configFileExists : TRUE | is_readable: config_file_path: " . $config_file_path);
			return true;
		}
		$this->appendLog("(configFileExists) | ERROR: The Logarr config file (config.json) is not accessible in the user data dir: " . $config_file_path);
		return false;
	}
	
	/**
	 * Performs a check for minimum requirements to run this application.
	 * Does not run the further application when PHP version is lower than 5.3.7
	 * Does include the PHP password compatibility library when PHP version lower than 5.5.0
	 * (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
	 * @return bool Success status of minimum requirements check, default is false
	 */
	private function performMinimumRequirementsCheck()
	{
		if (version_compare(PHP_VERSION, '5.3.7', '<')) {
			echo "ERROR: Logarr does not run on PHP version older than 5.3.7 !";
			phpLog("Logarr ERROR: Logarr does not run on PHP version older than 5.3.7");
			appendLog("ERROR: Logarr does not run on PHP version older than 5.3.7");
		} elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
			require_once(__DIR__ . "/../libraries/password_compatibility_library.php");
			return true;
		} elseif (version_compare(PHP_VERSION, '5.5.0', '>=')) {
			return true;
		}
		// default return
		return false;
	}

	/**
	 * This is basically the controller that handles the entire flow of the application.
	 */
	public function runApplication()
	{
		// start the session, always needed!
		$this->doStartSession();
		// check for possible user interactions (login with session/post data or logout)
		$this->performUserLoginAction();

		//check which page we're on
		$urlParts = explode("/", strtok($_SERVER["REQUEST_URI"], '?'));
		$currentPage = strtok(end($urlParts), ".");
		$homePageURLs = array("index", "", "load-log", "version_check", "sync-config", "time", "download", "unlink", "login-status");

		// check is user wants to see setup page (etc.)
		if ($currentPage == "setup") {
			if (!$this->isSetup()) {
				return true;
			} else {
				if (isset($this->auth_settings) && (!isset($this->auth_settings['setupEnabled']) || $this->auth_settings['setupEnabled'] != "false")) {
					if ($this->getUserLoginStatus()) {
						return true;
					} else {
						// TODO / BUG / IF Logarr is setup and settings and logs auth are disabled and setup access is enabled user is unable to get to setup page:
						header("location: settings.php#setup");
						exit();
					}
				} else {
					$this->showPageUnauthorized();
					exit();
				}
			}
		} else if (in_array($currentPage, $homePageURLs)) {
			if ($this->auth_settings['logsEnabled'] == "true") {
				// show "page", according to user's login status
				if ($this->getUserLoginStatus()) {
					return true;
				} else {
					$this->showPageLoginForm();
					exit();
				}
			} else {
				return true;
			}
		} else if (strpos(strtolower($_SERVER["REQUEST_URI"]), 'settings') !== false) {
			if ($this->auth_settings['settingsEnabled'] == "true") {
				// show "page", according to user's login status
				if ($this->getUserLoginStatus()) {
					return true;
				} else {
					$this->showPageLoginForm();
					exit();
				}
			} else {
				return true;
			}
		} else {
			if ($this->getUserLoginStatus()) {
				return true;
			} else {
				$this->showPageLoginForm();
				exit();
			}
		}
	}

	/**
	 * Simply starts the session.
	 * It's cleaner to put this into a method than writing it directly into runApplication()
	 */
	private function doStartSession()
	{
		if (session_status() == PHP_SESSION_NONE) session_start();
	}

	/**
	 * Handles the flow of the login/logout process. According to the circumstances, a logout, a login with session
	 * data or a login with post data will be performed
	 */
	private function performUserLoginAction()
	{
		if (isset($_GET["action"]) && $_GET["action"] == "logout") {
			$this->doLogout();
			exit();
		}
		if (!empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])) {
			$this->doLoginWithSessionData();
		} elseif (isset($_POST["login"])) {
			$this->doLoginWithPostData();
		} elseif (isset($_COOKIE["Logarr_AUTH"])) {
			$this->doLoginWithCookieData();
		}
	}

	/**
	 * Set a marker (NOTE: is this method necessary ?)
	 */
	private function doLoginWithSessionData()
	{
		$this->user_is_logged_in = true;
		if (!isset($_COOKIE['Logarr_AUTH'])) {
			if ($this->createDatabaseConnection()) {
				// remember: the user can log in with username or email address
				$sql = 'SELECT user_name, user_email, auth_token
                FROM users
                WHERE user_name = :user_name OR user_email = :user_name
                LIMIT 1';
				$query = $this->db_connection->prepare($sql);
				$query->bindValue(':user_name', $_SESSION['user_name']);
				$query->execute();
				$result_row = $query->fetchObject();
				if ($result_row) {
					$cookie_value = $result_row->auth_token;
					setcookie("Logarr_AUTH", $cookie_value, time() + 60 * 60 * 24 * 7, "/"); //store login cookie for 7 days
					$this->phpLog("Logarr warning: Logarr user logged in with session from IP: " . $this->getUserIpAddr());
					$this->appendLog("Logarr user logged in with session from IP: " . $this->getUserIpAddr());
					return true;
				} else {
					$this->feedback = "Invalid Auth Token";
				}
			}
		}
		return true;
	}

	/**
	 * Process flow of login with POST data
	 */
	private function doLoginWithPostData()
	{
		if ($this->checkLoginFormDataNotEmpty()) {
			if ($this->createDatabaseConnection()) {
				$this->checkPasswordCorrectnessAndLogin();
			}
		}
	}

	/**
	 * Validates the login form data, checks if username and password are provided
	 * @return bool Login form data check success state
	 */
	private function checkLoginFormDataNotEmpty()
	{
		if (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {
			return true;
		} elseif (empty($_POST['user_name'])) {
			$this->feedback = "Username field was empty.";
		} elseif (empty($_POST['user_password'])) {
			$this->feedback = "Password field was empty.";
		}
		// default return
		return false;
	}

	/**
	 * Checks if user exits, if so: check if provided password matches the one in the database
	 * @return bool User login success status
	 */
	private function checkPasswordCorrectnessAndLogin()
	{
		// remember: the user can log in with username or email address
		$sql = 'SELECT user_name, user_email, user_password_hash, auth_token
                FROM users
                WHERE user_name = :user_name OR user_email = :user_name
                LIMIT 1';
		$query = $this->db_connection->prepare($sql);
		$query->bindValue(':user_name', $_POST['user_name']);
		$query->execute();
		$result_row = $query->fetchObject();
		if ($result_row) {
			// using PHP 5.5's password_verify() function to check password
			if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {
				// write user data into PHP SESSION [a file on your server]
				$_SESSION['user_name'] = $result_row->user_name;
				$_SESSION['user_email'] = $result_row->user_email;
				$_SESSION['user_is_logged_in'] = true;
				$this->user_is_logged_in = true;
				$cookie_value = $result_row->auth_token;
				setcookie("Logarr_AUTH", $cookie_value, time() + 60 * 60 * 24 * 7, "/"); //store login cookie for 7 days
				$this->phpLog("Logarr warning: Logarr user logged in with credentials from IP: " . $this->getUserIpAddr());
				$this->appendLog("Logarr user logged in with credentials from IP: " . $this->getUserIpAddr());
				return true;
			} else {
				$this->feedback = "Invalid password";
				$this->phpLog("Logarr ERROR: Login attempt: Invalid password from IP: " . $this->getUserIpAddr());
				$this->appendLog("Logarr login attempt: ERROR: Invalid password from IP: " . $this->getUserIpAddr());
			}
		} else {
			$this->feedback = "User does not exist";
			$this->appendLog("Logarr login attempt: ERROR: User does not exist from IP: " . $this->getUserIpAddr());
		}
		// default return
		return false;
	}

	/**
	 * Checks if user exits, if so: check if provided password matches the one in the database
	 * @return bool User login success status
	 */
	private function doLoginWithCookieData()
	{
		if ($this->createDatabaseConnection()) {
			// remember: the user can log in with username or email address
			$sql = 'SELECT user_name, user_email, auth_token
                FROM users
                WHERE auth_token = :auth_token
                LIMIT 1';
			$query = $this->db_connection->prepare($sql);
			$query->bindValue(':auth_token', $_COOKIE['Logarr_AUTH']);
			$query->execute();
			$result_row = $query->fetchObject();
			if ($result_row) {
				// write user data into PHP SESSION [a file on your server]
				$_SESSION['user_name'] = $result_row->user_name;
				$_SESSION['user_email'] = $result_row->user_email;
				$_SESSION['user_is_logged_in'] = true;
				$this->user_is_logged_in = true;
				$cookie_value = $result_row->auth_token;
				setcookie("Logarr_AUTH", $cookie_value, time() + 60 * 60 * 24 * 7, "/"); //store login cookie for 7 days
				$this->phpLog("Logarr warning: Logarr user logged in with cookie from IP: " . $this->getUserIpAddr());
				$this->appendLog("Logarr user logged in with cookie from IP: " . $this->getUserIpAddr());
				return true;
			} else {
				$this->feedback = "Invalid Auth Token";
				$this->appendLog("Logarr login attempt: ERROR: Invalid Auth Token from IP: " . $this->getUserIpAddr());
			}
		}
		// default return
		return false;
	}

	/**
	 * Simply returns the current status of the user's login
	 * @return bool User's login status
	 */
	public function getUserLoginStatus()
	{
		return $this->user_is_logged_in;
	}

	/**
	 * Simple demo-"page" with the login form.
	 * In a real application you would probably include an html-template here, but for this extremely simple
	 * demo the "echo" statements are totally okay.
	 */
	private function showPageLoginForm()
	{
		include_once('authentication/login.php');
	}

	/**
	 * Simple unauthorized page
	 */
	private function showPageUnauthorized()
	{
		include_once('authentication/unauthorized.php');

		$this->appendLog("Logarr warning: Unauthorized page loaded from IP: " . $this->getUserIpAddr());
	}

	/**
	 * Logs the user out
	 */
	private function doLogout()
	{
		$_SESSION = array();
		session_destroy();
		$this->user_is_logged_in = false;
		unset($_COOKIE["Logarr_AUTH"]);
		setcookie("Logarr_AUTH", null, time() - 1, "/");
		$this->feedback = "You were just logged out.";
		header("location: index.php");
		$this->appendLog("Logarr user has logged out");
	}

	/**
	 * The registration flow
	 * @return bool
	 */
	public function doRegistration()
	{
		if ($this->checkRegistrationData()) {
			if ($this->createDatabaseConnection()) {
				if ($this->createNewUser()) {
					return true;
				}
			}
		}
		// default return
		return false;
	}

	/**
	 * Validates the user's registration input
	 * @return bool Success status of user's registration data validation
	 */
	private function checkRegistrationData()
	{
		// if no registration form submitted: exit the method
		if (!isset($_POST["register"])) {
			return false;
		}
		// validating the input
		if (empty($_POST['user_name'])) {
			$this->feedback = "Empty Username";
		} elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
			$this->feedback = "Empty Password";
		} elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
			$this->feedback = "Password and password repeat are not the same";
			$this->appendLog("ERROR: Password and password repeat are not the same.");
		} elseif (strlen($_POST['user_password_new']) < 6) {
			$this->feedback = "Password has a minimum length of 6 characters";
		} elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
			$this->feedback = "Username cannot be shorter than 2 or longer than 64 characters";
			$this->appendLog("ERROR: Username cannot be shorter than 2 or longer than 64 characters.");
		} elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
			$this->feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
			$this->appendLog("ERROR: Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.");
		} elseif (isset($_POST['user_email']) && !empty($_POST['user_email'])) {
			if (strlen($_POST['user_email']) > 64) {
				$this->feedback = "Email cannot be longer than 64 characters";
			} elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
				$this->feedback = "Your email address is not in a valid email format";
			} else {
				return true;
			}
		} else {
			return true;
		}
		// default return
		return false;
	}

	/**
	 * Creates a new user.
	 * @return bool Success status of user registration
	 */
	private function createNewUser()
	{
		// remove html code etc. from username and email
		$user_name = htmlentities($_POST['user_name'], ENT_QUOTES);
		$user_email = isset($_POST['user_email']) ? htmlentities($_POST['user_email'], ENT_QUOTES) : "";
		$user_password = $_POST['user_password_new'];
		// crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 char hash string.
		// the constant PASSWORD_DEFAULT comes from PHP 5.5 or the password_compatibility_library
		$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
		$sql = 'SELECT * FROM users WHERE user_name = :user_name OR (NOT(user_email="") AND user_email = :user_email)';
		$query = $this->db_connection->prepare($sql);
		$query->bindValue(':user_name', $user_name);
		$query->bindValue(':user_email', $user_email);
		$query->execute();
		// As there is no numRows() in SQLite/PDO (!!) we have to do it this way:
		// If you meet the inventor of PDO, punch him. Seriously.
		$result_row = $query->fetchObject();
		
		if ($result_row) {
			$this->feedback = "ERROR: Username / email is already used.";
			$this->appendLog("ERROR: Username / email is already used.");
		} else {
			if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
				try {
					$token = bin2hex(random_bytes(32));
				} catch (Exception $e) {
					$this->appendLog("PHP ERROR: " . $e->getMessage() . " ON LINE " . $e->getLine() . " OF FILE " . $e->getFile());
				}
			} else {
				$token = bin2hex(openssl_random_pseudo_bytes(32));
			}

			$sql = 'INSERT INTO users (user_name, user_password_hash, user_email, auth_token)
                    VALUES(:user_name, :user_password_hash, :user_email, :auth_token)';
			$query = $this->db_connection->prepare($sql);
			$query->bindValue(':user_name', $user_name);
			$query->bindValue(':user_password_hash', $user_password_hash);
			$query->bindValue(':user_email', $user_email);
			$query->bindValue(':auth_token', $token);
			// PDO's execute() gives back TRUE when successful, FALSE when not
			// @link http://stackoverflow.com/q/1661863/1114320
			$registration_success_state = $query->execute();
			if ($registration_success_state) {
				$this->phpLog("Logarr warning: User credentials have been created successfully!");
				$this->appendLog("User credentials have been created successfully!");
				$this->feedback = 'User credentials have been created successfully.';
				return true;
			} else {
				$this->feedback = "ERROR: Registration failed. Check the webserver PHP logs and try again.";
				$this->phpLog("Logarr ERROR (createNewUser): Registration failed.");
				$this->appendLog("ERROR (createNewUser): Registration failed. Check the webserver PHP logs and try again");
			}
		}
		// default return
		return false;
	}
}

// run the application
$authenticator = new OneFileLoginApplication();
