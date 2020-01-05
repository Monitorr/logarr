<?php
if (isset($_POST) && !empty($_POST)) {
    include(__DIR__ . '/../../functions.php');
    include(__DIR__ . '/../../auth_check.php');
    // saving sample text to file (it doesn't include validation!)
    file_put_contents('../../../data/custom.js', $_POST['js']);

    die('success');
}
