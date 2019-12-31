<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">

<!-- authentication.php -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/alpaca.min.css">
    <link rel="stylesheet" href="../../css/vendor/sweetalert2.min.css">
    <link rel="stylesheet" href="../../css/vendor/jquery-ui.min.css">
    <link rel="stylesheet" href="../../css/logarr.css">
    <link rel="stylesheet" href="../../data/custom.css">

    <meta name="theme-color" content="#464646">
    <meta name="theme_color" content="#464646">

    <!-- <script type="text/javascript" src="../../js/jquery.min.js"></script> -->
    <script src="../../js/jquery-3.3.1.min.js"></script>
    <script src="../../js/vendor/sweetalert2.min.js"></script>
    <script src="../../js/handlebars.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/alpaca.min.js"></script>
    <script src="../../js/vendor/jquery-ui.min.js"></script>

    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | Settings
    </title>

    <style>
        .swal2-popup.swal2-toast {
            cursor: default !important;
        }
    </style>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            showCloseButton: true,
            position: 'bottom-end',
            background: 'rgba(50, 1, 25, 0.75)'
        });

        function settingchange() {
            Toast.fire({
                type: 'warning',
                title: 'Settings change pending'
            })
        };

        function settingapply() {
            Toast.fire({
                type: 'success',
                title: 'Settings Saved! <br> Logarr is reloading',
                background: 'rgba(0, 184, 0, 0.75)'
            })
        };

        function settingserror() {
            Toast.fire({
                type: 'error',
                title: 'Error saving settings!',
                background: 'rgba(207, 0, 0, 0.75)'
            })
        };

        function ajaxerror() {
            Toast.fire({
                type: 'error',
                title: 'Error loading settings!',
                background: 'rgba(207, 0, 0, 0.75)'
            })
        };

        function validerror() {
            Toast.fire({
                type: 'error',
                title: 'Invalid value!',
                background: 'rgba(207, 0, 0, 0.75)'
            })
        };
    </script>

    <!-- Tooltips: -->
    <script>
        $(function() {
            $(document).tooltip({
                hide: {
                    effect: "fadeOut",
                    duration: 200
                }
            });
        });
    </script>

</head>

<body id="settings-frame-wrapper">

    <script>
        document.body.className += ' fade-out';
        $(function() {
            $('body').removeClass('fade-out');
        });
    </script>

    <p id="response"></p>

    <div id="authenticationform">

        <div id="authenticationsettings"></div>

        <script type="text/javascript">
            $(document).ready(function() {
                var CustomConnector = Alpaca.Connector.extend({
                    buildAjaxConfig: function(uri, isJson) {
                        var ajaxConfig = this.base(uri, isJson);
                        ajaxConfig.headers = {
                            "ssoheader": "abcde12345"
                        };
                        return ajaxConfig;
                    }
                });

                var data;
                $.ajax({
                    dataType: "json",
                    url: './load-settings/authentication_load.php',
                    data: data,
                    success: function(data) {
                        console.log(data);
                    },

                    error: function(errorThrown) {
                        console.log(errorThrown);
                        document.getElementById("response").innerHTML = "GET failed (ajax)";
                        //alert("GET failed (ajax)");
                        ajaxerror();
                    },
                });

                Alpaca.registerConnectorClass("custom", CustomConnector);
                $("#authenticationsettings").alpaca({
                    "connector": "custom",
                    "dataSource": "./load-settings/authentication_load.php",
                    "schemaSource": "./schemas/authentication.json",
                    "view": {
                        "parent": "bootstrap-edit-horizontal",
                        "layout": {
                            "template": './templates/two-column-layout-template.html',
                            "bindings": {
                                "setupEnabled": "rightcolumn",
                                "settingsEnabled": "leftcolumn",
                                "logsEnabled": "leftcolumn"
                            }
                        },
                        "fields": {
                            "/setupEnabled": {
                                "templates": {
                                    "control": "./templates/authentication/templates-authentication_setupenabled.html"
                                },
                                "bindings": {
                                    "setupEnabled": "#authentication_setupenabled"
                                }
                            },
                            "/settingsEnabled": {
                                "templates": {
                                    "control": "./templates/authentication/templates-authentication_settingsenabled.html"
                                },
                                "bindings": {
                                    "settingsEnabled": "#settingsenabled"
                                }
                            },
                            "/logsEnabled": {
                                "templates": {
                                    "control": "./templates/authentication/templates-authentication_logsenabled.html"
                                },
                                "bindings": {
                                    "logsEnabled": "#logsenabled"
                                }
                            }
                        }
                    },
                    "options": {
                        "focus": false,
                        "type": "object",
                        "helpers": [],
                        "validate": true,
                        "disabled": false,
                        "showMessages": true,
                        "collapsible": false,
                        "legendStyle": "button",
                        "fields": {
                            "setupEnabled": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Enable Setup access:",
                                "helpers": ["Enable access to the Setup page. (NOTE: For security purposes, this should be DISABLED ('False') after initial Setup.)"],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" True", " False"],
                                "name": "setupEnabled",
                                "typeahead": {},
                                "allowOptionalEmpty": false,
                                "data": {},
                                "autocomplete": "false",
                                "disallowEmptySpaces": true,
                                "disallowOnlyEmptySpaces": false,
                                "removeDefaultNone": true,
                                "fields": {},
                                "events": {
                                    "change": function() {
                                        $('.alpaca-form-button-submit').addClass('buttonchange');
                                        $('.setupenabledlabel').removeClass('settingslabelerror');
                                        $('.setupenabledlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log('%cERROR: Invalid value for Enable Setup access!', 'color: #FF0000;');
                                            $('.setupenabledlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            },
                            "settingsEnabled": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Enable Settings authentication:",
                                "helpers": ["Enable authentication for the Settings page."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" True", " False"],
                                "name": "settingsEnabled",
                                "typeahead": {},
                                "allowOptionalEmpty": false,
                                "data": {},
                                "autocomplete": "false",
                                "disallowEmptySpaces": true,
                                "disallowOnlyEmptySpaces": false,
                                "removeDefaultNone": true,
                                "fields": {},
                                "events": {
                                    "change": function() {
                                        $('.alpaca-form-button-submit').addClass('buttonchange');
                                        $('.settingsenabledlabel').removeClass('settingslabelerror');
                                        $('.settingsenabledlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log('%cERROR: Invalid value for Settings Authentication!', 'color: #FF0000;');
                                            $('.settingsenabledlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            },
                            "logsEnabled": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Enable Logarr authentication:",
                                "helpers": ["Enable authentication for the main Logarr UI."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" True", " False"],
                                "name": "logsEnabled",
                                "typeahead": {},
                                "allowOptionalEmpty": false,
                                "data": {},
                                "autocomplete": "false",
                                "disallowEmptySpaces": true,
                                "disallowOnlyEmptySpaces": false,
                                "removeDefaultNone": true,
                                "fields": {},
                                "events": {
                                    "change": function() {
                                        $('#authnote').removeClass('hidden');
                                        $('.alpaca-form-button-submit').addClass('buttonchange');
                                        $('.logsenabledlabel').removeClass('settingslabelerror');
                                        $('.logsenabledlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log('%cERROR: Invalid value for Logarr Authentication!', 'color: #FF0000;');
                                            $('.logsenabledlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            }
                        },
                        "form": {
                            "attributes": {
                                "action": "post-settings/post_receiver-authentication.php",
                                "method": "post",
                            },
                            "buttons": {
                                "submit": {
                                    "type": "button",
                                    "label": "Submit",
                                    "name": "submit",
                                    "value": "submit",
                                    click: function() {
                                        let authenticationsettings = $('#authenticationsettings');
                                        var data = authenticationsettings.alpaca().getValue();
                                        $.post({
                                            url: 'post-settings/post_receiver-authentication.php',
                                            data: authenticationsettings.alpaca().getValue(),
                                            success: function(data) {
                                                settingapply();
                                                console.log("Settings saved! Logarr is reloading");
                                                setTimeout(window.top.location.reload.bind(window.top.location), 1000);
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
                                            },
                                            error: function(errorThrown) {
                                                console.log(errorThrown);
                                                // alert("Error submitting data.");
                                                settingserror();
                                            }
                                        });
                                    }
                                }
                            },
                        }
                    },
                });

            })
        </script>

    </div>

</body>

</html>