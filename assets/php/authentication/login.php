<?php include(__DIR__ . "/header.php");
?>

<style type="text/css">

    #login_input_username::-webkit-search-cancel-button {
        position: relative;
        cursor: pointer;
    }

    /* // CHANGE ME: */

    .validity {
        margin: 0 !important;
        top: .5em;
        position: relative;
    }

    input:invalid~span:after {
        content: '✖';
        padding-left: 5px;
        position: absolute;
        color: red;
    }

    input:valid~span:after {
        content: '✓';
        padding-left: 5px;
        position: absolute;
        color: green;
    }
</style>


<div id='login-container' class='flex-child'>

    <?php
    if (isset($this->feedback) && !empty($this->feedback)) {
        echo "<div class='login-warning'><p>" . $this->feedback . "</p></div>";
    }
    ?>

    <form method="post" id="login-form" action="" name="loginform">
        <div>
            <label for="login_input_username"><i class="fa fa-fw fa-user"></i></label>
            <input id="login_input_username" class="input" type="search" placeholder="Username" name="user_name" autofocus required autocomplete="off" spellcheck="false" />
            <!-- <span class="validity"></span> -->
        </div>

        <div>
            <label for="login_input_password"><i class="fa fa-fw fa-key"></i></label>
            <input id="login_input_password" class="input" type="password" placeholder="Password" name="user_password" required autocomplete="off" />
            <!-- <span class="validity"></span> -->
        </div>

        <div id="loginbtn">
            <button type="submit" class="btn btn-primary" name="login" title="Log In">Log in</button>
        </div>
    </form>
</div>

<?php include(__DIR__ . "/footer.php"); ?> 