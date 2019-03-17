<?php
include(__DIR__ . '/functions.php');
echo json_encode(array("authentication" => $GLOBALS["authentication"]));