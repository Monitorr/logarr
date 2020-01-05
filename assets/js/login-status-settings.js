console.log('Logarr settings auth: ENABLED | Checking login status every 10s');

function checkLoginSettings() {

    $.ajax({
        type: "GET",
        url: "assets/php/login-status.php",
        success: function (data) {

            if (data === "true") {
                // User is logged IN:

                console.log('Logarr user is logged into Settings');

            } else {
                // User is logged OUT:

                console.log('Logarr user is logged OUT');

                logouttoast();

                // If user user logs out, refresh settings page to envoke authentication page after 3s:
                setTimeout(function () {
                    
                    window.location.href = "settings.php";

                }, 3000);
            }
        },

        error: function () {
            // error
            console.log("%cERROR: An error occurred checking login status", "color: red;");

            // If logged-in user creates NEW data dir, must create new user within 2 minutes:
            setTimeout(function () {

                console.log("%cERROR: An error occurred checking login status. You will be auto-logged out in less than 2 minutes", "color: red;");

                logoutwarning();

            }, 120000);

            setTimeout(function () {

                console.log("%cERROR: An error occurred while checking login status. You are logged out", "color: red;");

                logouttoast();

            }, 235000);
            
            setTimeout(function () {

                window.location.href = 'settings.php';

            }, 240000);
        }
    });

    //check login status every 10s:
    setTimeout(checkLoginSettings, 10000);
}

checkLoginSettings();