<?php
include('functions.php');
include_once('auth_check.php');
//Check if user is logged in:

if (!$authenticator->getUserLoginStatus()) {
    echo "false";
} else {
    if ($authenticator->getUserLoginStatus()) {
        echo "true";
        //TODO Returns error?
        // appendLog(
        //     $somecontent = "User is logged in"
        // );
    } else {
        echo "false";
    };
};