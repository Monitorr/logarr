<?php include(__DIR__ . "/header.php"); ?>
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
                    $('#userwrapper').load(document.URL + ' #userwrapper');
                })

                    .fail(function () {
                        alert("Posting failed (ajax)");
                        console.log("Posting failed (ajax)");
                    })

                return false;
            });
        });

    </script>

    <!-- db create button: -->
    <script>

        $(document).ready(function () {

            $('#dbbtn').click(function () {

                $('#response').html("<font color='yellow'><b>Creating user database...</b></font>");

                var dbfile = $("#dbfile").val();
                console.log('submitted: ' + dbfile);
                var url = "./mkdbajax.php";

                $.post(url, {dbfile: dbfile}, function (data) {
                    // alert("Directory Created successfully");
                    console.log('mkdbajax: ' + data);
                    $('#response').html(data);
                    $('#userwrapper').load(document.URL + ' #userwrapper');

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

    <div id="registration-container" class="flex-child">


        <div class="center">
            <h1 class="navbar-brand">Logarr | Registration</h1>
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

		<?php
		$config_file = $GLOBALS['datadir'] . 'config.json';

		if (!is_file($config_file)) {

			?>
            <!--  START datadir create form -->

            <div id="dbwrapper">

                <div id='dbdir' class='loginmessage'>
                    Create data directory, copy default data json files, and create user database file in defined directory:
                </div>

				<?php
				echo '<div id="loginerror">';
				echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> WARNING: An existing data directory is detected at: ';
				echo $GLOBALS['datadir'];
				echo ' <br> If an additional data directory is created, the current data directory will NOT be altered, however, Logarr will use all default resources from the newly created data directory. </b> <br>';

				echo '</div>';
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

                <div id='response' class='dbmessage'></div>

            </div>

            <!--  END datadir create form -->

			<?php

		} else {

			$dbfile = $GLOBALS['datadir'] . 'users.db';

			if (!is_file($dbfile)) {
				?>
                <!--  START  db create form -->


                <div id="dbcreatewrapper">

                    <hr>
                    <br>

					<?php

					$datafile = __DIR__ . '/../../data/datadir.json';

					if (is_file($datafile)) {
						echo '<div id="loginmessage">';
						echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> An existing data directory is detected at: ';
						echo $GLOBALS['datadir'];
						echo ' <br> By clicking "create" below, a user database will be created in the data directory specified below while leaving the JSON setting files in tact. <br> After the user database is created you will be able to create a user, log in, and edit the Logarr settings. <br>';
						echo '<br>';
						echo '</div>';
					} else {

					}
					?>

                    <form id="dbform">

                        <div>
                            <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='dbfile' id="dbfile"
                                                                            fv-not-empty=" This field can't be empty"
                                                                            fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}'
                                                                            autocomplete="off"
                                                                            value=" <?php echo $GLOBALS['datadir']; ?>"
                                                                            required readonly>
                        </div>
                        <br>
                        <div>
                            <input type='submit' id="dbbtn" class="btn btn-primary" title="Create user database"
                                   value='Create'/>
                        </div>

                    </form>

                    <br>

                    <div id='response' class='dbmessage'></div>

                </div>

                <!--  END  db create form -->

				<?php
			} else {

				?>
                <!--  START multi form -->

                <div id="multiform">

                    <div id="multiwarning">

						<?php

						$datafile = __DIR__ . '/../../data/datadir.json';

						if (is_file($datafile)) {
							echo '<br><i class="fa fa-fw fa-exclamation-triangle"> </i><b> WARNING: An existing data directory is detected at: ';
							echo $GLOBALS['datadir'];
							echo ' <br> If an additional data directory is created, the current directory will NOT be altered, however, Logarr will use all resources from the newly created data directory. </b>';
						}
						?>

                    </div>

                    <hr>

                    <table id='regmulti'>

                        <tr>
                            <th>
                                <div id='datadirheading' class='multiheading'>
                                    Create new data directory:
                                </div>
                            </th>

                            <th>
                                <div id='dbheading' class='multiheading'>
                                    Create new user database:
                                </div>
                            </th>
                        </tr>

                        <tr>
                            <td>

                                <div id="datadirmulti">

                                    <form id="datadirform">

                                        <div>
                                            <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='datadir'
                                                                                            id="datadir"
                                                                                            fv-not-empty=" This field can't be empty"
                                                                                            fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}'
                                                                                            fv-valid-func="$( '#datadirbtn' ).prop( 'disabled', false )"
                                                                                            fv-invalid-func="$( '#datadirbtn' ).prop( 'disabled', true )"
                                                                                            autocomplete="off"
                                                                                            placeholder=' Data dir path'
                                                                                            required>
                                            <br>
                                            <i class="fa fa-fw fa-info-circle"> </i>
                                            <i><?php echo "The current absolute path is: " . getcwd() ?> </i>
                                        </div>
                                        <br>
                                        <div>
                                            <input type='submit' id="datadirbtn" class="btn btn-primary"
                                                   title="Create data directory" value='Create'/>
                                        </div>

                                    </form>

                                    <div id="loginerror">
                                        <i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>
                                    </div>

                                    <div id="datadirnotes">
                                        <i>
                                            + The directory that is chosen must NOT already exist, however CAN be a sub
                                            directory of an exisiting directory.
                                            <br>
                                            + Path value must include a trailing slash.
                                            <br>
                                            + For security purposes, this directory should NOT be within the webserver's
                                            filesystem hierarchy. <br> However, if a path is chosen outside the webserver's
                                            filesystem, the PHP process must have read/write privileges to whatever location
                                            is
                                            chosen to create the data directory.
                                            <br>
                                            + Value must be an absolute path on the server's filesystem with the exception
                                            of
                                            Docker - use a relative path with trailing slash.
                                            <br>
                                            Good: c:\datadir\, /var/datadir/
                                            <br>
                                            Bad: wwwroot\datadir, ../datadir
                                        </i>
                                    </div>

                                </div>

                            </td>

                            <td>

                                <div id="dbcreatewrappermulti">

                                    <form id="dbform">

                                        <div>
                                            <i class='fa fa-fw fa-folder-open'> </i> <input type='text' name='dbfilemulti'
                                                                                            id="dbfile"
                                                                                            fv-not-empty=" This field can't be empty"
                                                                                            fv-advanced='{"regex": "\\s", "regex_reverse": true, "message": "  Value cannot contain spaces"}'
                                                                                            fv-valid-func="$( '#dbbtn' ).prop( 'disabled', false )"
                                                                                            fv-invalid-func="$( '#dbbtn' ).prop( 'disabled', true )"
                                                                                            autocomplete="off"
                                                                                            value=" <?php echo $GLOBALS['datadir']; ?>"
                                                                                            required>
                                            <br>
                                            <i class="fa fa-fw fa-info-circle"> </i>
                                            <i><?php echo "The current data directory path is: " . $GLOBALS['datadir']; ?> </i>
                                            <br>
                                        </div>
                                        <br>
                                        <div>
                                            <input type='submit' id="dbbtn" class="btn btn-primary"
                                                   title="Create user database"
                                                   value='Create'/>
                                        </div>

                                    </form>

									<?php

									if (is_file($datafile)) {

										echo '<div id="loginerror">';
										echo '<i class="fa fa-fw fa-exclamation-triangle"> </i><b> NOTE: </b> <br>';
										echo "</div>";

										echo '<div id="datadirnotes"> ';
										echo "<i>";
										echo "+ An existing data directory is detected at: " . $GLOBALS['datadir'];
										echo "<br>";
										echo '+ By clicking "create" above, a user database will be created in the data directory specified. ';
										echo "<br>";
										echo '+ The location of the new user database MUST be located in the data directory indicated above. ';
										echo "<br>";
										echo '+ If there is an existing user database in this directory, it will be renamed to "users.db.old" and a new user database will be created.';
										echo "<br>";
										echo '+ All setting JSON files in the current data directory will be left in tact.';
										echo "<br>";
										echo '+ After the new user database is created, you will be prompted to create new user credentials.';
										echo "<br>";
										echo "</div> ";
									} else {

									}
									?>

                                    <br>
                                </div>
                            </td>
                        </tr>

                    </table>

                    <div id='response' class='dbmessage'></div>

                </div>

                <!--  END multi form-->

				<?php

			}
		}

		?>

        <!--  START create user form -->

        <div id="userwrapper">

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
				echo '<td><i class="fa fa-fw fa-user"> </i> <input id="login_input_username" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder=" Username" title="Enter a username" required autocomplete="off" /> </td>';
				echo '<td><label for="login_input_username"><i> Letters and numbers only, 2 to 64 characters </i></label></td>';
				echo '</tr>';

				echo '<tr id="useremail">';
				echo "<td><i class='fa fa-fw fa-envelope'> </i> <input id='login_input_email' type='email' name='user_email' placeholder=' User e-mail' /></td>";
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
				echo $this->db_sqlite_path;
				echo ' to "users.old". Once that file is renamed, browse to this page again to recreate desired credentials. </i> ';
				echo ' </div>';

			} else {
			}

			?>

        </div>
        <!--  END create user form -->

    </div>

<?php include(__DIR__ . "/footer.php"); ?>