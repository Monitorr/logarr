console.log('Logarr auth: ENABLED | Checking login status every 10s');

function checkLogin() {

    $.ajax({
        type: "GET",
        url: "assets/php/login-status.php",
        success: function (data) {

            if (data === "true") {
                // User is logged IN:

                console.log('Logarr user is logged IN');

            } else {
                // User is logged OUT:

                console.log('Logarr user is logged OUT');

                logouttoast();

                // If user user logs out, refresh index page to envoke authentication page after 3s:
                setTimeout(function () {
                    
                    window.location.href = "index.php";

                }, 3000);
            }
        },

        error: function () {
            // error
            console.log("%cERROR: An error occurred checking login status. You will be auto-logged out in less than 2 minutes", "color: red;");

            logoutwarning();

            setTimeout(function () {

                console.log("%cERROR: An error occurred while checking login status. You are logged out", "color: red;");

                logouttoast();

            }, 115000);

            setTimeout(function () {

                window.location.href = 'assets/php/authentication/unauthorized.php';

            }, 120000);
        }
    });

    //check login status every 10s:
    setTimeout(checkLogin, 10000);
}

checkLogin();