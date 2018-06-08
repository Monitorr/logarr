<?php
include('../functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../../css/logarr.css" rel="stylesheet">
    <link type="text/css" href="../../css/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646"/>
    <meta name="theme_color" content="#464646"/>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/jquery.blockUI.js" async></script>
    <!-- <script type="text/javascript" src="../../js/pace.js" async></script> -->

    <style>

        body {
            margin: 2vw !important;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #1F1F1F;
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

        tbody {
            cursor: default !important;
        }

        select, input {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

    </style>


    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | Info
    </title>

</head>

<body>

<script>
    document.body.className += ' fade-out';
    $(function () {
        $('body').removeClass('fade-out');
    });
</script>

<div id="infodata">

    <table class="table">
        <tbody>
        <tr>
            <td><strong>Logarr Installed Version:</strong></td>
            <td><?php echo file_get_contents("../../js/version/version.txt") ?> <p id="version_check_auto"></p></td>
            <td><strong>OS / Version:</strong></td>
            <td><?php echo php_uname(); ?></td>
        </tr>
        <tr>
            <td><strong>Logarr Latest Version:</strong></td>
            <td>Master:
                <a href="https://github.com/Monitorr/logarr/releases" target="_blank" title="Logarr Releases">
                    <img src="https://img.shields.io/github/release/Monitorr/logarr.svg?style=flat" alt="Logarr Release"
                         style="width:6rem;height:1.1rem;">
                </a>
                | Develop:
                <a href="https://github.com/Monitorr/logarr/releases" target="_blank" title="Logarr Releases">
                    <img src="https://img.shields.io/github/release/Monitorr/logarr/all.svg" alt="Logarr Release"
                         style="width:6rem;height:1.1rem;">
                </a>
            </td>

            <td>
                <strong>PHP Version:</strong>
            </td>

            <td>

                <?php echo phpversion();

                echo " <strong> | Extensions: </strong> ";

                if (extension_loaded('curl')) {
                    echo " <div class='extok' title='PHP cURL extension loaded OK' >";
                    echo "cURL";
                    echo "</div>";
                } else {
                    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP cURL extension NOT loaded'>";
                    echo "cURL";
                    echo "</a>";
                }

                if (extension_loaded('sqlite3')) {
                    echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
                    echo "php_sqlite3";
                    echo "</div>";
                } else {
                    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
                    echo "php_sqlite3";
                    echo "</a>";
                }

                if (extension_loaded('pdo_sqlite')) {
                    echo " | <div class='extok' title='PHP pdo_sqlite extension loaded OK'>";
                    echo "pdo_sqlite";
                    echo "</div>";
                } else {
                    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
                    echo "pdo_sqlite";
                    echo "</a>";
                }

                if (extension_loaded('zip')) {
                    echo " | <div class='extok' title='PHP ZIP extension loaded OK'>";
                    echo "php7-zip";
                    echo "</div>";
                } else {
                    echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='php7-zip extension NOT loaded'>";
                    echo "php7-zip";
                    echo "</a>";
                }

                ?>

            </td>

        </tr>
        <tr>
            <td><strong>Check & Execute Update:</strong></td>
            <td>
                Update branch selected:
                <strong>
                    <?php
                    $updateBranch = $GLOBALS['preferences']['updateBranch'];
                    echo '| ' . $updateBranch . ' | ' . PHP_EOL;
                    ?>
                </strong>

                <a id="version_check" class="btn" style="cursor: pointer" title="Execute Update">Check for Update</a>
            </td>
            <td><strong>Install Path: </strong></td>
            <td>
                <?php
                $vnum_loc = "../../";
                echo realpath($vnum_loc), PHP_EOL;
                ?>

            </td>
        </tr>

        <tr>
            <td><strong>Tools:</strong></td>
            <td>
            </td>

            <td><strong>Resources:</strong></td>
            <td><a href="https://github.com/Monitorr/logarr" target="_blank" title="Logarr GitHub Repo"> <img
                            src="https://img.shields.io/badge/GitHub-repo-green.svg" style="width:4rem;height:1rem;"
                            alt="Logarr GitHub Repo"></a> | <a href="https://hub.docker.com/r/Monitorr/logarr/"
                                                               target="_blank" title="Logarr Docker Repo"> <img
                            src="https://img.shields.io/docker/build/Monitorr/logarr.svg?maxAge=2592000"
                            style="width:6rem;height:1rem;" alt="Logarr Docker Repo"></a> | <a
                        href="https://feathub.com/Monitorr/logarr" target="_blank" title="Logarr Feature Request"> <img
                            src="https://img.shields.io/badge/FeatHub-suggest-blue.svg" style="width:5rem;height:1rem;"
                            alt="Logarr Feature Request"></a> | <a href="https://discord.gg/j2XGCtH" target="_blank"
                                                                   title="Logarr Discord Channel"> <img
                            src="https://img.shields.io/discord/102860784329052160.svg" style="width:5rem;height:1rem;"
                            alt="Logarr on Discord"></a> | <a href="https://paypal.me/monitorrapp" target="_blank"
                                                              title="Buy us a beer!"> <img
                            src="https://img.shields.io/badge/Donate-PayPal-green.svg" style="width:4rem;height:1rem;"
                            alt="PayPal"></a></td>
        </tr>
        </tbody>
    </table>

</div>

<div class="slide">
    <div id="phpContent"></div>
</div>

<script>document.getElementById("phpContent").innerHTML = '<object type="text/html" class="phpobject" data="../phpinfo.php" ></object>'</script>

<script src="../../js/update-settings.js" async></script>


</body>

</html>