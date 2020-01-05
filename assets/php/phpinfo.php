<?php
include('functions.php');
?>

<!-- phpinfo.php -->
<head>

	<meta name="robots" content="NOINDEX, NOFOLLOW">

	<link rel="icon" type="image/png" href="../../favicon.png">

	<title> Logarr | PHP Info </title>
	
	<link rel="stylesheet" href="../css/vendor/jquery-ui.min.css">
	<link rel="stylesheet" href="../css/logarr.css">
	<link rel="stylesheet" href="../data/custom.css">

	<script src="../js/jquery.min.js"></script>
	<script src="../js/vendor/jquery-ui.min.js"></script>

	<style type="text/css">
		body {
			/* padding-top: 0 !important; */
			margin-top: 0;
			background-color: #3d3d3d !important;
		}

		body::-webkit-scrollbar {
			width: .75rem;
			background-color: #252525;
		}

		body::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
			box-shadow: inset 0 0 .25rem rgba(0, 0, 0, 0.3);
			border-radius: .75rem;
			background-color: #252525;
		}

		body::-webkit-scrollbar-thumb {
			border-radius: .75rem;
			-webkit-box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
			box-shadow: inset 0 0 .25rem rgba(0, 0, 0, .3);
			background-color: #8E8B8B;
		}

		a {
			color: white;
		}

		#phpinfo {
			font-size: .5rem !important;
			cursor: default;
		}

		tbody {
			cursor: default;
		}

		table {
			width: 100% !important;
		}

		tr {
			font-size: 1rem !important;
		}

		hr {
			width: 100% !important;
		}

		.v {
			width: 50% !important;
			max-width: 1vw !important;
		}

		h1 {
			font-size: 2em !important;
			color: black;
		}

		h2 {
			font-size: 2em !important;
			color: black;
		}
	</style>
	
</head>

<body>

<?php

ini_set('error_reporting', E_ERROR);

$datadir_json = json_decode(file_get_contents(__DIR__ . '../../data/datadir.json'), 1);
$datadir = $datadir_json['datadir'];
$config_file = $datadir . '/config.json';
$preferences = json_decode(file_get_contents($config_file), 1)['preferences'];


if ($GLOBALS['preferences']['timezone'] == "") {
    date_default_timezone_set('UTC');
    $timezone = date_default_timezone_get();
} else {
    $timezoneconfig = $GLOBALS['preferences']['timezone'];
    date_default_timezone_set($timezoneconfig);
    $timezone = date_default_timezone_get();
}

appendLog("Logarr PHPInfo loaded");

echo "<div id='extensions'>";

echo "<div id='extensiontitle'> Required Extensions: </div> ";

//Check if '/assets/data/' dir is writable:

$myfile = fopen('../data/php-perms-check.txt', 'w+');
$date = date("D d M Y H:i T");


if (!$myfile) {
    echo " <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP write permissions FAIL'>";
    echo "Perms";
    echo "</a>";
    echo "<script>console.log( 'ERROR: PHP write permissions FAIL' );</script>";
} else {
    echo " <div class='extok' title='PHP write permissions OK' >";
    echo "Perms";
    echo "</div>";
    fwrite($myfile, "\r\n" . $date . "\r\n" . "Logarr PHP write permissions OK \r\nThis file can be safely removed, however it will be regenerated every time a user logs into Logarr Settings.");
    fclose($myfile);
}

$str = file_get_contents(__DIR__ . "/../data/datadir.json");
$json = json_decode($str, true);
$datadir = $json['datadir'];
$datafile = $datadir . 'php-perms-check.txt';
$datadirfile = $datafile;

//Check if datadir is present:

if (!file_exists($datadir)) {
    echo "<script>console.log('ERROR: Logarr Datadir not found');</script>";
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='Logarr Datadir NOT found'>";
    echo "Data";
    echo "</a>";
} else {
    echo "<script>console.log('Logarr Datadir found: $datadir ');</script>";

    //Check if datadir is writable:

    $datadircheck = fopen($datadirfile, 'w');

    if (!$datadircheck) {
        echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP Datadir write permissions FAIL'>";
        echo "Data";
        echo "</a>";
        echo "<script>console.log( 'ERROR: Logarr PHP Data dir write permissions FAIL' );</script>";
    } else {
        echo " | <div class='extok' title='PHP Datadir write permissions OK' >";
        echo "Data";
        echo "</div>";
        fwrite($datadircheck, $date . "\r\n" . "Logarr Datadir PHP write permissions OK \r\nThis file can be safely removed, however it will be regenerated every time a user logs into Logarr Settings. ");
        fclose($datadircheck);
        echo "<script>console.log( 'Logarr PHP Datadir write permissions OK' );</script>";
    }
}

if (extension_loaded('sqlite3')) {
    echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
    echo "php_sqlite3";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
    echo "php_sqlite3";
    echo "</a>";
    echo "<script>console.log( 'ERROR: PHP sqlite3 extension NOT loaded' );</script>";
}

if (extension_loaded('pdo_sqlite')) {
    echo " | <div class='extok' title='PHP pdo_sqlite extension loaded OK'>";
    echo "pdo_sqlite";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
    echo "pdo_sqlite";
    echo "</a>";
    echo "<script>console.log( 'ERROR: PHP pdo_sqlite extension NOT loaded' );</script>";
}

if (extension_loaded('zip')) {
    echo " | <div class='extok' title='PHP ZIP extension loaded OK'>";
    echo "php7-zip";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='php7-zip extension NOT loaded'>";
    echo "php7-zip";
    echo "</a>";
    echo "<script>console.log( 'ERROR: PHP php7-zip extension NOT loaded' );</script>";
}

if (extension_loaded('openssl')) {
    echo " | <div class='extok' title='PHP openssl extension loaded OK'>";
    echo "OpenSSL";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP openssl extension NOT loaded'>";
    echo "OpenSSL";
    echo "</a>";
    echo "<script>console.log( '%cERROR: PHP openssl extension NOT loaded','color: #FF0104;' );</script>";
}


//////////// default PHP extenstions ////////////


if (extension_loaded('date')) {
    echo " | <div class='extok' title='PHP date extension loaded OK'>";
    echo "date";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP date extension NOT loaded'>";
    echo " date";
    echo "</a>";
}

if (extension_loaded('json')) {
    echo " | <div class='extok' title='PHP json extension loaded OK'>";
    echo "json";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP json extension NOT loaded'>";
    echo " json";
    echo "</a>";
}

if (extension_loaded('pcre')) {
    echo " | <div class='extok' title='PHP pcre extension loaded OK'>";
    echo "pcre";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP pcre extension NOT loaded'>";
    echo " pcre";
    echo "</a>";
}

if (extension_loaded('session')) {
    echo " | <div class='extok' title='PHP session extension loaded OK'>";
    echo "session";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP session extension NOT loaded'>";
    echo " session";
    echo "</a>";
}

if (extension_loaded('filter')) {
    echo " | <div class='extok' title='PHP filter extension loaded OK'>";
    echo "filter";
    echo "</div>";
} else {
    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP filter extension NOT loaded'>";
    echo " filter";
    echo "</a>";
}

echo "</div>";

?>

<div id="phpinfo">
    <?php phpinfo(); ?>
</div>

    <script>
        $(function() {
            $(document).tooltip({
                hide: {
                    effect: "fadeOut",
                    duration: 200
                },
            });
        });
	</script>

</body>
