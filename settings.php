<?php
$config_file = 'assets/config/config.json';

function convertConfig()
{
    include(__DIR__ . '/../config/config.php');
    $new_config = json_decode(file_get_contents(__DIR__ . '/../config/config.sample-03jun2018.json'), 1);
    $old_config = array('config' => $config, 'logs' => $logs);
    $json = json_encode(array_merge($new_config, $old_config), JSON_PRETTY_PRINT);
    file_put_contents(__DIR__ . '/../config/config.json', $json);
}
//if (is_file('assets/config/config.php') && !is_file($config_file)) convertConfig();

//Use the function is_file to check if the config file already exists or not.
if(!is_file($config_file)){
    copy('assets/config/config.sample-03jun2018.json', $config_file);
}
include ('assets/php/functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<!--
     Monitorr | settings page
https://github.com/Monitorr/Monitorr
-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="manifest" href="webmanifest.json">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <link rel="apple-touch-icon" href="favicon.ico">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Monitorr">

    <link type="text/css" href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link type="text/css" href="assets/css/logarr.css" rel="stylesheet">
    <link type="text/css" href="assets/css/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/pace.js" async></script><script src="assets/js/logarr.main.js"></script>
    <!-- <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->


    <!-- sync config with javascript -->
    <script>
        var config = <?php echo json_encode($config);?>;
        var settings = <?php echo json_encode($settings);?>;
        var preferences = <?php echo json_encode($preferences);?>;
        function refreshConfig() {
            $.ajax({
                url: "assets/php/sync-config.php",
                data: {config:config},
                type: "POST",
                success: function (response) {
                    console.log(response);
                    var config = $.parseJSON(response);
                    setTimeout(function () {
                        refreshConfig()
                    }, config.rfconfig); //delay is rftime
                    document.title = config.title; //update page title to configured title
                    console.log('Refreshed config variables');
                }
            });
        };
        //refreshConfig();
        console.log(config);
    </script>
    <!-- // Set global timezone from config file: -->
    <?php
    //Why is this necessary? - rob1998
    if($preferences['timezone'] == "") {

        date_default_timezone_set('UTC');
        $timezone = date_default_timezone_get();

    }

    else {

        $timezoneconfig = $preferences['timezone'];
        date_default_timezone_set($timezoneconfig);
        $timezone = date_default_timezone_get();

    }
    ?>

    <script>
        <?php
        //initial values for clock:
        //$timezone = $preferences['timezone'];
        $dt = new DateTime("now", new DateTimeZone("$timezone"));
        $timeStandard = (int) ($preferences['timestandard'] === "True" ? true:false);
        $rftime = $settings['rftime'];
        $timezone_suffix = '';
        if(!$timeStandard){
            $dateTime = new DateTime();
            $dateTime->setTimeZone(new DateTimeZone($timezone));
            $timezone_suffix = $dateTime->format('T');
        }
        $serverTime = $dt->format("D d M Y H:i:s");
        ?>
        var servertime = "<?php echo $serverTime;?>";
        var timeStandard = <?php echo $timeStandard;?>;
        var timeZone = "<?php echo $timezone_suffix;?>";
        var rftime = <?php echo $settings['rftime'];?>;
        function updateTime() {
            setInterval(function() {
                var timeString = date.toLocaleString('en-US', {hour12: timeStandard, weekday: 'short', year: 'numeric', day: '2-digit', month: 'short', hour:'2-digit', minute:'2-digit', second:'2-digit'}).toString();
                var res = timeString.split(",");
                var time = res[3];
                var dateString = res[0]+'&nbsp; | &nbsp;'+res[1].split(" ")[2]+" "+res[1].split(" ")[1]+'<br>'+res[2];
                var data = '<div class="dtg">' + time + ' ' + timeZone + '</div>';
                data+= '<div id="line">__________</div>';
                data+= '<div class="date">' + dateString + '</div>';
                $("#timer").html(data);
            }, 1000);
        }
        function syncServerTime() {
            $.ajax({
                url: "assets/php/time.php",
                type: "GET",
                success: function (response) {
                    var response = $.parseJSON(response);
                    servertime = response.serverTime;
                    timeStandard = parseInt(response.timeStandard);
                    timeZone = response.timezoneSuffix;
                    rftime = response.rftime;
                    date = new Date(servertime);
                    setTimeout(function() {syncServerTime()}, config.rftime); //delay is rftime
                    console.log('Logarr time update START');
                }
            });
        }
        $(document).ready(function() {
            setTimeout(syncServerTime(), config.rftime); //delay is rftime
            updateTime();
        });
    </script>

    <script src="assets/js/clock.js" async></script>

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
            margin-top: 0rem !important;
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

        .auto-style1 {
            text-align: center;
        }

        #left {
            /* padding-top: 5rem; */
            padding-bottom: 1.5rem !important;
        }

        #footer {
            position: fixed !important;
            bottom: 0 !important;
        }

        a:link{
            background-color: transparent !important;
        }

    </style>

    <title>
        <?php
        echo $preferences['sitetitle'];
        ?>
        | Settings
    </title>

    <!-- <?php include ('assets/php/gitinfo.php'); ?> -->



    <script>
        $(function() {
            switch (window.location.hash) {
                case "#user-preferences":
                    load_preferences();
                    break;
                case "#logarr-settings":
                    load_settings();
                    break;
                case "#logarr-logs":
                    load_logs();
                    break;
                default:
                    load_info();
            }
        });
    </script>

</head>

<body>

<script>
    document.body.className += ' fade-out';
    $(function() {
        $('body').removeClass('fade-out');
    });
</script>

<div id ="settingscolumn" class="settingscolumn">

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

                <li>
                    <a href ="#info" onclick="load_info()"><i class="fa fa-fw fa-info"></i> Info </a>
                </li>
                <li>
                    <a href ="#user-preferences" onclick="load_preferences()"><i class="fa fa-fw fa-cog"></i>  User Preferences </a>
                </li>
                <li>
                    <a href ="#logarr-settings" onclick="load_settings()"><i class="fa fa-fw fa-cog"></i>  Logarr Settings </a>
                </li>
                <li>
                    <a href ="#logs-configuration" onclick="load_logs()"><i class="fa fa-fw fa-cog"></i> Logs Configuration  </a>
                </li>
                <li>
                    <a href="index.php"><i class="fa fa-fw fa-home"></i> Logarr </a>
                </li>

            </ul>

        </nav>

    </div>

    <div id="version" >

        <script src="assets/js/update_auto.js" async></script>

        <p> <a class="footer a" href="https://github.com/monitorr/Logarr" target="_blank" title="Logarr Repo"> Logarr </a> | <a class="footer a" href="https://github.com/Monitorr/logarr/releases" target="_blank" title="Logarr Releases"> <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a> </p>

        <div id="version_check_auto"></div>

        <div id="reginfo" >

            <?php

            if (!is_file($config_file)) {
                echo "Config file NOT present.";
                echo "<br><br>";
            }

            else {
                echo 'Config file present:';
                echo "<br>";
                echo $config_file;
                echo "<br><br>";
            }

            ?>

        </div>

    </div>

</div>
<div class="settings-title">
    <div id="setttings-page-title" class="navbar-brand">
    </div>
</div>
<div id ="includedContent">

    <script>
        function load_info() {
            document.getElementById("setttings-page-title").innerHTML='Information';
            document.getElementById("includedContent").innerHTML='<object  type="text/html" class="object" data="assets/php/settings/info.php" ></object>';
        }
    </script>

    <script>
        function load_preferences() {
            document.getElementById("setttings-page-title").innerHTML='User Preferences';
            document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/settings/user_preferences.php" ></object>';
        }
    </script>

    <script>
        function load_settings() {
            document.getElementById("setttings-page-title").innerHTML='Logarr Settings';
            document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/settings/site_settings.php" ></object>';
        }
    </script>

    <script>
        function load_logs() {
            document.getElementById("setttings-page-title").innerHTML='Logs Settings';
            document.getElementById("includedContent").innerHTML='<object type="text/html" class="object" data="assets/php/settings/logs_settings.php" ></object>';
        }
    </script>

</div>

<div id="footer">

    <p> <a class="footer a" href="https://github.com/Monitorr/logarr" target="_blank"> Monitorr </a> | <a class="footer a" href="https://github.com/Monitorr/logarr/releases" target="_blank"> <?php echo file_get_contents( __DIR__ . "/assets/js/version/version.txt" );?> </a> </p>

</div>
</body>

</html>
