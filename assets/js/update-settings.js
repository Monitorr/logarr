function updatechecklatest() {
    Toast.fire({
        toast: true,
        type: 'success',
        title: 'You have the latest <br> Logarr version',
        background: 'rgba(0, 184, 0, 0.75)',
        timer: 5000
    });
}

function updatecheck() {
    Toast.fire({
        toast: true,
        type: 'info',
        title: 'Logarr is checking for an <br> application update',
        showCloseButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        }
    });
}

function updateprogress() {
    Toast.fire({
        toast: true,
        type: 'warning',
        title: 'Logarr is updating',
        showCloseButton: false,
        onBeforeOpen: () => {
            Swal.showLoading();
        }
    });
}

function updatecheckerror() {
    Toast.fire({
        toast: true,
        type: 'error',
        title: 'An error occurred <br> while checking your Logarr version!',
        background: 'rgba(207, 0, 0, 0.75)'
    });
}

function updatesuccess() {
    Toast.fire({
        toast: true,
        type: 'success',
        title: 'Update Successful! <br> Reloading Logarr in 5 seconds...',
        background: 'rgba(0, 184, 0, 0.75)'
    });
}

function updateextracterror() {
    Toast.fire({
        toast: true,
        type: 'error',
        title: 'An error occurred <br> while extracting the update files!',
        background: 'rgba(207, 0, 0, 0.75)'
    });
}

function updatecopyerror() {
    Toast.fire({
        toast: true,
        type: 'error',
        title: 'An error occurred <br> while copying the update files!',
        background: 'rgba(207, 0, 0, 0.75)'
    });
}

function updatewriteerror() {
    Toast.fire({
        toast: true,
        type: 'error',
        title: 'An error occurred <br> while updating the Logarr files!',
        background: 'rgba(207, 0, 0, 0.75)'
    });
}

$(document).ready(function () {

    //Check if application is updated on page load:
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
                    '<a class="updatelink" href="https://github.com/Monitorr/Logarr/releases" target="_blank" title="Click CHECK FOR UPDATE below to update Logarr" style="cursor: pointer;"> <b>An update is available </b></a>'
                );
                $('#version_check').addClass('version_check-update');

            } else {
                // user has the latest version already installed
                versionCheckAuto.html("");
                console.log('Logarr update: You have the latest version');
            }
        },
        error: function () {
            // error
            versionCheckAuto.html('<p id="vcheckerror" class="vcheckerror">An error occurred while checking your Logarr version </p>');
            console.log('ERROR: An error occurred while checking your Logarr version');
            updatecheckerror();
        }
    });

    // Check and execute update when "CHECK FOR UPDATE" button is fired:

    let versionCheck = $('#version_check');
    versionCheck.on('click', function (e) {
        var uid = $(this).attr("id");
        var info = "uid=" + uid + "&vcheck=1";
        $.ajax({
            beforeSend: function () {
                console.log('Logarr is checking for an application update');
                updatecheck();
                $('#version_check').html('<img src="../../images/loader.gif" width="16" height="16" />');
            },
            type: "POST",
            url: "../version_check.php",
            data: info,
            dataType: "json",
            success: function (data) {

                // check for version verification
                if (data.version != 0) {
                    var uInfo = "uid=" + uid + "&version=" + data.version;
                    $.ajax({
                        beforeSend: function () {

                            console.log('Logarr is updating...');

                            updateprogress();

                            versionCheck.html('<img src="../../images/loader.gif" width="16" height="16" />');
                        },
                        type: "POST",
                        url: "../update-functions.php",
                        data: uInfo,
                        dataType: "json",
                        success: function (data) {

                            // check for version verification:
                            if (data.copy != 0) {
                                if (data.unzip == 1) {

                                    // successful update
                                    console.log('Logarr update successful! Reloading Logarr in 5 seconds...');
                                    versionCheck.html("<strong>SUCCESSFUL!</strong>");
                                    updatesuccess();
                                    
                                    //reload DOM after successful update:
                                    setTimeout(function() {
                                        window.top.location.reload(true);
                                    }, 5000);

                                } else {
                                    // error during update/unzip
                                    console.log('Logarr update: An error occurred while extracting the update files!');
                                    versionCheck.html('<p id="vcheckerror" class="vcheckerror"> ERROR! </p>');
                                    updateextracterror();
                                    setTimeout(5000);
                                }
                            } else {
                                console.log('Logarr update: An error occurred while copying the update files!');
                                versionCheck.html('<p id="vcheckerror" class="vcheckerror"> ERROR! </p>');
                                updatecopyerror();
                                setTimeout(5000);
                            }
                        },
                        error: function () {
                            // error
                            console.log('Logarr update: An error occurred while updating the Logarr files!');
                            versionCheck.html('<p id="vcheckerror" class="vcheckerror"> ERROR! </p>');
                            updatewriteerror();
                            setTimeout(5000);
                        }
                    });
                } else {
                    // latest version already installed:
                    console.log('Logarr update: You have the latest Logarr version');
                    versionCheck.html("Check for Update");
                    updatechecklatest();
                }
            },
            error: function () {
                // error
                console.log('Logarr update: An error occurred while checking your Logarr version');
                versionCheck.html('<p id="vcheckerror" class="vcheckerror"> ERROR! </p>');
                updatecheckerror();
                setTimeout(5000);
            }
        });
    });
});
