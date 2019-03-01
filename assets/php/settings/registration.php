<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../../css/logarr.css" rel="stylesheet">
    <link type="text/css" href="../../data/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646"/>
    <meta name="theme_color" content="#464646"/>

    <link rel="icon" type="image/png" href="../../../favicon.png">

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/jquery.blockUI.js" async></script>

    <title>
		<?php
		$title = $GLOBALS['preferences']['sitetitle'];
		echo $title . PHP_EOL;
		?>
        | Registration
    </title>
</head>

<body>

<div id="registration-content"></div>
<script>
    $("#registration-content").load("?action=register");
</script>

 <!-- CHANGE ME:  DO WE NEED THIS: ?? -->
<!-- <script src="../../js/update-settings.js" async></script> -->

</body>

</html>