<?php
require_once(__DIR__ . "/assets/php/auth_check.php");
require_once(__DIR__ . "/assets/php/functions/configuration.functions.php");

//TODO: If datadir is created and user is logged out / forward to settings.php

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
            appendLog($logentry = "Logarr is creating a new user");
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

<!-- configuration.php -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="manifest" href="webmanifest.json">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool." />
    <meta name="application-name" content="Logarr" />
    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/vendor/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/logarr.css">
    <link rel="stylesheet" href="assets/data/custom.css">

    <link rel="icon" type="image/png" href="favicon.png">

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <title>Logarr | Configuration</title>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/vendor/sweetalert2.min.js"></script>
    <script src="assets/js/vendor/formValidation.js"></script>
    <script src="assets/js/logarr.main.js"></script>

    <?php appendLog($logentry = "Logarr Configuration page loaded"); ?>

    <script>
        function toastwelcome() {
            Toast.fire({
                toast: true,
                type: 'success',
                title: 'Welcome to Logarr!',
                position: 'bottom-start',
                background: 'rgba(50, 1, 25, 0.75)',
                timer: 10000
            })
        };

        function usererror() {
            Toast.fire({
                toast: true,
                type: 'error',
                title: 'Error creating user!',
                background: 'rgba(207, 0, 0, 0.75)'
            })
        };

        function usersuccess() {
            Toast.fire({
                toast: true,
                type: 'success',
                title: 'User created successfully!',
                background: 'rgba(0, 184, 0, 0.75)'
            })
        };

        function datadirerror() {
            Toast.fire({
                toast: true,
                type: 'error',
                title: 'Error creating <br> data directory!',
                background: 'rgba(207, 0, 0, 0.75)'
            })
        };

        function datadirsuccess() {
            Toast.fire({
                toast: true,
                type: 'success',
                title: 'Data directory <br> created successfully!',
                background: 'rgba(0, 184, 0, 0.75)'
            })
        };
    </script>

    <?php
    if ($authenticator->isDatadirSetup()) {

        echo '<script>';
        echo '$(document).ready(function () {';
        echo 'datadir = true;';
        echo '});';
        echo '</script>';
    } else {
        echo '<script>';
        echo '$(document).ready(function() {';
        echo 'datadir = false;';
        echo '});';
        echo '</script>';
    }
    ?>

    <script>
        $(document).ready(function() {
            if (datadir == true) {

            } else {
                $('#registration-header').removeClass('hidden');
                $('#extensions').removeClass('hidden');
                $('#footer').removeClass('hidden');
                toastwelcome();
                console.log("Welcome to Logarr!");
            }
        });
    </script>

    <script>
        function switchTabs(newHash) {

            let targets = $(".stepper-target");

            if (newHash === "" || newHash === "#") newHash = "#datadir";
            let newTarget = $(newHash);

            //TODO: Change active step

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
                    url: "configuration.php",
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
                            switchTabs("#users");
                            datadirsuccess();
                        } else {
                            console.log("ERROR: Failed to create data directory");
                            $('#response').addClass('regerror');
                            $("#response").text("Failed to create data directory: " + data.response);
                            datadirerror();
                        }
                    },
                    error: function(data) {
                        console.log(data);
                        console.log("Posting failed (ajax)");
                        datadirerror();
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
                url: "configuration.php",
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
                        $("#config").load(location.href + " #config>*", "");
                        $('#responseuser').addClass('regsuccess');
                        $("#responseuser").text("User created successfully!");
                        switchTabs("#config");
                        usersuccess();
                        //TODO:  Force winodow reload after user create
                        configreload();

                    } else {
                        $('#responseuser').addClass('regerror');
                        $("#responseuser").text("Failed to create user: " + data.responseuser);
                        console.log("ERROR: Failed to create user");
                        usererror();
                    }
                },
                error: function(data) {
                    console.log(data);
                    console.log("Posting failed (ajax)");
                    usererror();
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

    <!-- Reload settings page after user creation: -->
    <script>
        function configreload() {
            console.log("Reloading Logarr in 10 seconds");
            setTimeout(function() {
                //TODO : forward to index or settings ??
                top.location = "settings.php";
            }, 5000);
        }
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
            | Configuration
        </div>
    </div>

    <div id="configuration-container" class="flex-child">
        <!-- Horizontal Steppers -->
        <div class="row">
            <div class="col-md-12">

                <!-- Steppers Wrapper -->
                <ul class="stepper stepper-horizontal">

                    <?php
                    $datadirStepClass = "";
                    $userStepClass = "";
                    $configStepClass = "";

                    if (!$authenticator->isDatadirSetup()) {
                        $datadirStepClass = "active";
                    } else if (!$authenticator->databaseExists()) {
                        $datadirStepClass = "completed";
                        $userStepClass = "active";
                    } else if (!$authenticator->isConfigComplete()) {
                        $datadirStepClass = "completed";
                        $userStepClass = "completed";
                        $configStepClass = "active";
                    } else {
                        $datadirStepClass = "completed";
                        $userStepClass = "completed";
                        $configStepClass = "success";
                    }
                    ?>

                    <!-- First Step -->
                    <li class="<?php echo $datadirStepClass; ?>" onclick="switchTabs('#datadir');">
                        <a href="#datadir">
                            <span class="circle">1</span>
                            <span class="label">Datadir</span>
                        </a>
                    </li>

                    <!-- Second Step -->
                    <li class="<?php echo $userStepClass; ?>" onclick="switchTabs('#users');">
                        <a href="#users">
                            <span class="circle">2</span>
                            <span class="label">User management</span>
                        </a>
                    </li>

                    <!-- Third Step -->
                    <li class="<?php echo $configStepClass; ?>" onclick="switchTabs('#config');">
                        <a href="#config">
                            <span class="circle"><i class="fas fa-exclamation"></i></span>
                            <span class="label">Config</span>
                        </a>
                    </li>

                </ul>
                <!-- /.Steppers Wrapper -->

            </div>
        </div>
        <!-- Horizontal Steppers -->

        <!--  START datadir create form -->
        <div id="datadir" class="stepper-target">

            <h2 class="heading">Create a data directory:</h2>

            <?php
            if ($authenticator->isDatadirSetup()) {
                echo '<div id="loginerror" class="warning">';
                echo '<i class="fa fa-fw fa-exclamation-triangle"> </i> WARNING: An existing data directory is detected at: ';
                echo $authenticator->datadir;
                echo ' <br> If a new data directory is created, the current data directory will NOT be altered, however, Logarr will use all default resources from the newly created data directory.';
                echo ' <br> All settings and user credentials will be reset to default.';
                echo ' <br> After creating a new data directory, you must create a user within 2 minutes.';
                echo '<br>';
                echo '</div>';
            } else { }
            ?>

            <form id="datadirform" method="post">

                <div>
                    <i class='fa fa-fw fa-folder-open'> </i> <input type='search' class="input" name='datadir' id="datadir-input" fv-not-empty=" This field cannot be empty" fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' spellcheck="false" autocomplete="off" placeholder=' Data dir path' required>
                    <br>
                    <i class="fa fa-fw fa-info-circle"> </i>
                    <i>
                        <?php echo "The current absolute path is: " . getcwd() ?> </i>
                </div>
                <br>
                <div id='response'></div>
                <div>
                    <input type="hidden" name="action" value="datadir">
                    <input type='submit' id="datadirbtn" class="btn btn-primary" title="Create data directory" value='Create' />
                </div>

            </form>

            <div id="loginerror" class="warning">
                <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
            </div>

            <div id="datadirnotes">
                <i>
                    + The directory that is chosen must NOT already exist, however CAN be a sub directory of an
                    exisiting
                    directory.
                    <br>
                    + Path value must include a trailing slash.
                    <br>
                    + For security purposes, this directory should NOT be within the webserver's filesystem hierarchy.
                    However, if a path is chosen outside the webserver's filesystem, the PHP process must have
                    read/write
                    privileges to whatever location is chosen to create the data directory.
                    <br>
                    + Value must be an absolute path on the server's filesystem with the exception of Docker - use a
                    relative path with trailing slash.
                    <br>
                    Good: c:\datadir\, /var/datadir/
                    <br>
                    Bad: wwwroot\datadir, ../datadir
                </i>
            </div>

            <br>
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
                    <h2 class="heading">Create a new user:</h2>
                    <i class="fa fa-fw fa-info-circle"> </i>
                    Current database:
                    <?php echo $authenticator->datadir . "users.db"; ?>
                    <br>
                </div>

                <form id="userform" method="post">

                    <table id="registrationtable">

                        <tbody id="registrationform">

                            <tr id="usernameinput">
                                <td><i class="fa fa-fw fa-user"> </i> <input id="login_input_username" type="search" class="input" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder=" Username" title="Enter a username" required spellcheck="false" autocomplete="off" /></td>
                                <td><label for="login_input_username"><i> Letters and numbers only, 2 to 64 characters </i></label></td>
                            </tr>

                            <tr id="useremail">
                                <td><i class='fa fa-fw fa-envelope'> </i> <input id='login_input_email' type='email' class="input" name='user_email' placeholder=' User e-mail' spellcheck="false"></td>
                                <td><label for="login_input_email"> <i> Not required </i></label></td>
                            </tr>

                            <tr id="userpassword">
                                <td><i class='fa fa-fw fa-key'> </i> <input id='login_input_password_new' class='login_input input' type='password' name='user_password_new' pattern='.{6,}' required autocomplete='off' placeholder=' Password' title='Enter a password' /></td>
                                <td><input id='login_input_password_repeat' class='login_input input' type='password' name='user_password_repeat' pattern='.{6,}' fv-not-empty='' fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-valid-func='$( "#registerbtn" ).prop( "disabled", false )' fv-invalid-func='$( "#registerbtn" ).prop( "disabled", true )' required autocomplete='off' placeholder=' Repeat password' title='Repeat password' /><i> Minimum 6 characters </i></td>
                            </tr>
                        </tbody>
                    </table>

                    <div id='responseuser'></div>
                    <br>
                    <input id="registerbtn" type="submit" class="btn btn-primary" name="register" value="Register" title="Create user">
                </form>

                <div id="loginerror" class="warning">
                    <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
                </div>
                <div id="usernotes">
                    + It is NOT possible to change a user's credentials after creation.<br>
                    + If credentials need to be changed or reset, rename the file in your data directory
                    to "users.old". Once that file is renamed, browse to this page again to recreate desired credentials.
                </div>
            </div>
            <?php

        }
        ?>

        </div>
        <!-- END user create form -->

        <!-- START config -->
        <div id="config" class="stepper-target">

            <?php
                //TODO create the final page
            if ($authenticator->isDatadirSetup()) {
                if ($authenticator->databaseExists()) {
                    if ($authenticator->doesUserExist()) {
                        if ($authenticator->isConfigComplete()) { } else {
                            copyDefaultConfig($authenticator->datadir);
                        }
                        ?>

            <!-- //TODO Change me: -->
            <div> Logarr configuration is complete! </div>
            <div> Logarr will now reload in 10 seconds </div>

            <p id='regsettingnote'>
                <i class='fas fa-exclamation-triangle'></i> For security purposes, ensure to change the Authentication Setting: 'Enable Configuration Access' to ('FALSE') after initial configuration.
            </p>
            
            <button class="btn btn-primary">
            <a href='index.php'>go to homepage</a>
            </button>
            
            <?php

        }
    }
}
?>
        </div>
        <!-- END config -->
    </div>

    <?php

    echo "<div id='extensions' class='hidden'>";

    echo "<div id='extensiontitle'> Required PHP Extensions: </div> ";

    //Check if '/assets/data/' dir is writable:
    $myfile = fopen('assets/data/php-perms-check.txt', 'w+');
    $date = date("D d M Y H:i T");


    if (!$myfile) {
        echo " <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP write permissions FAIL'>";
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
        echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP php_sqlite3 extension NOT loaded'>";
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
        echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP pdo_sqlite extension NOT loaded'>";
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
        echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='php7-zip extension NOT loaded'>";
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
        echo " | <a class='extfail' href='https://github.com/Monitorr/logarr/wiki/01-Config:--Initial-configuration' target='_blank' title='PHP openssl extension NOT loaded'>";
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

</body>

</html> 