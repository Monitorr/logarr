<?php include(__DIR__ . "/header.php");
?>

    <div id='login-container' class='flex-child'>
		<?php
		if (isset($this->feedback) && !empty($this->feedback)) {
			echo "<div class='login-warning'><p>" . $this->feedback . "</p></div>";
		}
		?>
        <form method="post" id="login-form" action="" name="loginform">
            <div>
                <label for="login_input_username"><i class="fa fa-fw fa-user"></i></label>
                <input id="login_input_username" class="input" type="text" placeholder="Username" name="user_name" autofocus required/>
            </div>
            <div>
                <label for="login_input_password"><i class="fa fa-fw fa-key"></i></label>
                <input id="login_input_password" class="input" type="password" placeholder="Password" name="user_password" required/>
            </div>

            <div id='loginerror'></div>

            <div id="loginbtn">
                <button type="submit" class="btn btn-primary" name="login">Log in</button>
            </div>
        </form>
    </div>

<?php include(__DIR__ . "/footer.php"); ?>