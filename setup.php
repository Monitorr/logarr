<?php
require_once(__DIR__ . "/assets/php/auth_check.php");
require_once(__DIR__ . "/assets/php/functions/setup.functions.php");

if (isset($_POST['action'])) {
    $result = array("status" => "failed");
    switch ($_POST['action']) {
        case "datadir":
            if (isset($_POST['datadir']) && !empty($_POST['datadir'])) {
                if (createDatadir($_POST['datadir'])) {
                    $result["status"] = "success";
                    $result["response"] = $_POST['datadir'];
                }
            }
            break;
        case "registerUser":
            appendLog("Logarr is creating a new user");
            $result["status"] = $authenticator->doRegistration() ? "success" : "failed";
            $result["responseuser"] = $authenticator->feedback;
            break;
        default:
            break;
    }
    echo json_encode($result);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

    <!--
                    LOGARR
        by @seanvree, @rob1998, and @jonfinley
            https://github.com/Monitorr
    -->

    <!-- setup.php -->

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <link rel="manifest" href="webmanifest.json">

        <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool.">
        <meta name="application-name" content="Logarr">
        <meta name="robots" content="NOINDEX, NOFOLLOW">

        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/vendor/sweetalert2.min.css">
        <link rel="stylesheet" href="assets/css/vendor/jquery-ui.min.css">
        <link rel="stylesheet" href="assets/css/logarr.css">
        <link rel="stylesheet" href="assets/data/custom.css">

        <link rel="icon" type="image/png" href="favicon.png">

        <meta name="theme-color" content="#464646">
        <meta name="theme_color" content="#464646">

        <title>Logarr | Setup</title>

        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/vendor/sweetalert2.min.js"></script>
        <script src="assets/js/vendor/formValidation.js"></script>
        <script src="assets/js/vendor/jquery-ui.min.js"></script>
        <script src="assets/js/logarr.main.js"></script>

        <?php appendLog("Logarr Setup loaded"); ?>

        <style>
            .input {
                width: 20vw !important;
                max-width: 15rem !important;
            }

            .swal2-loading {
                position: fixed;
                padding-bottom: 1rem;
            }

            /* TODO // BUG: Tooltips appended to disabled buttons do not inherit custom styling */
            .ui-tooltip {
                background: rgba(50, 1, 25, 0.90) !important;
            }
        </style>

        <script>
            <?php
            echo "var datadir = " . $authenticator->doesDataDirExist() . ";";
            echo "var datauser = " . $authenticator->doesUserExist() . ";";
            echo "var docker = " . isDocker();
            ?>
        </script>

        <script>
            $(document).ready(function() {

                if (datadir == true) {
                    window.location.href = 'setup.php#users';
                    $('#datadircircle').removeClass('circlenotcomplete');
                    $('#datadircircle').addClass('circlecomplete');
                } else {
                    console.log("%cWARNING: The data dir has not been established", "color: red;");
                    $('#datadircircle').addClass('circlenotcomplete');
                };

                if (typeof datauser == "undefined") {
                    console.log("Welcome to %cLogarr", "color: #FF0104; font-size: 2em;");
                    console.log("%cERROR: datauser undefined", "color: red;");
                    console.log("%cWARNING: User NOT established in users.db database", "color: red;");
                    $('#registration-header').removeClass('hidden');
                    $('#extensions').removeClass('hidden');
                    $('#footer').removeClass('hidden');
                    $('#usercircle').addClass('circlenotcomplete');
                    $('#setupcircle').removeClass('circlecomplete');
                    $('#setupcircle').addClass('circlenotcomplete');
                    toastwelcome();
                } else {
                    if (datauser == true) {
                        console.log("User established in users.db database");
                        $('#datadircircle').removeClass('circlecomplete');
                    } else {
                        console.log("Welcome to %cLogarr", "color: #FF0104; font-size: 2em;");
                        console.log("%cWARNING: User NOT established in users.db database", "color: red;");
                        $('#registration-header').removeClass('hidden');
                        $('#extensions').removeClass('hidden');
                        $('#footer').removeClass('hidden');
                        $('#usercircle').addClass('circlenotcomplete');
                        $('#setupcircle').removeClass('circlecomplete');
                        $('#setupcircle').addClass('circlenotcomplete');
                        toastwelcome();
                    };
                };
                if (typeof docker == "undefined") {
                    console.log("%cERROR: docker undefined", "color: red;");
                } else {
                    if (docker == true) {
                        $('#docker').addClass('dockerwarn');
                        console.log("Logarr detected DOCKER environment");
                    };
                };
            });
        </script>

        <script>
            function switchTabs(newHash) {

                let targets = $(".stepper-target");

                if (newHash === "" || newHash === "#") newHash = "#datadir";
                let newTarget = $(newHash);

                //TODO / BUG / If user removes users.db and re-creates, "complete" step will not be shown after user creation:
                targets.hide();

                newTarget.fadeIn();
                window.location.hash = newHash;
            }

            $(function() {
                switchTabs(window.location.hash);
            });
        </script>

        <!-- datadir form submit: -->
        <script>
            $(document).ready(function() {

                $('#datadirform').submit(function(e) {
                    e.preventDefault();
                    let datadir = $("#datadir-input").val();
                    $.ajax({
                        type: "POST",
                        url: "setup.php",
                        data: {
                            datadir: datadir,
                            action: "datadir"
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status === "success") {
                                console.log("Success: datadir set to: " + data.response);
                                $("#users").load(location.href + " #users>*", "");
                                $('#response').addClass('regsuccess');
                                $("#response").text("Data directory created successfully: " + data.response);
                                $('#datadircircle').removeClass('active');
                                $("#datadirstep").removeClass("active");
                                $('#datadircircle').addClass('completed');
                                $('#datadircircle').removeClass('circlenotcomplete');
                                $('#datadircircle').addClass('circlecomplete');
                                $("#userstep").addClass("active");
                                $('#usercircle').addClass('active');
                                $('#usernext').removeClass('disabled');
                                $('#usernext').prop('disabled', false);
                                usercomplete();
                                datadirsuccess();
                                switchTabs("#users");
                                setupwarning();
                            } else {
                                console.log("%cERROR: Failed to create data directory", "color: #FF0104;");
                                $('#datadirbtn').prop('disabled', true);
                                $('#usernext').prop('disabled', true);
                                datadirerror();
                                $('#response').addClass('regerror');
                                $("#response").text("Failed to create data directory: " + data.response);
                            }
                        },
                        error: function(data) {
                            datadirerror();
                            console.log("%cERROR: Posting failed (ajax)", "color: #FF0104;");
                            console.log(data);
                        }
                    });
                });
            });
        </script>

        <!-- user form submit: -->
        <script>
            $(document).on("submit", "#userform", function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "setup.php",
                    data: {
                        action: "registerUser",
                        user_name: $("#login_input_username").val(),
                        user_password_new: $("#login_input_password_new").val(),
                        user_password_repeat: $("#login_input_password_repeat").val(),
                        user_email: $("#login_input_email").val(),
                        register: true
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        if (data.status === "success") {
                            console.log("Success: " + data.responseuser);
                            $('.setup').removeClass('hidden');
                            $("#setup").load(location.href + " #setup>*", "");
                            $('#responseuser').addClass('regsuccess');
                            $("#responseuser").text("User created successfully!");
                            $("#userstep").removeClass("active");
                            $('#usercircle').removeClass('active');
                            $("#userstep").addClass("completed");
                            $('#usercircle').addClass('completed');
                            $("#setupstep").addClass("active");
                            $("#setupstep").removeClass("hidden");
                            $('#usercircle').removeClass('circlenotcomplete');
                            $('#usercircle').addClass('circlecomplete');
                            $('#setupcircle').removeClass('circlenotcomplete');
                            $('#setupcircle').addClass('circlecomplete');
                            setupcomplete();
                            usersuccess();
                            switchTabs("#setup");

                            // Reload setup page after user creation / setup complete:
                            setTimeout(function() {
                                sareload();
                            }, 3000);

                        } else {
                            $('#responseuser').addClass('regerror');
                            $('#registerbtn').prop('disabled', true);
                            $("#login_input_username, #login_input_password_repeat, #login_input_password_new").keyup(function(e) {
                                $('#registerbtn').prop('disabled', false);
                            });
                            usererror();
                            $("#responseuser").text("Failed to create user: " + data.responseuser);
                            console.log("%cERROR: Failed to create user", "color: #FF0104;");
                        }
                    },
                    error: function(data) {
                        usererror();
                        console.log("%cERROR: Posting failed (ajax)", "color: #FF0104;");
                        console.log(data);
                    }
                });
            });
        </script>

        <!-- Disable buttons if input field is invalid: -->
        <script>
            $(document).ready(function() {
                $('#datadir').each(
                    function() {
                        var val = $(this).val().trim();
                        if (val == '') {
                            $('#datadirbtn').prop('disabled', true);
                        }
                    }
                );
                $('#login_input_password_repeat').each(
                    function() {
                        var val = $(this).val().trim();
                        if (val == '') {
                            $('#registerbtn').prop('disabled', true);
                        }
                    }
                );
            });
        </script>

        <!-- Enables user mgmt tab if setup complete: -->
        <script>
            function usercomplete() {
                document.getElementById("userstep").onclick = function() {
                    switchTabs('#users');
                };
            }

            function setupcomplete() {
                document.getElementById("setupstep").onclick = function() {
                    switchTabs('#setup');
                };
            }
        </script>

        <!-- Tooltips: -->
        <script>
            $(function() {
                $(document).tooltip({
                    position: {
                        my: "left top",
                        at: "right+5 top-5",
                        collision: "flipfit"
                    },
                    hide: {
                        effect: "fadeOut",
                        duration: 200
                    }
                });
            });
        </script>

    </head>

    <body id="body" style="color: #FFFFFF;">

        <script>
            document.body.className += ' fade-out';
            $(function() {
                $('body').removeClass('fade-out');
            });
        </script>

        <div id="registration-header" class="flex-grid hidden">
            <div id="logo-reg" class="col">
                <img src="assets/images/logarr_white_text_crop.png" alt="Logarr">
            </div>
            <div id="registration-title" class="header-brand col">
                | Setup
            </div>
        </div>

        <div id="setup-container" class="flex-child">
            <!-- Horizontal Steppers -->
            <div class="row">
                <div class="col-md-12">

                    <!-- Steppers Wrapper -->
                    <ul class="stepper stepper-horizontal">

                        <?php
                        $datadirStepClass = "";
                        $userStepClass = "";
                        $setupStepClass = "";

                        if (!$authenticator->doesDataDirExist()) {
                            $datadirStepClass = "active";
                        } else if (!$authenticator->isDatadirSetup()) {
                            $datadirStepClass = "active";
                        } else if (!$authenticator->databaseExists()) {
                            $datadirStepClass = "completed";
                            $userStepClass = "active";
                        } else if (!$authenticator->configFileExists()) {
                            $datadirStepClass = "completed";
                            $userStepClass = "completed";
                            $setupStepClass = "active";
                        } else {
                            $datadirStepClass = "completed";
                            $userStepClass = "completed";
                            $setupStepClass = "success";
                        }
                        ?>

                        <!-- First Step -->
                        <li id="datadirstep" class="<?php echo $datadirStepClass; ?>" onclick="switchTabs('#datadir');">
                            <a class="cursorpoint" href="#datadir" title="Data Directory">
                                <span id="datadircircle" class="circle <?php echo $datadirStepClass; ?>">1</span>
                                <span class="label">Data Directory</span>
                            </a>
                        </li>

                        <!-- Second Step -->
                        <li id="userstep" class="<?php echo $userStepClass; ?>">
                            <a id="usersteplink" href="#users" title="User Management">
                                <span id="usercircle" class="circle <?php echo $userStepClass; ?>">2</span>
                                <span class="label">User Management</span>
                            </a>
                        </li>

                        <!-- Third Step -->
                        <li id="setupstep" class="setup <?php echo $setupStepClass; ?>">
                            <a href="#setup" title="Complete">
                                <span id="setupcircle" class="circle <?php echo $setupStepClass; ?>"><i class="fas fa-check"></i></span>
                                <span class="label">Complete</span>
                            </a>
                        </li>

                    </ul>
                    <!-- /.Steppers Wrapper -->

                </div>
            </div>
            <!-- Horizontal Steppers -->

            <!--  START datadir create form -->
            <div id="datadir" class="stepper-target">

                <h2 class="heading setupheader">Create a data directory:</h2>

                <?php
                if ($authenticator->isDatadirSetup()) {
                    echo '<div id="loginerror" class="warning">';
                    echo '<p id="datadirwarn">';
                    echo '<i class="fa fa-fw fa-exclamation-triangle"> </i> WARNING: An existing data directory is detected at: ';
                    echo $authenticator->datadir;
                    echo '</p>';
                    echo ' + If a new data directory is created, the current data directory will NOT be altered, however, Logarr will use all default resources from the newly created data directory.';
                    echo ' <br> + All settings and user credentials will be reset to default.';
                    echo ' <br> + After creating a new data directory, you must create a user within 2 minutes.';
                    echo '<br>';
                    echo '</div>';
                }
                ?>

                <form id="datadirform" method="post">

                    <div>
                        <i class='fa fa-fw fa-folder-open'> </i>

                        <?php
                        if (isDocker()) {
                            echo "<input type='search' class=\"input dockerinput\" disabled readonly name='datadir' id=\"datadir-input\" title=\"Changing the Data Directory while using Docker is not possible.\" fv-not-empty=\" This field cannot be empty\" fv-advanced='{\"regex\": \"\\\s\", \"regex_reverse\": true, \"message\": \"  Value cannot contain spaces\"}' spellcheck=\"false\" autocomplete=\"off\" placeholder=' Data dir path' value=\"$authenticator->datadir\" required>";
                        } else {
                            echo "<input type='search' class=\"input standardinput\" name='datadir' id=\"datadir-input\" title=\"Data directory path\" fv-not-empty=\" This field cannot be empty\" fv-advanced='{\"regex\": \"\\\s\", \"regex_reverse\": true, \"message\": \"  Value cannot contain spaces\"}' spellcheck=\"false\" autocomplete=\"off\" placeholder=' Data dir path' value=\"$authenticator->datadir\" required>";
                        }
                        ?><br>
                        <p id="configpath">
                            <i class="fa fa-fw fa-info-circle"> </i>
                            <?php echo "Current absolute path: " . getcwd() ?>
                        </p>
                    </div>
                    <br>
                    <div id='response'></div>
                    <div>
                        <input type="hidden" name="action" value="datadir">
                        <input type='submit' id="datadirbtn" class="btn btn-primary setupBtn" title="Create data directory" value='Create' />
                        <button type='button' id="usernext" class="btn btn-primary disabled buttonchange" title="Create user" onClick='switchTabs("#users");'>Next <i class="fas fa-angle-right"></i></button>
                    </div>

                </form>

                <div id="loginerror" class="warning">
                    <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
                </div>

                <div id="datadirnotes">
                    <i>
                        + The directory that is chosen must NOT already exist, however CAN be a sub directory of an existing directory.
                        <br>
                        + Value must be an absolute path on the server's filesystem.
                        <br>
                        <p id="docker" class="<?php if (isDocker()) echo 'dockerwarn'; ?>">+ It is NOT possible to change the data directory location if using Docker.</p>
                        + For security purposes, this directory should NOT be within the webserver's filesystem hierarchy.
                        However, if a path is chosen outside the webserver's filesystem, the PHP process must have read/write privileges to the chosen data directory.

                    </i>
                </div>

            </div>
            <!--  END datadir create form -->

            <!-- START user create form -->
            <div id="users" class="stepper-target">
                <?php
                if ($authenticator->isDatadirSetup()) {
                    ?>
                    <!--  START multi form -->

                    <div id="multiform">

                        <div id="loginmessage">
                            <h2 class="heading setupheader">Create a new user:</h2>
                            <i class="fa fa-fw fa-info-circle"> </i>
                            Current database:
                            <?php echo $authenticator->datadir . "users.db"; ?>
                            <br>
                        </div>

                        <form id="userform" method="post">
                            <table id="registrationtable">
                                <tbody id="registrationform">
                                    <tr id="usernameinput">
                                        <td><i class="fa fa-fw fa-user"> </i> <input id="login_input_username" type="search" class="input" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder=" Username" title="Enter a username" fv-not-empty='' required spellcheck="false" autocomplete="off"></td>
                                        <td><label for="login_input_username"><i> Letters and numbers only, 2 to 64 characters </i></label></td>
                                    </tr>

                                    <tr id="useremail">
                                        <td><i class='fa fa-fw fa-envelope'> </i> <input id='login_input_email' type='email' class="input" name='user_email' placeholder=' User e-mail' spellcheck="false"></td>
                                        <td><label for="login_input_email"> <i> Not required </i></label></td>
                                    </tr>

                                    <tr id="userpassword">
                                        <td>
                                            <i class='fa fa-fw fa-key'></i>
                                            <input id='login_input_password_new' class='login_input input' type='password' name='user_password_new' pattern='.{6,}' fv-not-empty='' required autocomplete='off' placeholder=' Password' title='Enter a password' />
                                        </td>
                                        <td>
                                            <input id='login_input_password_repeat' class='login_input input' type='password' name='user_password_repeat' pattern='.{6,}' fv-not-empty='' fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "Value cannot contain spaces"}' fv-valid-func='$( "#registerbtn" ).prop( "disabled", false )' fv-invalid-func='$( "#registerbtn" ).prop( "disabled", true )' required autocomplete='off' placeholder=' Repeat password' title='Repeat password' />
                                            <i> Minimum 6 characters </i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div id='responseuser'></div>
                            <br>
                            <input id="registerbtn" type="submit" class="btn btn-primary setupBtn" name="register" value="Register" title="Create user">
                        </form>

                        <div id="loginerror" class="warning">
                            <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
                        </div>
                        <div id="usernotes">
                            + It is NOT possible to change a user's credentials after creation.<br>
                            + If credentials need to be changed or reset, rename the file "users.db" to "users.db.old" in your data directory. <br>
                            + Once that file is renamed, browse to index.php, and you will be automatically prompted to create a new user. <br>
                            + This process will NOT reset your settings.
                        </div>
                    </div>
                <?php

                }
                ?>

            </div>
            <!-- END user create form -->

            <!-- START Setup -->

            <div id="setup" class="hidden stepper-target setup">

                <?php
                if ($authenticator->isDatadirSetup()) {
                    if ($authenticator->databaseExists()) {
                        if ($authenticator->doesUserExist()) {
                            if (!$authenticator->configFileExists()) {
                                copyDefaultConfig($authenticator->datadir);
                            }
                            ?>

                            <img id="setup-icon" src="assets/images/logarr_white_text_crop.png" alt="Logarr">
                            <div id="setupcomplete"> Logarr Setup is complete!</div>
                            <div id="setupreload"> Logarr will reload in 10 seconds</div>

                            <p id='regsettingnote'>
                                <i class='fas fa-exclamation-triangle'></i> For security purposes, ensure to change the Authentication Setting: 'Enable Setup Access' to ('FALSE') after initial setup.
                            </p>

                <?php
                        }
                    }
                }
                ?>
            </div>
            <!-- END Setup -->

        </div>

        <?php

        echo "<div id='extensions' class='hidden'>";

        echo "<div id='extensiontitle'> Required PHP Extensions: </div> ";

        //Check if '/assets/data/' dir is writable:
        $myfile = fopen('assets/data/php-perms-check.txt', 'w+');
        $date = date("D d M Y H:i T");


        if (!$myfile) {
            echo " <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP write permissions FAIL'>";
            echo "Perms";
            echo "</a>";
            echo "<script>console.log( 'ERROR: PHP write permissions FAIL' );</script>";
            appendLog(
                $logentry = "ERROR: PHP write permissions FAIL"
            );
        } else {
            echo " <div class='extok' title='PHP write permissions OK' >";
            echo "Perms";
            echo "</div>";
            fwrite($myfile, "\r\n" . $date . "\r\n" . "Logarr PHP write permissions O.K \r\nThis file can be safely removed, however it will be regenerated every time a user logs into Logarr Settings.");
            fclose($myfile);
        }

        if (extension_loaded('sqlite3')) {
            echo " | <div class='extok' title='PHP sqlite3 extension loaded OK'>";
            echo "php_sqlite3";
            echo "</div>";
        } else {
            echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Initial-installation' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
            echo "php_sqlite3";
            echo "</a>";
            echo "<script>console.log( 'ERROR: PHP sqlite3 extension NOT loaded' );</script>";
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
            echo "<script>console.log( 'ERROR: PHP pdo_sqlite extension NOT loaded' );</script>";
            appendLog(
                $logentry = "ERROR: PHP php_sqlite extension NOT loaded"
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
            echo "<script>console.log( 'ERROR: PHP php7-zip extension NOT loaded' );</script>";
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
            appendLog(
                $logentry = "ERROR: PHP openssl extension NOT loaded"
            );
        }

        echo "</div>";
        ?>

        <div id="footer" class="hidden">
            <div id="logarrid">
                <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank" class="footer">Logarr
                </a> |
                <a href="https://github.com/monitorr/logarr/releases" title="Logarr releases" target="_blank" class="footer"> Releases </a>
                <br>
            </div>
        </div>

        <!-- Enable/disable tabs & warnings based on setup completion: -->
        <?php
        if ($authenticator->doesDataDirExist()) {
            echo "<script type='text/javascript'>";
            echo "usercomplete();";
            echo "$('#datadirstep').addClass('cursorpoint');";
            echo "$('#usersteplink').addClass('cursorpoint');";
            echo "$('#usernext').removeClass('disabled');";
            echo "$('#usernext').prop('disabled', false);";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "$('#usernext').prop('disabled', true);";
            echo "</script>";
        };

        if ($authenticator->doesUserExist()) {
            echo "<script type='text/javascript'>";
            echo "$('#setupstep').addClass('hidden');";
            echo "</script>";
        } else {
            echo "<script type='text/javascript'>";
            echo "setupwarning();";
            echo "</script>";
        }
        ?>

        <!-- Close persistant tooltips: -->
        <script>
            $(window).blur(function() {
                $('a').blur();
            });

            $('#registerbtn').on('click', function(e) {
                $(document).tooltip("enable");
            });

            $('#registerbtn').on('click', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });

            $('#datadirbtn').on('click', function(e) {
                $(document).tooltip("enable");
            });

            $('#datadirbtn').on('click', function(e) {
                setTimeout(function() {
                    $(document).tooltip("disable");
                }, 1000);
            });
        </script>

    </body>

</html>