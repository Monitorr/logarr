<?php
include('assets/php/functions.php');
include('assets/php/auth_check.php');
?>


<!DOCTYPE html>
<html lang="en">

<!--
                LOGARR
    by @seanvree, @wjbeckett, and @jonfinley
        https://github.com/Monitorr
-->

<head>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="webmanifest.json">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool."/>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/logarr.css"/>
    <link rel="stylesheet" href="assets/data/custom.css"/>

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/images/favicon/apple-touch-icon-57x57.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon/apple-touch-icon-114x114.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon/apple-touch-icon-144x144.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="assets/images/favicon/apple-touch-icon-60x60.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="assets/images/favicon/apple-touch-icon-120x120.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="assets/images/favicon/apple-touch-icon-76x76.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="assets/images/favicon/apple-touch-icon-152x152.png"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-196x196.png" sizes="196x196"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-96x96.png" sizes="96x96"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-32x32.png" sizes="32x32"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-16x16.png" sizes="16x16"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-128.png" sizes="128x128"/>
    <meta name="application-name" content="Logarr"/>
    <meta name="msapplication-TileColor" content="#FFFFFF"/>
    <meta name="msapplication-TileImage" content="assets/images/favicon/mstile-144x144.png"/>
    <meta name="msapplication-square70x70logo" content="assets/images/favicon/mstile-70x70.png"/>
    <meta name="msapplication-square150x150logo" content="assets/images/favicon/mstile-150x150.png"/>
    <meta name="msapplication-wide310x150logo" content="assets/images/favicon/mstile-310x150.png"/>
    <meta name="msapplication-square310x310logo" content="assets/images/favicon/mstile-310x310.png"/>
    <meta name="theme-color" content="#252525"/>
    <meta name="theme_color" content="#252525"/>

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <title><?php echo $GLOBALS['preferences']['sitetitle']; ?></title>

    <script src="assets/js/jquery.min.js"></script>

        <script src="assets/js/pace.js" async></script>

        <script src="assets/js/jquery.blockUI.js"></script>

        <script src="assets/js/jquery.highlight.js" async> </script>

        <script src="assets/js/logarr.main.js"></script>
    <script src="assets/js/jquery.mark.min.js" async></script>

    <script src="assets/js/logarr.main.js" async></script>

    <!-- sync config with javascript -->
    <script>
        let settings = <?php echo json_encode($GLOBALS['settings']);?>;
        let preferences = <?php echo json_encode($GLOBALS['preferences']);?>;
        let current_rflog = settings.rflog;
        let nIntervId;
        let logInterval = false;
        refreshConfig(true);
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
        let servertime = "<?php echo $serverTime;?>";
        let timeStandard = <?php echo $timeStandard;?>;
        let timeZone = "<?php echo $timezone_suffix;?>";
        let rftime = <?php echo $GLOBALS['settings']['rftime'];?>;

        $(document).ready(function () {
            setTimeout(syncServerTime(), settings.rftime); //delay is rftime
            updateTime();
        });
    </script>

    <script src="assets/js/clock.js"></script>


    <!-- LOG UNLINK FUNCTION  -->
    <script>
        $(document).on('click', 'button[data-action=\'unlink-log\']', function (event) {
            event.preventDefault(); // stop being refreshed
            console.log('Attempting log roll');
            $.growlUI("Attempting <br> log roll");
            $.ajax({
                type: 'POST',
                url: 'assets/php/unlink.php',
                processData: false,
                data: "file=" + $(".path[data-service='" + $(this).data('service') + "']").html().trim(),
                success: function (data) {
                    $('#modalContent').html(data);
                    let modal = $('#responseModal');
                    let span = $('.closemodal');
                    modal.fadeIn('slow');
                    span.click(function () {
                        modal.fadeOut('slow');
                    });
                    $(body).click(function (event) {
                        if (event.target != modal) {
                            modal.fadeOut('slow');
                        }
                    });
                    setTimeout(function () {
                        modal.fadeOut('slow');
                    }, 3000);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log("ERROR: unlink ajax posting failed");
                }
            });
            return false;
        });
    </script>

    <!-- LOG DOWNLOAD FUNCTION  -->
    <script>
        $(document).on('click', 'button[data-action=\'download-log\']', function (event) {
            event.preventDefault(); // stop page from being refreshed
            $.growlUI("Downloading <br> log file");
            let logFilePath = ($(".path[data-service='" + $(this).data('service') + "']").html()).replace('file=', '').trim();
            console.log("Downloading log file: " + logFilePath);
            window.open('assets/php/download.php?file=' + logFilePath);
            return false;
        });
    </script>

    <!-- Execute search on ENTER keyup:  -->
    <script>

        $(document).ready(function () {
            $("#text-search2").keyup(function (event) {
                if (event.keyCode === 13) {
                    $("#marksearch").click();
                }
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            refreshblockUI();
        });

        $(document).on('click', '.category-filter-item', function (event) {
            refreshblockUI();
            setTimeout(function () {
                console.log('Filtering logs on: ' + window.location.hash);
            }, 500);
        });
    </script>

</head>

<body id="body" style="color: #FFFFFF;">
<div id="ajaxtimestamp" title="Analog clock timeout. Refresh page."></div>

<div class="header">

    <div id="left" class="Column">

        <div id="clock">
            <canvas id="canvas" width="120" height="120"></canvas>
            <div class="dtg" id="timer"></div>
        </div>

    </div>

    <div id="logo" class="Column">
        <img src="assets/images/log-icon.png" alt="Logarr" style="height:8em;border:0;" title="Reload Logarr"
             onclick="window.location.reload(true);">
    </div>

    <div id="right" class="Column">

        <div id="righttop" class="righttop">
            <form method="POST">
                <div id="markform">

                    <input type="search" name="markinput" id="text-search2" class="input" title="Input search term"
                           placeholder=" Search & highlight . . ." required>
                    <span id="validity" class="validity"></span>
                    <button data-search="search" name="searchBtn" id="searchBtn" value="Search"
                            class="btn marksearch btn-primary" onclick="this.blur(); return false;"
                            title="Execute search. Results will be highlighted in yellow.">Search
                    </button>
                    <button data-search="next" name="nextBtn" class="btn search-button btn-primary btn-visible btn-hidden"
                            onclick="this.blur(); return false;" title="Focus to first search result">&darr;
                    </button>
                    <button data-search="prev" name="prevBtn" class="btn search-button btn-primary btn-visible btn-hidden"
                            onclick="this.blur(); return false;" title="Focus to last search result">&uarr;
                    </button>
                    <button data-search="clear" class="btn search-button btn-primary"
                            onclick="this.blur(); return false;" title="Clear search results">✖
                    </button>

                </div>
            </form>
        </div>

        <div id="rightmiddle" class="rightmiddle">

            <div id="count" class="count"
                 title="Search results have been highlighted in yellow. NOTE: Search results will be cleared if a log update is triggered."></div>

        </div>

        <div id="rightbottom" class="rightbottom">

            <table id="slidertable">
                <tr title="Auto-update logs | Interval: <?php echo $settings['rflog']; ?> ms ">

                    <th id="textslider">
                        Auto Update:
                    </th>

                    <th id="slider">
                        <div id="auto-update-status"></div>
                        <!--<label class="switch" id="buttonStart">
                            <input id="autoRefreshLog" type="checkbox">
                            <span class="slider round"></span>
                        </label>-->
                    </th>

                    <th>
                        <input id="Update" type="button" name="updateBtn" class="button2 btn btn-primary" value="Update"
                               title="Trigger log manual update" onclick="refreshblockUI(); this.blur(); return false"/>
                    </th>

                </tr>
            </table>

        </div>

    </div>

</div>

<div id="logcontainer"></div>

<!-- Unlink response modal: -->

<div id='responseModal'>

    <span class="closemodal" aria-hidden="true" title="Close">&times;</span>

    <div id='modalContent'></div>

</div>

<button onclick="topFunction();checkAll1();" id="myBtn" title="Go to top"></button>

<div id="footer">

    <!-- Checks for Logarr application update on page load & "Check for update" click: -->
    <script src="assets/js/update.js" async></script>

    <div id="logarrid">
        <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank"
           class="footer">Logarr </a> |
        <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a> |
        <a href="https://github.com/Monitorr/logarr/releases" title="Logarr releases" target="_blank" class="footer">
            Version: <?php echo file_get_contents("assets/js/version/version.txt"); ?></a>
        <?php if(isset($_SESSION['user_name']) && isset($_SESSION['user_is_logged_in']) && !empty($_SESSION['user_name']) && ($_SESSION['user_is_logged_in'])){
            echo " | <a href='index.php?action=logout' title='Log out' class='footer'></i>Logout</a>";
        }?>
        <br>
    </div>

    <div id="version">
        <a id="version_check" title="Check and execute update" style="cursor: pointer">Check for Update</a>
        <br>
    </div>

    <div id="version_check_auto"></div>

</div>

<!-- scroll to top   -->

<script>

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("myBtn").style.display = "block";
        } else {
            document.getElementById("myBtn").style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

</script>

<script>

    function checkedAll(isChecked) {
        var c = document.getElementsByName('slidebox');

        for (var i = 0; i < c.length; i++) {
            if (c[i].type == 'checkbox') {
                c[i].checked = isChecked;
            }
        }
    }

    function checkAll1() {
        checkedAll(true);
    }

</script>

</body>

</html>
