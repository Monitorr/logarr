<?php
include_once(__DIR__ . "/../functions.php");
include_once(__DIR__ . "/../auth_check.php");
?>
<!DOCTYPE html>
<html lang="en">

<!--
                LOGARR
    by @seanvree, @jonfinley, and @rob1998
        https://github.com/Monitorr
-->

<!-- login.php -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="manifest" href="webmanifest.json">

    <script src="assets/js/vendor/pace.js" async></script>

    <link rel="icon" type="image/png" href="favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool.">
    <meta name="application-name" content="Logarr">
    <meta name="theme-color" content="#464646">
    <meta name="theme_color" content="#464646">

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/logarr.css">
    <link rel="stylesheet" href="assets/data/custom.css">

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/vendor/jquery-ui.min.js"></script>
    <script src="assets/js/vendor/sweetalert2.min.js"></script>
    <script src="assets/js/logarr.main.js"></script>

    <?php appendLog("Logarr Login page loaded"); ?>

    <style>
        .notification {
            visibility: hidden;
        }

        .footer:hover {
            font-size: 1rem !important;
        }

        #login_input_username::-webkit-search-cancel-button {
            position: relative;
            cursor: pointer;
        }

        .swal2-bottom-start {
            margin-left: 1rem !important;
            bottom: 5vh !important;
            cursor: default;
        }

        .swal2-icon.swal2-warning {
            color: yellow !important;
            border-color: yellow !important;
        }
    </style>

    <script>
        function toastwelcome() {
            Toast.fire({
                type: 'success',
                title: 'Welcome to Logarr!',
                position: 'bottom-start',
                timer: 5000
            });
            console.log("Welcome to %cLogarr", "color: #FF0104; font-size: 2em;");
        }

        $(document).ready(function() {
            toastwelcome();
        });
    </script>

    <?php
        ini_set('error_reporting', 'E_WARN');
    ?>

    <title>
        <?php echo $GLOBALS['preferences']['sitetitle'] . ' | Log In'; ?>
    </title>

    <!-- Load inital global values from javascript -->
    <script>
        let settings = <?php echo json_encode($GLOBALS['settings']); ?>;
        let preferences = <?php echo json_encode($GLOBALS['preferences']); ?>;
        let authentication = <?php echo json_encode($GLOBALS['authentication']); ?>;
    </script>

    <!-- UI clock functions: -->
    <script>
        <?php
        $timezoneconfig = $GLOBALS['preferences']['timezone'];
        date_default_timezone_set($timezoneconfig);
        $timezone = date_default_timezone_get();
        $dt = new DateTime("now", new DateTimeZone("$timezone"));
        $timeStandard = (int) ($GLOBALS['preferences']['timestandard']);
        $rftime = $GLOBALS['settings']['rftime'];
        $timezone_suffix = '';
        if (!$timeStandard) {
            $dateTime = new DateTime();
            $dateTime->setTimeZone(new DateTimeZone($timezone));
            $timezone_suffix = $dateTime->format('T');
        }
        $serverTime = $dt->format("D d M Y H:i:s");
        ?>
        let servertime = "<?php echo $serverTime; ?>";
        let timeStandard = <?php echo $timeStandard; ?>;
        let timeZone = "<?php echo $timezone_suffix; ?>";
        let rftime = <?php echo $GLOBALS['settings']['rftime']; ?>

        settings.rftime = settings.rftime > 300 ? rftime : 60000; //minimum value, if not set default value will be used

        $(document).ready(function() {
            syncServerTime();
            updateTime();
        });
    </script>

    <script src="assets/js/clock.js"></script>
    <script src="assets/data/custom.js"></script>

    <!-- Tooltips: -->
    <script>
        $(function() {
            $(document).tooltip({
                hide: {
                    effect: "fadeOut",
                    duration: 200
                }
            });
        });
    </script>

    <!-- Return to index.php when cancel button is clicked: -->
    <script>
        $(document).ready(function() {
            var location = window.location.href;
            var current = location.substring(location.lastIndexOf("/") + 1, location.length);
            if (current.startsWith("settings.php")) {} else {
                $('#returnbtn').addClass('hidden');
            }
        });

        function returnIndex() {
            top.location = "index.php";
        };
    </script>

</head>

<body id="body">

    <script>
        document.body.className += ' fade-out';
        $(function() {
            $('body').removeClass('fade-out');
        });
    </script>

    <div class="header-login">
        <div id="logo-login" class="Column">
            <img src="assets/images/logarr_white_text_crop.png" alt="Logarr">
        </div>
    </div>

    <div id="logincolumn" class="Column">

        <div id="logoHeader" class="Column">
            <img src="assets/images/logo_white_glow_crop.png" alt="Logarr">
        </div>

        <div id="loginbrand">
            <div id="brand" class="navbar-brand" onclick='window.location.href="index.php";' title="Return to Logarr">
                <?php
                echo $GLOBALS['preferences']['sitetitle'];
                ?>
            </div>
        </div>

        <div id="clock">
            <canvas id="canvas" width="120" height="120"></canvas>
            <div class="dtg" id="timer"></div>
        </div>

        <div id="version_check_auto" class="loginversion"></div>

    </div>

    <div id='login-container'>

        <?php
        if (isset($this->feedback) && !empty($this->feedback)) {
            echo "<div class='login-warning'><p>" . $this->feedback . "</p></div>";
        }
        ?>

        <form method="post" id="login-form" action="" name="loginform">
            <div>
                <label for="login_input_username"><i class="fa fa-fw fa-user"></i></label>
                <input id="login_input_username" class="input" type="search" name="user_name" placeholder="Username" title="" autofocus required autocomplete="off" spellcheck="false" />
            </div>

            <div>
                <label for="login_input_password"><i class="fa fa-fw fa-key"></i></label>
                <input id="login_input_password" class="input" type="password" name="user_password" placeholder="Password" title="" required autocomplete="off" />
            </div>

            <div id="login">
                <button type="submit" id="loginbtn" class="btn btn-primary" name="login" title="Log In">Log In</button>
                <button type="button" id="returnbtn" class="btn btn-primary" name="return" title="Return to Logarr" onclick="returnIndex();">Cancel</button>
            </div>

        </form>

    </div>

    <!-- Hide Cancel button if Index auth is enabled: -->
    <?php
    $auth = $GLOBALS['authentication']['logsEnabled'];

    if ($auth == 'true') {
        echo '<script>';
        echo '$("#returnbtn").addClass("hidden");';
        echo '</script>';
    }
    ?>

    <!-- Disable Log In button if username and password fields are empty: -->
    <script>
        if ($("#login_input_password").val() == "") {
            $("#loginbtn").addClass("disabled");
            $("#loginbtn").addClass("cursornotallowed");
        } else {
            $("#loginbtn").removeClass("disabled");
            $("#loginbtn").removeClass("cursornotallowed");
        }

        const $password_input = document.querySelector('#login_input_password');

        const typeHandler = function(e) {
            $("#loginbtn").removeClass("disabled");
            $("#loginbtn").removeClass("cursornotallowed");
        }

        $password_input.addEventListener('input', typeHandler) // register for oninput
        $password_input.addEventListener('propertychange', typeHandler) // for IE8
        $password_input.addEventListener('change', typeHandler) // fallback for Firefox
    </script>

    <?php include(__DIR__ . "/footer.php"); ?>