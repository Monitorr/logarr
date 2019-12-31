$(document).ready(function () {
    // check local version.txt file and alert if update available:
   
    var uid = $(this).attr("id");
    var info = "uid=" + uid + "&vcheck=1";
    const versionCheckAuto = $('#version_check_auto');
    $.ajax({
        beforeSend: function () {
            versionCheckAuto.html('<img src="assets/images/loader.gif" width="16" height="16" />');
            console.log('Logarr is checking for an application update (Auto)');
        },
        type: "POST",
        url: "assets/php/version_check.php",
        data: info,
        dataType: "json",
        success: function (data) {

            // clear loading information
            versionCheckAuto.html("");

            // check for version verification:
            if (data.version != 0) {
                var uInfo = "uid=" + uid + "&version=" + data.version;
                console.log('Logarr version ' + data.version.trim() + ' is available. Click "check for update" on the Info page to update Logarr');

                updateavailtoast();

                versionCheckAuto.html(
                    '<div class="footer a" style="cursor: pointer"> <a class="updatelink" href="https://github.com/Monitorr/logarr/releases" target = "_blank" title="Logarr Releases"> <b> A Logarr update is available </b></a> </div>'
                );

                setTimeout(15000);

            } else {
                // latest version already installed:
                versionCheckAuto.html("");
                console.log('Logarr update: You have the latest Logarr version (Auto)');
            }
        },
        error: function () {
            // error
            console.log('ERROR: An error occurred while checking your Logarr version');
            versionCheckAuto.html('<p id="vcheckerror" class="vcheckerror">An error occurred while checking your Logarr version!</p>');
            updatecheckerror();
            setTimeout(10000);
        }
    });
});
