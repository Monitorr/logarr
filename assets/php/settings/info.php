<?php
include('../functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../../css/logarr.css" rel="stylesheet">
    <link type="text/css" href="../../data/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646"/>
    <meta name="theme_color" content="#464646"/>

    <script src="../../js/jquery.min.js"></script>
    <script src="../../js/jquery.blockUI.js" async></script>
    <!-- <script type="text/javascript" src="../../js/pace.js" async></script> -->

    <title>
	    <?php
	    $title = $GLOBALS['preferences']['sitetitle'];
	    echo $title . PHP_EOL;
	    ?>
        | Info
    </title>

</head>

<body id="settings-frame-wrapper">

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


	            if (extension_loaded('sqlite3')) {
		            echo " <div class='extok' title='PHP sqlite3 extension loaded OK'>";
		            echo "php_sqlite3";
		            echo "</div>";
	            } else {
		            echo " <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
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
            <td></td>
            <td></td>
            <!--<td><strong>Tools:</strong></td>
            <td>
                <a href="" id="registration-link" class="toolslink" title="Registration Page">Registration</a>
            </td>
            <script>
                $(document).on('click', '#registration-link', function (event) {
                    event.preventDefault();
                    console.log('navigating to registration page');
                    window.top.location.href = '../../../settings.php?action=register';
                });
            </script>-->

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


</body>

</html>