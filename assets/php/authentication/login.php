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

    <script src="assets/js/pace.js" async></script>

    <link rel="icon" type="image/png" href="favicon.png">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool." />
    <meta name="application-name" content="Logarr" />
    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/vendor/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/logarr.css">
    <link rel="stylesheet" href="assets/data/custom.css">

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/vendor/sweetalert2.min.js"></script>
    <script src="assets/js/logarr.main.js"></script>

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
            })
            console.log("Welcome to Logarr!");
        };

        $(document).ready(function() {
            toastwelcome();
        });
    </script>

    <!-- // TODO CHANGE ME: -->
    <?php
        //ini_set('error_reporting', E_ERROR);
        error_reporting(0);
    ?>

    <title>
        <?php echo $GLOBALS['preferences']['sitetitle'] . ' | Log In'; ?>
    </title>

    <!-- sync config with javascript -->
    <script>
        var settings = <?php echo json_encode($GLOBALS['settings']); ?>;
        var preferences = <?php echo json_encode($GLOBALS['preferences']); ?>;
    </script>

    <!-- UI clock functions: -->
    <script>
        <?php
        //initial values for clock:
        $timezone = $GLOBALS['preferences']['timezone'];
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
        var servertime = "<?php echo $serverTime; ?>";
        var timeStandard = <?php echo $timeStandard; ?>;
        var timeZone = "<?php echo $timezone_suffix; ?>";
        var rftime = <?php echo $GLOBALS['settings']['rftime']; ?>;

        $(document).ready(function() {
            updateTime();
        });
    </script>

    <script src="assets/js/clock.js"></script>

</head>

<body id="body" style="color: #FFFFFF;">

    <script>
        document.body.className += ' fade-out';
        $(function() {
            $('body').removeClass('fade-out');
        });
    </script>

    <div id="ajaxtimestamp" title="Analog clock timeout. Refresh page."></div>

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
            <div class="navbar-brand" onclick='window.location.href="index.php";' title="Return to Logarr">
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
                <input id="login_input_username" class="input" type="search" placeholder="Username" name="user_name" autofocus required autocomplete="off" spellcheck="false" />
            </div>

            <div>
                <label for="login_input_password"><i class="fa fa-fw fa-key"></i></label>
                <input id="login_input_password" class="input" type="password" placeholder="Password" name="user_password" required autocomplete="off" />
            </div>

            <div id="loginbtn">
                <button type="submit" class="btn btn-primary" name="login" title="Log In">Log in</button>
            </div>
        </form>
    </div>

    <?php include(__DIR__ . "/footer.php"); ?> 