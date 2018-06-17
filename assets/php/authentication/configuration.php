<?php
$datadir_file = __DIR__ . '/../../data/datadir.json';
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<!--
                LOGARR
    by @seanvree, @wjbeckett, and @jonfinley
        https://github.com/Monitorr
-->

<head>

    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="manifest" href="webmanifest.json">

    <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool."/>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/logarr.css"/>
    <link rel="stylesheet" href="assets/css/custom.css"/>

    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/images/favicon/apple-touch-icon-57x57.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon/apple-touch-icon-114x114.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon/apple-touch-icon-72x72.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon/apple-touch-icon-144x144.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="assets/images/favicon/apple-touch-icon-60x60.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="assets/images/favicon/apple-touch-icon-120x120.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="assets/images/favicon/apple-touch-icon-76x76.png"/>
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="assets/images/favicon/apple-touch-icon-152x152.png"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-196x196.png" sizes="196x196"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-96x96.png" sizes="96x96"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-32x32.png" sizes="32x32"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-16x16.png" sizes="16x16"/>
    <link rel="icon" type="image/png" href="assets/images/favicon/favicon-128.png" sizes="128x128"/>
    <meta name="application-name" content="Logarr"/>
    <meta name="msapplication-TileColor" content="#FFFFFF"/>
    <meta name="msapplication-TileImage" content="assets/images/favicon/mstile-144x144.png"/>
    <meta name="msapplication-square70x70logo" content="assets/images/favicon/mstile-70x70.png"/>
    <meta name="msapplication-square150x150logo" content="assets/images/favicon/mstile-150x150.png"/>
    <meta name="msapplication-wide310x150logo" content="assets/images/favicon/mstile-310x150.png"/>
    <meta name="msapplication-square310x310logo" content="assets/images/favicon/mstile-310x310.png"/>
    <meta name="theme-color" content="#252525"/>
    <meta name="theme_color" content="#252525"/>

    <meta name="robots" content="NOINDEX, NOFOLLOW">

    <!-- // CHANGE ME - Change to new config file  -->

    <title>Logarr | Configuration</title>

    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/js/pace.js" async></script>

    <script src="assets/js/jquery.blockUI.js"></script>

    <script src="assets/js/jquery.highlight.js" async></script>

    <script src="assets/js/logarr.main.js"></script>
    <script src="assets/js/jquery.mark.min.js" async></script>


</head>

<body id="body" style="color: #FFFFFF;">
<div class="header">

    <div id="left" class="Column"></div>

    <div id="logo" class="Column">
        <img src="assets/images/log-icon.png" alt="Logarr" style="height:8em;border:0;" title="Reload Logarr"
             onclick="window.location.reload(true);">
    </div>

    <div id="right" class="Column"></div>

</div>

<!-- datadir create button: -->
<script>

    $(document).ready(function () {

        $('#datadirbtn').click(function () {

            $('#response').html("<font color='yellow'><b>Creating data directory...</b></font>");

            var datadir = $("#datadir").val();
            console.log('submitted: ' + datadir);
            var url = "assets/php/authentication/mkdirajax.php";

            $.post(url, {datadir: datadir}, function (data) {
                console.log('mkdirajax: ' + data);
                $('#response').html(data);
                console.log(document.URL);
                $('#userwrapper').load(document.URL + '?action=config #userwrapper');
            })

                .fail(function () {
                    alert("Posting failed (ajax)");
                    console.log("Posting failed (ajax)");
                })

            return false;
        });
    });

</script>

<div id='responseModal'>

    <span class="closemodal" aria-hidden="true" title="Close">&times;</span>

    <div id='modalContent'></div>

</div>

<div id="configuration-container" class="flex-child">


    <div class="center">
        <h1 class="navbar-brand">Logarr | Configuration</h1>
    </div>

    <div id="loginmessage">
        <i class="fa fa-fw fa-info-circle"> </i> This registration process will perform the following actions: <br><br>
    </div>

    <div id="reginstructions">

        1- Establish a data directory which will contain three json files with your custom settings, and a user database
        file. <br>
        2- Create a data directory definition file in the Logarr installation directory which defines where your data
        directory is established on the webserver. <br>
        3- Copy the three default json settings files from the local Logarr repository to the established data
        directory. <br>
        4- Create a sqlite user database file in the established data directory. <br>
        5- Create a user. <br>
        + The above actions must complete successfully and in succession in order for Logarr to function properly.
        <br>
        + If you have any problems during the registration process, please check the
        <strong>
            <a href="https://github.com/Monitorr/logarr/wiki" target="_blank" class="toolslink" title="Logarr Wiki">
                Logarr Wiki
            </a>
        </strong>. <br>

    </div>

    <!--  START datadir create form -->

    <div id="dbwrapper">

        <div id='dbdir' class='loginmessage'>
            Create data directory, copy default data json files, and create user database file in defined directory:
        </div>

		<?php
        if(is_file($datadir_file)) {
	        echo '<div id="loginerror">';
	        echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> WARNING: An existing data directory is detected at: ';
	        echo json_decode(file_get_contents($datadir_file), 1)['datadir'];
	        echo ' <br> If an additional data directory is created, the current data directory will NOT be altered, however, Logarr will use all default resources from the newly created data directory. </b> <br>';

	        echo '</div>';
        }
		?>

        <form id="datadirform">

            <div>
                <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='datadir' id="datadir"
                                                                fv-not-empty=" This field can't be empty"
                                                                fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}'
                                                                fv-valid-func="$( '#datadirbtn' ).prop( 'disabled', false )"
                                                                fv-invalid-func="$( '#datadirbtn' ).prop( 'disabled', true )"
                                                                autocomplete="off" placeholder=' Data dir path'
                                                                required>
                <br>
                <i class="fa fa-fw fa-info-circle"> </i>
                <i><?php echo "The current absolute path is: " . getcwd() ?> </i>
            </div>
            <br>
            <div>
                <input type='input' id="datadirbtn" class="btn btn-primary" title="Create data directory"
                       value='Create'/>
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
    <div id="userwrapper">
	<?php

	if (is_file($datadir_file)) {
        $dbfile = json_decode(file_get_contents($datadir_file),1)['datadir'] . 'users.db';

        if (is_file($dbfile)) {

        ?>
        <!--  START multi form -->

        <div id="multiform">
            <div id='response' class='dbmessage'></div>

            <div id="loginmessage">
                <h2 class="heading">Create a new user:</h2>
                in the user database:
                <?php echo $dbfile; ?>
                <br>

            </div>

            <form id="userform" method="post" action="?action=config" name="registerform">

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

                        echo '<div id="loginerror">';
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
                        };

                        echo '</div>';

                        echo '<input id="registerbtn" type="submit" class="btn btn-primary" name="register" value="Register" />';
                        echo '<br>';

                        echo '<div id="loginsuccess">';

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

                        echo '</div>';

                        echo '</form>';

                        echo ' <div id="loginerror">';
                        echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br> ';
                        echo ' </div>';

                        echo ' <div id="usernotes">';
                        echo "<i> + It is NOT possible to change a user's credentials after creation. ";
                        echo '<br>';
                        echo ' + If credentials need to be changed or reset, rename the file in your data directory ';
                        echo  $dbfile;
                        echo ' to "users.old". Once that file is renamed, browse to this page again to recreate desired credentials. </i> ';
                        echo ' </div>';


                        }

	}

	?>
    </tr>
    <div id='response' class='dbmessage'></div>
    </div>

</body>

</html>
