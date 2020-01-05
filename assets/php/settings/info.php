<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">

<!-- info.php -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/vendor/sweetalert2.min.css">
    <link rel="stylesheet" href="../../css/vendor/jquery-ui.min.css">
    <link rel="stylesheet" href="../../css/logarr.css">
    <link rel="stylesheet" href="../../data/custom.css">

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | Settings
    </title>

    <style>
        table {
            color: white !important;
        }

        td {
            font-size: 1rem;
            vertical-align: middle !important;
        }

        .swal2-popup.swal2-toast {
            cursor: default !important;
        }
    </style>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/vendor/jquery-ui.min.js"></script>
    <script src="../../js/vendor/sweetalert2.min.js"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            showCloseButton: true,
            position: 'bottom-start',
            background: 'rgba(50, 1, 25, 0.75)'
        });

        function exterror() {
            Toast.fire({
                toast: true,
                type: 'error',
                title: 'PHP extension not loaded!',
                background: 'rgba(207, 0, 0, 0.75)',
                timer: 5000
            })
        };
    </script>

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

</head>

<body id="info-frame-wrapper">

    <script>
        document.body.className += ' fade-out';
        $(function() {
            $('body').removeClass('fade-out');
        });
    </script>

    <div id="infodata">

        <table class="table">
            <tbody>
                <tr>
                    <td class="infotitle">Logarr Installed Version:</td>
                    <td>
                        <?php echo file_get_contents("../../js/version/version.txt") ?>
                        <p id="version_check_auto" class="version_check_info"></p>
                    </td>
                    <td class="infotitle">OS / Version:</td>
                    <td>
                        <?php echo php_uname(); ?>
                    </td>
                </tr>
                <tr>
                    <td class="infotitle">Logarr Latest Version:</td>
                    <td>Master:
                        <a href="https://github.com/Monitorr/logarr/releases" target="_blank" title="Logarr Releases">
                            <img class="releasebadge" src="https://img.shields.io/github/release/Monitorr/logarr.svg?style=flat" alt="Logarr Release" style="width:6rem;height:1.1rem;">
                        </a>
                        | Develop:
                        <a href="https://github.com/Monitorr/logarr/releases" target="_blank" title="Logarr Releases">
                            <img class="releasebadge" src="https://img.shields.io/github/release/Monitorr/logarr/all.svg" alt="Logarr Release" style="width:6rem;height:1.1rem;">
                        </a>
                    </td>

                    <td class="infotitle">PHP Version:</td>

                    <td>

                        <?php echo phpversion();

                        echo " <strong> | Extensions: </strong> ";

                        $myfile = fopen('../../data/php-perms-check.txt', 'w');

                        if (!$myfile) {
                            echo " <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP write permissions FAIL'>";
                            echo "Perms";
                            echo "</a>";
                            echo "<script>console.log( 'ERROR: PHP write permissions FAIL' );</script>";
                            echo "<script>exterror();</script>";
                            appendLog(
                                $logentry = "ERROR: PHP write permissions FAIL"
                            );
                        } else {
                            echo "<div class='extok' title='PHP write permissions OK' >";
                            echo "Perms";
                            echo "</div>";
                            fwrite($myfile, "Logarr PHP write permissions O.K \r\nThis file can be safely removed, however will be regenerated every time a user logs into Logarr Settings. ");
                            fclose($myfile);
                        }

                        //Check if datadir is writable:
                        $str = file_get_contents(__DIR__ . "/../../data/datadir.json");
                        $json = json_decode($str, true);
                        $datadir = $json['datadir'];
                        $datafile = $datadir . 'php-perms-check.txt';
                        $datadirfile = $datafile;

                        $datadircheck = fopen($datadirfile, 'w');

                        if (!$datadircheck) {
                            echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP Datadir write permissions FAIL'>";
                            echo "Data";
                            echo "</a>";
                            echo "<script>console.log( 'ERROR: Logarr PHP Data dir write permissions FAIL' );</script>";
                            echo "<script>exterror();</script>";
                            appendLog(
                                $logentry = 'ERROR: Logarr PHP Data dir write permissions FAIL: ' . $datafile
                            );
                        } else {
                            echo " | <div class='extok' title='PHP Datadir write permissions OK' >";
                            echo "Data";
                            echo "</div>";
                            fwrite($datadircheck, "Logarr PHP Datadir write permissions O.K \r\nThis file can be safely removed, however it will be regenerated every time a user logs into Logarr Settings. ");
                            fclose($datadircheck);
                            echo "<script>console.log( 'Logarr PHP Datadir write permissions OK' );</script>";
                        }

                        if (extension_loaded('sqlite3')) {
                            echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
                            echo "php_sqlite3";
                            echo "</div>";
                        } else {
                            echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
                            echo "php_sqlite3";
                            echo "</a>";
                            echo "<script>exterror();</script>";
                            appendLog(
                                $logentry = "ERROR: PHP php_sqlite3 extension NOT loaded"
                            );
                        }

                        if (extension_loaded('pdo_sqlite')) {
                            echo " | <div class='extok' title='PHP pdo_sqlite extension loaded OK'>";
                            echo "pdo_sqlite";
                            echo "</div>";
                        } else {
                            echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
                            echo "pdo_sqlite";
                            echo "</a>";
                            echo "<script>exterror();</script>";
                            appendLog(
                                $logentry = "ERROR: PHP pdo_sqlite extension NOT loaded"
                            );
                        }

                        if (extension_loaded('zip')) {
                            echo " | <div class='extok' title='PHP ZIP extension loaded OK'>";
                            echo "php7-zip";
                            echo "</div>";
                        } else {
                            echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='php7-zip extension NOT loaded'>";
                            echo "php7-zip";
                            echo "</a>";
                            echo "<script>exterror();</script>";
                            appendLog(
                                $logentry = "ERROR: PHP ZIP extension NOT loaded"
                            );
                        }

                        if (extension_loaded('openssl')) {
                            echo " | <div class='extok' title='PHP openssl extension loaded OK'>";
                            echo "OpenSSL";
                            echo "</div>";
                        } else {
                            echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP openssl extension NOT loaded'>";
                            echo "OpenSSL";
                            echo "</a>";
                            echo "<script>console.log( 'ERROR: PHP openssl extension NOT loaded' );</script>";
                            echo "<script>exterror();</script>";
                            appendLog(
                                $logentry = "ERROR: PHP openssl extension NOT loaded"
                            );
                        }

                        ?>

                    </td>
                </tr>

                <tr>
                    <td class="infotitle">Check & Execute Update:</td>
                    <td>
                        Update branch selected:
                        <strong>
                            <?php
                            $updateBranch = strtoupper($GLOBALS['preferences']['updateBranch']);
                            echo '| ' . $updateBranch . ' | ' . PHP_EOL;
                            ?>
                        </strong>

                        <a id="version_check" class="btn btn-primary updatebtn" style="cursor: pointer" title="Execute Update">Check for Update</a>
                    </td>
                    <td class="infotitle">Install Path:</td>
                    <td>
                        <?php
                        $vnum_loc = "../../../";
                        echo realpath($vnum_loc), PHP_EOL;
                        ?>
                    </td>
                </tr>

                <tr>
                    <td class="infotitle">Resources:</td>
                    <td id="resources">
                        <a href="https://github.com/Monitorr/logarr" target="_blank" title="Logarr GitHub Repo"> <img class="resourceimg" src="https://img.shields.io/badge/GitHub-repo-green.svg" style="width:4rem;height:1rem;" alt="Logarr GitHub Repo"></a> |
                        <a href="https://hub.docker.com/r/monitorr/logarr/" target="_blank" title="Logarr Docker Repo"> <img class="resourceimg" src="https://img.shields.io/docker/build/monitorr/logarr.svg?maxAge=2592000" style="width:6rem;height:1rem;" alt="Logarr Docker Repo"></a> |
                        <a href="https://feathub.com/Monitorr/logarr" target="_blank" title="Logarr Feature Request"> <img class="resourceimg" src="https://img.shields.io/badge/FeatHub-suggest-blue.svg" style="width:5rem;height:1rem;" alt="Logarr Feature Request"></a> |
                        <a href="https://discord.gg/j2XGCtH" target="_blank" title="Logarr Discord Channel"> <img class="resourceimg" src="https://img.shields.io/discord/102860784329052160.svg" style="width:5rem;height:1rem;" alt="Logarr on Discord"></a> |
                        <a href="https://paypal.me/monitorrapp" target="_blank" title="Buy us a beer!"> <img class="resourceimg" src="https://img.shields.io/badge/Donate-PayPal-green.svg" style="width:4rem;height:1rem;" alt="PayPal"></a>
                    </td>

                    <td class="infotitle">Database Path:</td>
                    <td>
                        <?php

                        $str = file_get_contents(__DIR__ . "/../../data/datadir.json");
                        $json = json_decode($str, true);
                        $datadir = $json['datadir'];
                        $datafile = $datadir . 'users.db';
                        $db_sqlite_path = $datafile;

                        echo $db_sqlite_path;

                        ?>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div>
        <table id="infoframe">
            <tr>
                <td class="frametd">
                    <div class="version">
                        <div id="versioncontent"></div>
                    </div>
                </td>

                <td class="frametd">
                    <div class="php">
                        <div id="phpcontent"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <script>
        document.getElementById("versioncontent").innerHTML = '<object id="versionobject" type="text/html" data="../../../changelog.html" ></object>';
        document.getElementById("phpcontent").innerHTML = '<object id="phpobject" type="text/html" data="../phpinfo.php" ></object>';
    </script>

    <script src="../../js/update-settings.js" async></script>

    <!-- Close persistant tooltips: -->
    <script>
        $(window).blur(function() {
            $('a').blur();
        });
    </script>

</body>

</html>