// function to reorder
$(document).ready(function () {
    // check local version.txt file and alert if update available:
   
        var uid = $(this).attr("id");
        var info = "uid=" + uid + "&vcheck=1";
        const versionCheckAuto = $('#version_check_auto');
        $.ajax({
            beforeSend: function () {
                versionCheckAuto.html('<img src="assets/images/loader.gif" width="16" height="16" />');
                console.log('Logarr is checking for an application update (Auto).');
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
                    console.log('Logarr version ' + data.version.trim() + ' is available. Click "check for update" on the Info page to update Logarr.');

                    updateavailtoast();

                    versionCheckAuto.html(
                        '<div class="footer a" style="cursor: pointer"> <a class="updatelink" href="https://github.com/Monitorr/logarr/releases" target = "_blank" title="Logarr Releases"> <b> A Logarr update is available </b></a> </div>'
                    );

                    setTimeout(15000);

                } else {
                    // latest version already installed:
                    versionCheckAuto.html("");

                    console.log('Logarr update: You have the latest Logarr version (Auto).');
                }
            },
            error: function () {
                // error
                console.log('ERROR: An error occurred while checking your Logarr version.');
                versionCheckAuto.html('<p id="vcheckerror" class="vcheckerror">An error occurred while checking your Logarr version!</p>');
                updatecheckerror();
                setTimeout(10000);
            }
        });



        // TO DO CHANGE ME:  We don't need this anymore since user doesn't udpate from index
        /*     
            let versionCheck = $('#version_check');
            versionCheck.on('click', function (e) {
            //$('#loading').show();
            var uid = $(this).attr("id");
            var info = "uid=" + uid + "&vcheck=1";
            $.ajax({
                beforeSend: function () {
                    versionCheck.html('<img src="assets/images/loader.gif" width="16" height="16" />');
                    console.log('Logarr is checking for an application update.');
                },
                type: "POST",
                url: "assets/php/version_check.php",
                data: info,
                dataType: "json",
                success: function (data) {
                    // clear loading information
                    versionCheck.html("");
                    // check for version verification
                    if (data.version != 0) {
                        var uInfo = "uid=" + uid + "&version=" + data.version;
                        $.ajax({
                            beforeSend: function () {
                                versionCheck.html('<img src="assets/images/loader.gif" width="16" height="16" />');
                            },
                            type: "POST",
                            url: "assets/php/update-functions.php",
                            data: uInfo,
                            dataType: "json",
                            success: function (data) {
                                // check for version verification
                                if (data.copy != 0) {
                                    if (data.unzip == 1) {
                                        // clear loading information
                                        versionCheck.html("");
                                        // successful update
                                        console.log('Logarr update successful! Reloading Logarr in 5 seconds...');
                                        versionCheck.html("<strong>Update Successful!</strong>");
                                        updatesuccess();
                                        setTimeout(location.reload.bind(location), 5000);
                                    } else {
                                        // error during update/unzip
                                        console.log('Logarr update: An error occurred while extracting the update files.');
                                        versionCheck.html("<strong>An error occurred while extracting files.</strong>");
                                        updateextracterror();
                                        setTimeout(5000);
                                    }

                                } else {
                                    console.log('Logarr update: An error occurred while copying the update files.');
                                    versionCheck.html("<strong>An error occurred while copying the files.</strong>");
                                    $.growlUI('An error occurred while copying the files.');
                                    updatecopyerror();
                                    setTimeout(5000);

                                }
                            },
                            error: function () {
                                // error
                                console.log('Logarr update: An error occurred while updating your files.');
                                versionCheck.html('<strong>An error occurred while updating your files.</strong>');
                                $.growlUI('An error occurred while updating your files.');
                                setTimeout(5000);
                            }
                        });
                    } else {
                        // user has the latest version already installed
                        console.log('Logarr update: You have the latest version. Reloading Logarr in 5 seconds...');
                        versionCheck.html("");
                        versionCheck.html("<strong>You have the latest version. Reloading Logarr in 5 seconds...</strong>");
                        updatechecklatest();
                        setTimeout(location.reload.bind(location), 5000);
                    }
                },
                error: function () {
                    // error
                    console.log('Logarr update: An error occurred while checking your Logarr version.');
                    versionCheck.html('<p id="vcheckerror" class="vcheckerror"> An error occurred while checking your Logarr version.</p>');
                    $.growlUI('An error occurred while checking your Logarr version.');
                    updatecheckerror();
                    setTimeout(5000);
                }
            });
        }); 
    */


    // function to reorder

});
