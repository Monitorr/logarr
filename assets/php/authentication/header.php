<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<!--
                LOGARR
    by @seanvree, @jonfinley, and @rob1998
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

    <script src="assets/js/jquery.highlight.js" async></script>

    <script src="assets/js/logarr.main.js"></script>
    <script src="assets/js/jquery.mark.min.js" async></script>

    <!-- sync config with javascript -->
    <script>
        var settings = <?php echo json_encode($GLOBALS['settings']);?>;
        var preferences = <?php echo json_encode($GLOBALS['preferences']);?>;

        refreshConfig();
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
        var servertime = "<?php echo $serverTime;?>";
        var timeStandard = <?php echo $timeStandard;?>;
        var timeZone = "<?php echo $timezone_suffix;?>";
        var rftime = <?php echo $GLOBALS['settings']['rftime'];?>;

        $(document).ready(function () {
            setTimeout(syncServerTime(), settings.rftime); //delay is rftime
            updateTime();
        });
    </script>

    <script src="assets/js/clock.js"></script>

</head>

<body id="body" style="color: #FFFFFF;">

<div id="ajaxtimestamp" title="Analog clock timeout. Refresh page."></div>

<div class="header">

    <div id="left" class="Column">

        <div id="logoHeader" class="Column">
            <img src="assets/images/logo_white_glow_crop.png" alt="Logarr" title="Reload Logarr" onclick="window.location.reload(true);">
        </div>

        <div id="clock">
            <canvas id="canvas" width="120" height="120"></canvas>
            <div class="dtg" id="timer"></div>
        </div>

    </div>

    <!-- CHANGE ME // REMOVE "ON CLICK"? -->

    <div id="logo" class="Column">
        <img src="assets/images/logarr_white_text_crop.png" alt="Logarr" title="Reload Logarr" onclick="window.location.reload(true);">
    </div>

    <div id="right" class="Column">
    </div>

</div>
