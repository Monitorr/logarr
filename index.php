<?php
include('assets/php/functions.php');
include('assets/php/auth_check.php');
?>

<!DOCTYPE html>
<html lang="en">

    <!--
                    LOGARR
        @seanvree | @jonfinley | @rob1998
            https://github.com/Monitorr
    -->

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="manifest" href="webmanifest.json">

        <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool.">
        <meta name="description" content="Logarr">
        <meta name="application-name" content="Logarr">
        <meta name="robots" content="NOINDEX, NOFOLLOW">

        <script src="assets/js/vendor/pace.js" async></script>

        <link rel="icon" type="image/png" href="favicon.png">
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
        <link rel="mask-icon" href="assets/images/favicon/icon.svg" color="blue">

        <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
        <meta name="msapplication-square150x150logo" content="assets/images/favicon/mstile-150x150.png">

        <meta name="theme-color" content="#464646">
        <meta name="theme_color" content="#464646">
        <meta name="msapplication-TileColor" content="#464646">

        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/vendor/sweetalert2.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/vendor/jquery-ui.min.css">
        <link rel="stylesheet" href="assets/css/logarr.css">
        <link rel="stylesheet" href="assets/data/custom.css">

        <title>
            <?php
            $title = $GLOBALS['preferences']['sitetitle'];
            echo $title . PHP_EOL;
            ?>
        </title>

        <style>
            body {
                margin-bottom: 3rem;
            }

            .swal2-icon.swal2-warning {
                color: yellow;
                border-color: yellow;
            }
        </style>

        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/vendor/sweetalert2.min.js"></script>
        <script src="assets/js/vendor/jquery.highlight.js" async></script>
        <script src="assets/js/jquery.mark.min.js" async></script>
        <script src="assets/js/vendor/jquery-ui.min.js"></script>
        <script src="assets/js/logarr.main.js"></script>

        <script>
            $(document).ready(function() {
                console.log("Welcome to %cLogarr", "color: #FF0104; font-size: 2em;");
            });
        </script>

        <?php appendLog("Logarr Index loaded"); ?>

        <!-- Check if Logarr auth is enabled / if TRUE, check login status every 10s -->
        <?php checkLoginindex(); ?>

        <!-- Check for valid SETTINGS values in config.json on index.php load: -->
        <?php isMissingSettings(); ?>

        <!-- sync config with javascript -->
        <script>
            let settings = <?php echo json_encode($GLOBALS['settings']); ?>;
            let preferences = <?php echo json_encode($GLOBALS['preferences']); ?>;
            let logs = <?php echo json_encode($GLOBALS['logs']); ?>;
            let authentication = <?php echo json_encode($GLOBALS['authentication']); ?>;
            home = true;
            refreshConfig();
        </script>

        <!-- UI clock functions: -->
        <script>
            <?php
            //initial values for clock:
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
                syncServerTime()
                setInterval(function() {
                    syncServerTime()
                }, rftime); //delay is rftime
                updateTime();
            });
        </script>

        <script src="assets/js/clock.js" async></script>
        <script src="assets/data/custom.js"></script>

        <!-- Append settings values to Logarr log: -->
        <?php settingsValues(); ?>

        <!-- Tooltips: -->
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

        <script>
            refreshLog();
        </script>

    </head>

    <body id="body" onscroll="scrollFunction()" onload="refreshblockUI();">

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>

        <div class="header">

            <div id="left" class="Column">
                <div id="clock" title="Time refresh interval: <?php echo $settings['rftime']; ?> ms ">
                    <i class="fas fa-exclamation-triangle hidden" id="synctimeerror" title="An error occurred while synchronizing time!"> </i>
                    <canvas id="canvas" width="120" height="120"></canvas>
                    <div class="dtg" id="timer"></div>
                </div>
            </div>

            <div id="logo" class="Column">
                <img id="logo-icon" src="assets/images/logo_white_glow_text_logarr-crop.png" alt="Logarr" title="Reload Logarr" onclick="window.location.reload(true);">
                <img id="logo-icon-mobile" src="assets/images/logarr_white_text_crop.png" class="hidden" alt="Logarr" title="Reload Logarr" onclick="window.location.reload(true);">
                <div id="brand" class="header-brand" title="Reload Logarr" onclick="window.location.reload(true);">
                    <?php
                    echo $preferences['sitetitle'];
                    ?>
                </div>
            </div>

            <div id="dateRight" class="hidden"></div>

            <div id="right" class="Column">

                <div id="righttop" class="righttop">
                    <div id="auto-update">

                        Auto Update:

                        <label class="switch" id="buttonStart" title="Auto-update logs | Interval: <?php echo $settings['rflog']; ?> ms ">
                            <span class="slider round" id="autoUpdateSlider" data-enabled="false" onclick="overwriteLogUpdate();"></span>
                        </label>

                        <input id="Update" type="button" name="updateBtn" class="button2 btn btn-primary indexBtn" value="Update" title="Trigger log manual update" onclick="refreshblockUI(); this.blur(); return false" />

                    </div>
                </div>

                <div id="rightmiddle" class="rightmiddle">
                    <form method="POST">
                        <div id="markform">
                            <input type="search" name="markinput" id="text-search2" class="input" title="Input search query" placeholder=" Search & highlight . . ." required spellcheck="false">
                            <button data-search="search" name="searchBtn" id="searchBtn" value="Search" class="btn marksearch btn-primary indexBtn" onclick="this.blur(); return false;" title="Execute search. Results will be highlighted in yellow.">Search
                            </button>
                            <button data-search="next" id="nextBtn" name="nextBtn" class="btn search-button btn-primary btn-visible btn-hidden indexBtn npBtn" onclick="this.blur(); return false;" title="Focus to first search result">&darr;
                            </button>
                            <button data-search="prev" id="prevBtn" name="prevBtn" class="btn search-button btn-primary btn-visible btn-hidden indexBtn npBtn" onclick="this.blur(); return false;" title="Focus to last search result">&uarr;
                            </button>
                            <button data-search="clear" id="searchClear" class="btn search-button btn-primary indexBtn hidden" onclick="this.blur(); return false;" title="Clear search results">✖
                            </button>
                        </div>
                    </form>
                </div>

                <div id="rightbottom" class="rightbottom">
                    <div id="count" class="count" title="Search results have been highlighted in yellow."></div>
                </div>

            </div>

        </div>

        <div id="logcontainer">
            <nav id="categoryFilter" style="display: none;"></nav>
            <div id='logwrapper' class='flex'></div>
        </div>

        <button onclick="topFunction();checkAll1();" id="myBtn" class="toggle" title="Go to top"></button>

        <div id="footer">

            <!-- Checks for Logarr application update on page load-->
            <script src="assets/js/update.js" async></script>

            <div id="logarrid">
                <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank" class="footer">Logarr </a> |
                <a href="https://github.com/Monitorr/logarr/releases" title="Logarr Releases" target="_blank" class="footer">
                    v:
                    <?php echo file_get_contents("assets/js/version/version.txt"); ?></a> |
                <a href="settings.php" id="footerlink" title="Logarr Settings" target="_blank" class="footer">Settings</a>
                <?php if (isset($_SESSION['user_name']) && isset($_SESSION['user_is_logged_in']) && !empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])) {
                    echo " | <a href='index.php?action=logout' onclick='logouttoast();' title='Log Out' class='footer'>Log Out</a>";
                } ?>
                <br>
            </div>

        </div>

        <!-- Close persistant tooltips: -->
        <script>
            $(window).blur(function() {
                $('a').blur();
            });

            //Close persistant tooltips on mobile:
            $('.btn').on('touchstart', function(e) {
                $(document).tooltip("enable");
            });

            $('.btn').on('touchend', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });

            $('.slider').on('touchstart', function(e) {
                $(document).tooltip("enable");
            });

            $('.slider').on('touchend', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });

            $('.toggle').on('touchstart', function(e) {
                $(document).tooltip("enable");
            });

            $('.toggle').on('touchend', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });

            $('.expandtoggle').on('touchstart', function(e) {
                $(document).tooltip("enable");
            });

            $('.expandtoggle').on('touchend', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });

            $('.input').on('touchstart', function(e) {
                $(document).tooltip("enable");
            });

            $('.input').on('touchend', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });
        </script>

    </body>

</html>