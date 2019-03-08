function checkLogin() {

    //console.log('Logarr settings auth: ENABLED | Checking login status');

    $.ajax({
        type: "GET",
        url: "assets/php/login-status.php",
        // data: info,
        success: function (data) {

            if (data === "true") {
                // User is logged IN:

                console.log('Loggar user is logged into Settings');

            } else {
                // User is logged OUT:

                console.log('Loggar user is logged OUT');

                logouttoast();

                // If user user logs out, refresh settings page to envoke authentication page after 3s:
                setTimeout(function () {

                    // TODO what works for both index and settings ??
                    
                    window.location.href = "settings.php"; // THIS WORKS

                    //window.top.location; // DOES NOT WORK
                    
                    //window.top.location.reload(); Does not work

                    //top.location.reload();  //Doesn't work on settings page if in iframe

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