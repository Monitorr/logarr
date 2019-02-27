<?php
include_once(__DIR__ . "/../functions.php");
include_once(__DIR__ . "/../auth_check.php");
?>
<!DOCTYPE html>
<html lang="en">

<!--
                LOGARR
    by @seanvree, @jonfinley, and @rob1998
        https://github.com/Monitorr
-->

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="manifest" href="webmanifest.json">

	<script src="assets/js/pace.js" async></script>

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

	<script src="assets/js/jquery.min.js"></script>

	<!-- // CHANGE ME: -->
	<?php
	//ini_set('error_reporting', E_ERROR);
	error_reporting(0);
	?>

	<title>
		<?php echo $GLOBALS['preferences']['sitetitle']; ?> - Register
	</title>
</head>

<body id="body" style="color: #FFFFFF;">


<!--  START create user form -->
<div id="userwrapper">

	<script>
        console.log("test");
        $(function () {
            console.log("test2");
            $("#userform").submit(function (e) {
                e.preventDefault();
                let form = $(this);
                $.ajax({
                    type: "POST",
                    url: "/?action=register&debug=1",
                    data: form.serialize(), // serializes the form's elements.
                    success: function (response) {
                        $("#feedback").html(response);
                    }
                });
            });
        });
	</script>
	<p id="feedback"></p>
	<?php

	//Create user:
	$dbfile3 = $GLOBALS['datadir'] . 'users.db';

	if (is_file($dbfile3)) {


		echo '<div id="loginmessage">';
		echo '<h5 class="heading">Create new user:</h5>';
		echo 'in the user database: ';
		echo $GLOBALS['datadir'];
		echo "users.db :";
		echo '<br>';
		echo '</div>';

		echo '<form id="userform" method="post" action="" name="registerform">';

		echo '<table id="registrationtable">';

		echo '<tbody id="registrationform">';

		echo '<tr id="usernameinput">';
		echo '<td><i class="fa fa-fw fa-user"> </i> <input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder=" Username" title="Enter a username" required autocomplete="off" spellcheck="false" /> </td>';
		echo '<td><label for="login_input_username"><i> Letters and numbers only, 2 to 64 characters </i></label></td>';
		echo '</tr>';

		echo '<tr id="useremail">';
		echo "<td><i class='fa fa-fw fa-envelope'> </i> <input id='login_input_email' type='email' name='user_email' placeholder=' User e-mail' spellcheck='false' /></td>";
		echo '<td><label for="login_input_email"> <i> Not required </i></label></td>';
		echo ' </tr>';

		echo '<tr id="userpassword">';
		echo "<td><i class='fa fa-fw fa-key'> </i> <input id='login_input_password_new' class='login_input' type='password' name='user_password_new' pattern='.{6,}' required autocomplete='off' placeholder=' Password' title='Enter a password' /></td>";
		?>

		<td><input id='login_input_password_repeat' class='login_input' type='password' name='user_password_repeat'
		           pattern='.{6,}' fv-not-empty=' This field cannot be empty'
		           fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}'
		           fv-valid-func='$( "#registerbtn" ).prop( "disabled", false )'
		           fv-invalid-func='$( "#registerbtn" ).prop( "disabled", true )' required autocomplete='off'
		           placeholder=' Repeat password' title='Repeat password'/><i> Minimum 6 characters </i></td>

		<?php

		echo '</tr>';

		echo ' </tbody>';

		echo '</table>';

		/*
		 * TODO: create something new and better for this
		 * echo '<div id="loginerror">';
		if (property_exists($this, 'feedbackerror') && $this->feedbackerror) {
			echo "<script>
				$(document).ready(function () {
					$('#modalContent').html('$this->feedbackerror');
					var modal = $('#responseModal');
					var span = $('.closemodal');
					modal.fadeIn('slow');
					span.click(function () {
						modal.fadeOut('slow');
					});
					$(body).click(function (event) {
						if (event.target != modal) {
							modal.fadeOut('slow');
						}
					});
				});
			</script>";
		}
		echo '</div>';*/
		echo '<input type="hidden"  name="register" value="Register"/>';
		echo '<input id="registerbtn" type="submit" class="btn btn-primary" name="register" value="Register" title="Register" />';
		echo '<br>';

		/*
		 * TODO: create something new and better for this
		 * echo '<div id="loginsuccess">';

		// $this->feedback = "This user does not exist.";

		if (property_exists($this, 'feedbacksuccess') && $this->feedbacksuccess) {
			echo "<script>
				$(document).ready(function () {
					$('#modalContent').html('$this->feedbacksuccess');
					var modal = $('#responseModal');
					var span = $('.closemodal');
					modal.fadeIn('slow');
					span.click(function () {
						modal.fadeOut('slow');
					});
					$(body).click(function (event) {
						if (event.target != modal) {
							modal.fadeOut('slow');
						}
					});
				});
			</script>";

			echo '<div id="myModal" class="modalreg">';

			echo '<div id="mymodal2" class="modal-content">';

			echo $this->feedbacksuccess;

			echo '</div>';
			echo '<span class="close closereg"  aria-hidden="true" title="close">&times;</span>';

			echo '</div>';
		};

		echo '</div>';*/
		echo '</form>';

		echo ' <div id="loginerror">';
		echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br> ';
		echo ' </div>';

		echo ' <div id="usernotes">';
		echo "<i> + It is NOT possible to change a user's credentials after creation. ";
		echo '<br>';
		echo ' + If credentials need to be changed or reset, rename the file in your data directory ';
		echo ' to "users.old". Once that file is renamed, browse to this page again to recreate desired credentials. </i> ';
		echo ' </div>';

	} else {
		echo "Something is wrong with your configuration.";
	}
	?>

</div>
<!--  END create user form -->

<div id="footer">

	<!-- Checks for Logarr application update on page load & "Check for update" click: -->
	<script src="assets/js/update.js" async></script>

	<div id="logarrid">
		<a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank"
		   class="footer">Logarr </a> |

		<a href="https://github.com/Monitorr/logarr/releases" title="Logarr releases" target="_blank" class="footer">
			Version: <?php echo file_get_contents(__DIR__ . "/../../js/version/version.txt"); ?>
		</a> |
		<a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a>
		<br>
	</div>

</div>

</body>

</html>
