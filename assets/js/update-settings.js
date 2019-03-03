// function to reorder

// TODO:  Add sweet alert to all values:

$(document).ready(function () {
    var uid = $(this).attr("id");
    var info = "uid=" + uid + "&vcheck=1";
    const versionCheckAuto = $('#version_check_auto');
    $.ajax({
        beforeSend: function () {
            versionCheckAuto.html('<img src="../../images/loader.gif" width="16" height="16" />');
        },
        type: "POST",
        url: "../version_check.php",
        data: info,
        dataType: "json",
        success: function (data) {
            // clear loading information
            versionCheckAuto.html("");
            // check for version verification
            if (data.version != 0) {
                var uInfo = "uid=" + uid + "&version=" + data.version;

                versionCheckAuto.html(
                    '<a class="updatelink" href="https://github.com/Monitorr/Logarr/releases" target="_blank" title="Click CHECK FOR UPDATE below to update Logarr" style="cursor: pointer;"> <b>An update is available </b></a>',
                );
            }

            else {
                // user has the latest version already installed
                versionCheckAuto.html("");
                console.log('Logarr update: You have the latest version.');
            }
        },
        error: function () {
            // error
            //TODO:
            //versionCheckAuto.html('<strong> An error occured while checking your Logarr version </strong>');
            versionCheckAuto.html('<p id="vcheckerror" class="vcheckerror">An error occurred while checking your Logarr version </p>');
            console.log('ERROR: An error occurred while checking your Logarr version');
        }
    });

    // check users files and update with most recent version
    let versionCheck = $('#version_check');
    versionCheck.on('click', function (e) {
        //$('#loading').show();
        var uid = $(this).attr("id");
        var info = "uid=" + uid + "&vcheck=1";
        $.ajax({
            beforeSend: function () {
                $('#version_check').html('<img src="../../images/loader.gif" width="16" height="16" />');
                console.log('Logarr is checking for an application update.');
            },
            type: "POST",
            url: "../version_check.php",
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
                            versionCheck.html('<img src="../../images/loader.gif" width="16" height="16" />');
                        },
                        type: "POST",
                        url: "../update-functions.php",
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
                                    $.blockUI(
                                        {
                                            css: {
                                                width: '35%',
                                                left: '32.5%',
                                            },
                                            message: 'Update Successful! <br> Reloading Logarr in 5 seconds...'
                                        }
                                    );
                                    setTimeout(location.reload.bind(location), 5000);
                                    // location.reload();
                                } else {
                                    // error during update/unzip
                                    console.log('Logarr update: An error occurred while extracting the files.');
                                    versionCheck.html("<strong>An error occurred while extracting the files.</strong>");
                                    // CHANGE ME:  Convert to Sweetalert:
                                    $.growlUI('An error occurred while extracting the files.');
                                    setTimeout(5000);
                                }

                            } else {
                                console.log('Logarr update: An error occurred while copying the files.');
                                versionCheck.html("<strong>An error occurred while copying the files.</strong>");
                                $.growlUI('An error occurred while copying the files.');
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
                    $.growlUI('You have the latest version. <br> Reloading Logarr in 5 seconds...');
                    setTimeout(location.reload.bind(location), 5000);
                }
            },
            error: function () {
                // error
                console.log('Logarr update: An error occurred while checking your Logarr version.');
                //versionCheck.html('<strong>An error occured while checking your Logarr version.</strong>');
                versionCheck.html('<p id="vcheckerror" class="vcheckerror"> An error occurred while checking your Logarr version.</p>');
                $.growlUI('An error occurred while checking your Logarr version.');
                setTimeout(5000);
            }
        });
    });
});
