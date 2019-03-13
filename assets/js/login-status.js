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
            console.log('ERROR: An error occurred while checking login status.');

            logouttoast();

            setTimeout(function () {
                
                window.location.href = 'assets/php/authentication/unauthorized.php';

            }, 3000);
        }
    });

    //check login status every 10s:
    setTimeout(checkLogin, 10000);
}

checkLogin();