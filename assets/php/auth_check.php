<?php

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
	public $feedback = ""; //
	/**
	 * @var string Path of the database file (create this with _install.php)
	 */
	//private $db_sqlite_path = "../data/users.db";
	/**
	 * @var string Type of used database (currently only SQLite, but feel free to expand this with mysql etc)
	 */
	private $db_type = "sqlite";
	/**
	 * @var object Database connection
	 */
	private $db_connection = null;
	/**
	 * @var bool Login status of user
	 */
	private $user_is_logged_in = false;

	/**
	 * Does necessary checks for PHP version and PHP password compatibility library and runs the application
	 */
	public function __construct()
	{
		if (is_file(__DIR__ . "/../data/datadir.json")) {
			$str = file_get_contents(__DIR__ . "/../data/datadir.json");
			$json = json_decode($str, true);
			$datadir = $json['datadir'];
			$this->datadir = $datadir;
			$datafile = $datadir . 'users.db';
			$db_sqlite_path = $datafile;
			$this->db_sqlite_path = $db_sqlite_path;
		}
		if (isset($_GET["action"]) && $_GET["action"] == "config") {
			$this->doRegistration();
			$this->showPageConfiguration();
			exit();
		}
		if ($this->performMinimumRequirementsCheck()) {
			$this->runApplication();
		}
	}

	/**
	 * The registration flow
	 * @return bool
	 */
	private function doRegistration()
	{
		if ($this->checkRegistrationData()) {
			if ($this->createDatabaseConnection()) {
				$this->createNewUser();
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
		} elseif (strlen($_POST['user_password_new']) < 6) {
			$this->feedback = "Password has a minimum length of 6 characters";
		} elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
			$this->feedback = "Username cannot be shorter than 2 or longer than 64 characters";
		} elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
			$this->feedback = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
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
	 * Creates a PDO database connection (in this case to a SQLite flat-file database)
	 * @return bool Database creation success status, false by default
	 */
	private function createDatabaseConnection()
	{
		try {
			$this->db_connection = new PDO($this->db_type . ':' . $this->db_sqlite_path);
			return true;
		} catch (PDOException $e) {
			$this->feedback = "PDO database connection problem: " . $e->getMessage();
		} catch (Exception $e) {
			$this->feedback = "General problem: " . $e->getMessage();
		}
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
		$user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
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
			$this->feedbackerror = "Sorry, that username / email is already taken. Please choose another one.";
		} else {
			if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
				$token = bin2hex(random_bytes(32));
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
				$this->feedbacksuccess = '<b>User credentials have been created successfully.</b><br><b><a href="settings.php" class="btn btn-primary" title="Logarr Settings">Log in here</a> </b>';
				return true;
			} else {
				$this->feedbackerror = "ERROR: registration failed. Please check the webserver PHP logs and try again.";
			}
		}
		// default return
		return false;
	}

	/**
	 * Simple demo-"page" with the registration form.
	 * In a real application you would probably include an html-template here, but for this extremely simple
	 * demo the "echo" statements are totally okay.
	 */
	private function showPageConfiguration()
	{
		include_once('authentication/configuration.php');
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
			echo "Sorry, Simple PHP Login does not run on a PHP version older than 5.3.7 !";
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
		// check is user wants to see register page (etc.)
		if (isset($_GET["action"]) && $_GET["action"] == "register") {
			if (isset($GLOBALS['authentication']) && isset($GLOBALS['authentication']['registrationEnabled']) && $GLOBALS['authentication']['registrationEnabled'] == "true") {
				$this->doRegistration();
				$this->showPageRegistration();
			} else {
				$this->showPageUnauthorized();
			}
			exit();
		} else if (isset($_GET["action"]) && $_GET["action"] == "logout") {
			$this->doLogout();
			exit();
		} else {
			// check for possible user interactions (login with session/post data or logout)
			$this->performUserLoginAction();

			//check which page we're on
			$urlParts = explode("/", strtok($_SERVER["REQUEST_URI"], '?'));
			$currentPage = strtok(end($urlParts), ".");
			if (($currentPage == "index" || $currentPage == "")) {
				if($GLOBALS['authentication']['logsEnabled'] == "true") {
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
				if($GLOBALS['authentication']['settingsEnabled'] == "true") {
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
	 * Simple demo-"page" with the registration form.
	 * In a real application you would probably include an html-template here, but for this extremely simple
	 * demo the "echo" statements are totally okay.
	 */
	private function showPageRegistration()
	{
		include_once('authentication/register.php');
	}

	/**
	 * Simple unauthorized page
	 */
	private function showPageUnauthorized()
	{
		include_once('authentication/unauthorized.php');
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
	}

	/**
	 * Handles the flow of the login/logout process. According to the circumstances, a logout, a login with session
	 * data or a login with post data will be performed
	 */
	private function performUserLoginAction()
	{
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
		$this->user_is_logged_in = true; // ?
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
				return true;
			} else {
				$this->feedback = "Invalid password";
			}
		} else {
			$this->feedback = "User does not exist";
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
				return true;
			} else {
				$this->feedback = "Invalid Auth Token";
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
}

// run the application
$authenticator = new OneFileLoginApplication();
?>