function checkLogin() {

    //console.log('Logarr auth: ENABLED | Checking login status');

    $.ajax({
        type: "GET",
        url: "assets/php/login-status.php",
        // data: info,
        success: function (data) {

            if (data === "true") {
                // User is logged IN:

                console.log('Loggar user is logged IN');

            } else {
                // User is logged OUT:

                console.log('Loggar user is logged OUT');
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
        }
    });

    //check login status every 10s:
    setTimeout(checkLogin, 10000);
}

checkLogin();