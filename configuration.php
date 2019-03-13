<?php
require_once(__DIR__ . "/assets/php/auth_check.php");
require_once(__DIR__ . "/assets/php/functions/configuration.functions.php");


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
			$result["status"] = $authenticator->doRegistration() ? "success" : "failed";
			$result["response"] = $authenticator->feedback;
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
    by @seanvree, @wjbeckett, and @jonfinley
        https://github.com/Monitorr
-->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="manifest" href="webmanifest.json">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool."/>
    <meta name="application-name" content="Logarr"/>


    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/logarr.css">
    <link rel="stylesheet" href="assets/data/custom.css">

    <link rel="icon" type="image/png" href="favicon.png">

    <meta name="theme-color" content="#464646"/>
    <meta name="theme_color" content="#464646"/>

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <title>Logarr | Configuration</title>

    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/js/jquery.blockUI.js"></script>

    <script src="assets/js/jquery.highlight.js" async></script>

    <script src="assets/js/vendor/sweetalert2.min.js"></script>

    <script src="assets/js/logarr.main.js"></script>

    <script src="assets/js/jquery.mark.min.js" async></script>


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

        $(function () {
            switchTabs(window.location.hash);
        });
    </script>

    <!-- datadir form submit: -->
    <script>
        $(document).ready(function () {

            $('#datadirform').submit(function (e) {
                e.preventDefault();
                let datadir = $("#datadir-input").val();

                $.ajax({
                    type: "POST",
                    url: "configuration.php",
                    data: {datadir: datadir, action: "datadir"},
                    dataType: 'json',
                    success: function (data) {
                        if (data.status === "success") {
                            console.log("success, datadir set to: " + data.response);
                            //TODO notification
                            $("#users").load(location.href + " #users>*", "");
                            switchTabs("#users");
                        } else {
                            //TODO notification
                        }
                    },
                    error: function (data) {
                        //TODO notification
                        console.log(data);
                        console.log("Posting failed (ajax)");
                    }
                });
            });
        });
    </script>

    <!-- user form submit: -->
    <script>
        $(document).on("submit", "#userform", function (e) {
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
                success: function (data) {
                    console.log(data);
                    if (data.status === "success") {
                        console.log("success, datadir set to: " + data.response);
                        //TODO notification
                        $("#config").load(location.href + " #config>*", "");
                        switchTabs("#config");
                    } else {
                        //TODO notification
                    }
                },
                error: function (data) {
                    //TODO notification
                    console.log(data);
                    console.log("Posting failed (ajax)");
                }
            });
        });
    </script>
</head>

<body id="body" style="color: #FFFFFF;">

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

        <div id='dbdir' class='loginmessage'>
            Create data directory, copy default data json files, and create user database file in defined directory:
        </div>

		<?php
		if ($authenticator->isDatadirSetup()) {
			echo '<div id="loginerror">';
			echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> WARNING: An existing data directory is detected at: ';
			echo $authenticator->datadir;
			echo ' <br> If an additional data directory is created, the current data directory will NOT be altered, however, Logarr will use all default resources from the newly created data directory. </b> <br>';

			echo '</div>';
		}
		?>

        <form id="datadirform" method="post">

            <div>
                <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='datadir' id="datadir-input" spellcheck="false" autocomplete="off" placeholder=' Data dir path' required>
                <br>
                <i class="fa fa-fw fa-info-circle"> </i>
                <i>
					<?php echo "The current absolute path is: " . getcwd() ?> </i>
            </div>
            <br>
            <div>
                <input type="hidden" name="action" value="datadir">
                <input type='submit' id="datadirbtn" class="btn btn-primary" title="Create data directory" value='Create'/>
            </div>

        </form>

        <div id="loginerror">
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
                <div id='response' class='dbmessage'></div>
                <div id="loginmessage">
                    <h2 class="heading">Create a new user:</h2>
                    in the user database:
					<?php echo $authenticator->datadir . "users.db"; ?>
                    <br>

                </div>

                <form id="userform" method="post">

                    <table id="registrationtable">


                        <tbody id="registrationform">


                        <tr id="usernameinput">
                            <td><i class="fa fa-fw fa-user"> </i> <input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder=" Username" title="Enter a username" required autocomplete="off"/></td>
                            <td><label for="login_input_username"><i> Letters and numbers only, 2 to 64 characters </i></label></td>
                        </tr>


                        <tr id="useremail">
                            <td><i class='fa fa-fw fa-envelope'> </i> <input id='login_input_email' type='email' name='user_email' placeholder=' User e-mail'/></td>
                            <td><label for="login_input_email"> <i> Not required </i></label></td>
                        </tr>

                        <tr id="userpassword">
                            <td><i class='fa fa-fw fa-key'> </i> <input id='login_input_password_new' class='login_input' type='password' name='user_password_new' pattern='.{6,}' required autocomplete='off' placeholder=' Password' title='Enter a password'/></td>
                            <td><input id='login_input_password_repeat' class='login_input' type='password' name='user_password_repeat' pattern='.{6,}' fv-not-empty=' This field cannot be empty' fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}' fv-valid-func='$( "#registerbtn" ).prop( "disabled", false )' fv-invalid-func='$( "#registerbtn" ).prop( "disabled", true )' required autocomplete='off' placeholder=' Repeat password' title='Repeat password'/><i> Minimum 6 characters </i></td>
                        </tr>
                        </tbody>
                    </table>
                    <input id="registerbtn" type="submit" class="btn btn-primary" name="register" value="Register"/>
                </form>
                <div id="loginerror">
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
					if ($authenticator->isConfigComplete()) {
					} else {
						copyDefaultConfig($authenticator->datadir);
					}
					echo "done <a href='index.php'>go to homepage</a>";
				}
			}
		}
		?>
    </div>
    <!-- END config -->
</div>
</body>
</html>