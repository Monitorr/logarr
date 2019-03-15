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

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool." />
    <meta name="application-name" content="Logarr" />
    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/logarr.css">
    <link rel="stylesheet" href="assets/data/custom.css">

    <title>Logarr | Unauthorized</title>

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
            <img src="assets/images/logarr_white_text_crop.png" alt="Logarr" a href="https://github.com/monitorr/Logarr" target="_blank" title="Logarr"></a>
        </a>
    </div>


    <div id="registration-container" class="flex-child unauth-container">
        <div id="regerror">
            Access to this page is DISABLED
        </div>

        <div id="regbody">
            + If you are the administrator and are trying to access the Logarr Configuration tool, change the 'Enable Configuration Access' setting to 'True' in the <a class="footer reglink" href='settings.php#logarr-authentication' title="Authentication Settings" target="_blank" >Authentication Settings page</a>.
            <br><br>
            + If you cannot access the Logarr settings page, rename the database file: "<strong> <i> <?php echo $db_sqlite_path; ?> </i> </strong>" and browse to this page again.
            <br><br>
            + <a class="footer reglink" href='index.php' title="Logarr" target='_blank'> Return to the Logarr UI. </a>
        </div>
    </div>

    <div id="footer">

        <div id="logarrid">
            <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank"
            class="footer">Logarr </a> |
            <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a>
            <br>
        </div>

    </div>

</body>

</html>
