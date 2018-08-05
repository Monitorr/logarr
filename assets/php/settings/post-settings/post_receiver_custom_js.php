<?php
if (isset($_POST) && !empty($_POST)) {
	// saving sample text to file (it doesn't include validation!)
	file_put_contents('../../../data/custom.js', $_POST['js']);

	die('success');
}