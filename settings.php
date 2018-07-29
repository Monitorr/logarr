<?php
include('assets/php/functions.php');
include('assets/php/auth_check.php');
?>

<!DOCTYPE html>
<html lang="en">

<!--
     Logarr | settings page
https://github.com/Monitorr/Logarr
-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="manifest" href="webmanifest.json">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico"/>
    <link rel="apple-touch-icon" href="favicon.ico">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Monitorr">

    <link type="text/css" href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link type="text/css" href="assets/css/logarr.css" rel="stylesheet">
    <link type="text/css" href="assets/data/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646"/>
    <meta name="theme_color" content="#464646"/>

    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/pace.js" async></script>
    <script src="assets/js/logarr.main.js"></script>
    <!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->


    <!-- sync config with javascript -->
    <script>
        let settings = <?php echo json_encode($GLOBALS['settings']);?>;
        let preferences = <?php echo json_encode($GLOBALS['preferences']);?>;
        refreshConfig(false);
    </script>

    <!-- // Set global timezone from config file: -->
	<?php
	//Why is this necessary? - rob1998
	if ($GLOBALS['preferences']['timezone'] == "") {

		date_default_timezone_set('UTC');
		$timezone = date_default_timezone_get();

	} else {

		$timezoneconfig = $GLOBALS['preferences']['timezone'];
		date_default_timezone_set($timezoneconfig);
		$timezone = date_default_timezone_get();

	}
	?>

    <script>
		<?php
		//initial values for clock:
		//$timezone = $preferences['timezone'];
		$dt = new DateTime("now", new DateTimeZone("$timezone"));
		$timeStandard = (int)($GLOBALS['preferences']['timestandard']);
		$rftime = $GLOBALS['settings']['rftime'];
		$timezone_suffix = '';
		if (!$timeStandard) {
			$dateTime = new DateTime();
			$dateTime->setTimeZone(new DateTimeZone($timezone));
			$timezone_suffix = $dateTime->format('T');
		}
		$serverTime = $dt->format("D d M Y H:i:s");
		?>
        let servertime = "<?php echo $serverTime;?>";
        let timeStandard = <?php echo $timeStandard;?>;
        let timeZone = "<?php echo $timezone_suffix;?>";
        let rftime = <?php echo $settings['rftime'];?>;

        $(document).ready(function () {
            setTimeout(syncServerTime(), settings.rftime); //delay is rftime
            updateTime();
        });
    </script>


    <style>

        body {
            margin: auto;
            padding-left: 2rem;
            padding-right: 1rem;
            padding-bottom: 1rem;
            /* overflow-y: scroll !important;  */
            overflow-x: hidden !important;
            /* color: white !important; */
            background-color: #1F1F1F;
        }

        .navbar-brand {
            font-weight: 500;
        }

        #summary {
            margin-top: 0 !important;
            width: 17rem !important;
            position: relative !important;
            margin-bottom: 1rem;
            font-size: .8rem;
            line-height: 1.5rem;
        }

        legend {
            color: white;
        }

        body::-webkit-scrollbar {
            width: 10px;
            background-color: #252525;
        }

        body::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #252525;
        }

        body::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #8E8B8B;
        }

        body.offline #link-bar {
            display: none;
        }

        body.online #link-bar {
            display: block;
        }

        #left {
            /* padding-top: 5rem; */
            padding-bottom: 1.5rem !important;
        }

        #footer {
            position: fixed !important;
        }

        a:link {
            background-color: transparent !important;
        }

    </style>

    <title>
		<?php
		echo $preferences['sitetitle'];
		?>
        | Settings
    </title>

    <!-- <?php include('./assets/php/gitinfo.php'); ?> -->


    <script>
        $(function () {
            switch (window.location.hash) {
                case "#user-preferences":
                    load_preferences();
                    break;
                case "#logarr-settings":
                    load_settings();
                    break;
                case "#logarr-authentication":
                    load_authentication();
                    break;
                case "#logs-configuration":
                    load_logs();
                    break;
                default:
                    load_info();
            }
        });
    </script>

    <script src="assets/js/clock.js" async></script>
    <script src="assets/data/custom.js"></script>
</head>

<body>

<script>
    document.body.className += ' fade-out';
    $(function () {
        $('body').removeClass('fade-out');
    });
</script>

<div id="settingscolumn" class="settingscolumn">

    <div id="settingsbrand">
        <div class="navbar-brand">
			<?php
			echo $preferences['sitetitle'];
			?>
        </div>
    </div>

    <div id="summary"></div>

    <div class="Column left">
        <div id="clock">
            <canvas id="canvas" width="120" height="120"></canvas>
            <div class="dtg" id="timer"></div>
        </div>
    </div>

    <div id="wrapper" class="left">

        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">

            <div class="settingstitle">
                Settings
            </div>

            <ul class="nav sidebar-nav">

                <li class="sidebar-nav-item" data-item="info">
                    <a href="#info" onclick="load_info()"><i class="fa fa-fw fa-info"></i>Info</a>
                </li>
                <li class="sidebar-nav-item" data-item="user-preferences">
                    <a href="#user-preferences" onclick="load_preferences()"><i class="fa fa-fw fa-user"></i>User Preferences</a>
                </li>
                <li class="sidebar-nav-item" data-item="logarr-settings">
                    <a href="#logarr-settings" onclick="load_settings()"><i class="fa fa-fw fa-cog"></i>Logarr Settings</a>
                </li>
                <li class="sidebar-nav-item" data-item="logarr-authentication">
                    <a href="#logarr-authentication" onclick="load_authentication()"><i class="fa fa-fw fa-lock"></i>Authentication</a>
                </li>
                <li class="sidebar-nav-item" data-item="logs-configuration">
                    <a href="#logs-configuration" onclick="load_logs()"><i class="fa fa-fw fa-book"></i>Logs Configuration</a>
                </li>
                <li class="sidebar-nav-item" data-item="registration">
                    <a href="settings.php?action=register"><i class="fas fa-user-plus"></i>Registration</a>
                </li>
                <?php if(isset($_SESSION['user_name']) && isset($_SESSION['user_is_logged_in']) && !empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])){ ?>
                <li class="sidebar-nav-item" data-item="log-out">
                    <a href="settings.php?action=logout"><i class="fas fa-sign-out-alt"></i>Log Out</a>
                </li>
                <?php } ?>
                <li class="sidebar-nav-item" data-item="logarr">
                    <a href="index.php"><i class="fa fa-fw fa-home"></i>Logarr</a>
                </li>

            </ul>

        </nav>

    </div>

    <div id="version">

        <script src="assets/js/update.js" async></script>

        <p><a class="footer a" href="https://github.com/monitorr/Logarr" target="_blank" title="Logarr Repo">
                Logarr </a> | <a class="footer a" href="https://github.com/Monitorr/logarr/releases" target="_blank"
                                 title="Logarr Releases"> <?php echo file_get_contents("assets/js/version/version.txt"); ?> </a>
        </p>

        <div id="version_check_auto"></div>

        <div id="reginfo">

			<?php

			if (!configExists()) {
				echo "Config file NOT present";
			} else {
				echo 'Config file present';
			}

			?>

        </div>

    </div>

</div>
<div class="settings-title">
    <div id="setttings-page-title" class="navbar-brand">
    </div>
</div>
<div id="includedContent">

    <script>
    </script>

</div>

<div id="footer" class="settings-footer">

    <!-- Checks for Logarr application update on page load & "Check for update" click: -->
    <script src="assets/js/update.js" async></script>

    <div id="logarrid">
        <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank"
           class="footer">Logarr </a> |
        <a href="https://github.com/Monitorr/logarr/releases" title="Logarr releases" target="_blank" class="footer">
            Version: <?php echo file_get_contents("assets/js/version/version.txt"); ?></a> |
        <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a>
        <?php if(isset($_SESSION['user_name']) && isset($_SESSION['user_is_logged_in']) && !empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])){
            echo " | <a href='index.php?action=logout' title='Log out' class='footer'></i>Logout</a>";
        }?>
        <br>
    </div>

</div>
</body>

</html>
