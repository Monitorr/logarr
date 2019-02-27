<?php include(__DIR__ . "/header.php"); ?>

<?php
    $str = file_get_contents(__DIR__ . "/../../data/datadir.json");
    $json = json_decode($str, true);
    $datadir = $json['datadir'];
    $datafile = $datadir . 'users.db';
    $db_sqlite_path = $datafile;
?>

<div id="registration-container" class="flex-child unauth-container">
    <div id="regerror">
        Access to this page is DISABLED.
    </div>

    <div id="regbody">
        + If you are the administrator and are trying to access the Logarr registration tool, change the 'Enable Registration' setting to 'True' in the <a class="footer reglink" href='settings.php#authentication' title="Authentication Settings" > Authentication settings page </a>.
        <br><br>
        + If you cannot access the Logarr settings page, rename the database file "<strong> <i> <?php echo $db_sqlite_path; ?> </i> </strong>" and browse to this page again.
        <br><br>
        + <a class="footer reglink" href='index.php' title="Logarr" target='_blank'> Return to the Logarr UI. </a>
    </div>
</div>

<?php include(__DIR__ . "/footer.php"); ?> 