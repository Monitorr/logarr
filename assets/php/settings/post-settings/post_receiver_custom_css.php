<?php
include('assets/php/functions.php');
include('assets/php/auth_check.php');
if (isset($_POST) && !empty($_POST)) {
	// saving sample text to file (it doesn't include validation!)
	file_put_contents('../../../data/custom.css', $_POST['css']);

	die('success');
}
