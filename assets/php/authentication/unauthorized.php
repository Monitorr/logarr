<!DOCTYPE html>
<html lang="en">

<!--
                LOGARR
    by @seanvree, @jonfinley, and @rob1998
        https://github.com/Monitorr
-->

<!-- unauthorized.php -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="manifest" href="webmanifest.json">

    <link rel="icon" type="image/png" href="favicon.png">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool.">
    <meta name="application-name" content="Logarr">
    <meta name="theme-color" content="#464646">
    <meta name="theme_color" content="#464646">

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <title>Logarr | Unauthorized</title>

    <style>
        <?php include(__DIR__ . '/../../css/bootstrap.min.css'); ?>
        <?php include(__DIR__ . '/../../css/font-awesome.min.css'); ?>
        <?php include(__DIR__ . '/../../css/logarr.css'); ?>
        <?php include(__DIR__ . '/../../data/custom.css'); ?>
    </style>

</head>

<body id="body" style="color: #FFFFFF;">

    <?php
        $str = file_get_contents(__DIR__ . "/../../data/datadir.json");
        $json = json_decode($str, true);
        $datadir = $json['datadir'];
        $datafile = $datadir . 'users.db';
        $db_sqlite_path = $datafile;
    ?>

    <div class="header-login">
        <a id="logo-unauth" class="Column" href="https://github.com/monitorr/Logarr" target="_blank" title="Logarr">
            <img src="assets/images/logarr_white_text_crop.png" alt="Logarr" title="Logarr">
        </a>
    </div>

    <div id="registration-container" class="flex-child unauth-container">
        <div id="regerror">
            Access to this page is DISABLED
        </div>

        <!-- //TODO:  Fix relative links  -->

        <div id="regbody">
            + If you are the Administrator and are trying to access the Logarr Setup tool, change the 'Enable Setup Access' setting to 'True' in the <a class="footer reglink" href='settings.php#logarr-authentication' title="Authentication Settings" target="_blank">Authentication Settings page</a>.
            <br><br>
            + If you cannot access the Logarr Settings page, rename the database file: "<strong> <i> <?php echo $db_sqlite_path; ?> </i> </strong>" and browse to this page again.
            <br><br>
            + If invalid values are detected in the Logarr Configuration File ("config.json") in the Data Directory, this page will automatically load to prevent unauthorized  access to Logarr.
            <br><br>
            + <a class="footer reglink" href='index.php' title="Logarr" target='_blank'> Return to the Logarr UI. </a>
        </div>
    </div>

    <div id="footer">

        <div id="logarrid">
            <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank" class="footer">Logarr </a> |
            <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a>
            <br>
        </div>

    </div>

</body>

</html> 