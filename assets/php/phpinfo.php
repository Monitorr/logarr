<link rel="stylesheet" href="../css/logarr.css">

<style type="text/css">

	body {
		padding-top: 0 !important;
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
        color: black;
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

<?php

	ini_set('error_reporting', E_ERROR);

	echo "<div id='extensions'>";

		echo " <strong> Required Extensions: </strong> ";

		$myfile = fopen('php-perms-check.txt', 'w');

		echo "<br>";

		if (!$myfile) {
			echo " <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP write permissions FAIL'>";
				echo "Perms";
			echo "</a>";
			echo "<script>console.log( 'ERROR: PHP Write permissions FAIL' );</script>";
		} else {
			echo " <div class='extok' title='PHP write permissions OK' >";
				echo "Perms";
			echo "</div>";
			fwrite($myfile, "PHP write permissions OK");
			fclose($myfile);
		}

		if (extension_loaded('sqlite3')) {
			echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
			echo "php_sqlite3";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
			echo "php_sqlite3";
			echo "</a>";
			echo "<script>console.log( 'ERROR: PHP sqlite3 extension NOT loaded' );</script>";
		}

		if (extension_loaded('pdo_sqlite')) {
			echo " | <div class='extok' title='PHP pdo_sqlite extension loaded OK'>";
			echo "pdo_sqlite";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
			echo "pdo_sqlite";
			echo "</a>";
			echo "<script>console.log( 'ERROR: PHP pdo_sqlite extension NOT loaded' );</script>";
		}

		if (extension_loaded('zip')) {
			echo " | <div class='extok' title='PHP ZIP extension loaded OK'>";
			echo "php7-zip";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='php7-zip extension NOT loaded'>";
			echo "php7-zip";
			echo "</a>";
			echo "<script>console.log( 'ERROR: PHP php7-zip extension NOT loaded' );</script>";
		}

		if (extension_loaded('openssl')) {
			echo " | <div class='extok' title='PHP openssl extension loaded OK'>";
			echo "OpenSSL";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP openssl extension NOT loaded'>";
			echo "OpenSSL";
			echo "</a>";
			echo "<script>console.log( 'ERROR: PHP openssl extension NOT loaded' );</script>";
		}


		//////////// default PHP extenstions ////////////


		if (extension_loaded('date')) {
			echo " | <div class='extok' title='PHP date extension loaded OK'>";
			echo "date";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP date extension NOT loaded'>";
			echo " date";
			echo "</a>";
		}

		if (extension_loaded('json')) {
			echo " | <div class='extok' title='PHP json extension loaded OK'>";
			echo "json";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP json extension NOT loaded'>";
			echo " json";
			echo "</a>";
		}

		if (extension_loaded('pcre')) {
			echo " | <div class='extok' title='PHP pcre extension loaded OK'>";
			echo "pcre";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pcre extension NOT loaded'>";
			echo " pcre";
			echo "</a>";
		}

		if (extension_loaded('session')) {
			echo " | <div class='extok' title='PHP session extension loaded OK'>";
			echo "session";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP session extension NOT loaded'>";
			echo " session";
			echo "</a>";
		}

		if (extension_loaded('filter')) {
			echo " | <div class='extok' title='PHP filter extension loaded OK'>";
			echo "filter";
			echo "</div>";
		} else {
			echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP filter extension NOT loaded'>";
			echo " filter";
			echo "</a>";
		}

	echo "</div>";

?>

<div id="phpinfo">

    <?php phpinfo(); ?>

</div> 