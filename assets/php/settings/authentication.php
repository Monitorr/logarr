<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/alpaca.min.css">
    <link rel="stylesheet" href="../../css/vendor/sweetalert2.min.css">
    <link rel="stylesheet" href="../../css/logarr.css">
    <link rel="stylesheet" href="../../data/custom.css">

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script src="../../js/vendor/sweetalert2.min.js"></script>
    <script type="text/javascript" src="../../js/handlebars.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/alpaca.min.js"></script>

    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | User Preferences
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
                title: 'Settings Saved! Logarr will now reload.',
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
                                "registrationEnabled": "rightcolumn",
                                "settingsEnabled": "leftcolumn",
                                "logsEnabled": "leftcolumn"
                            }
                        },
                        "fields": {
                            "/registrationEnabled": {
                                "templates": {
                                    "control": "./templates/authentication/templates-authentication_registrationenabled.html"
                                },
                                "bindings": {
                                    "registrationEnabled": "#registrationenabled"
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
                            "registrationEnabled": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Enable Registration:",
                                "helpers": ["Enable access to the registration page. (NOTE: For security purposes, this should be DISABLED ('false') after initial configuration.)"],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" True", " False"],
                                "name": "registrationEnabled",
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
                                        $('.registrationenabledlabel').addClass('settingslabelchanged');
                                        settingchange();
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
                                "helpers": ["Enable authentication for the settings page."],
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
                                        $('.settingsenabledlabel').addClass('settingslabelchanged');
                                        settingchange();
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
                                        $('.alpaca-form-button-submit').addClass('buttonchange');
                                        $('.logsenabledlabel').addClass('settingslabelchanged');
                                        settingchange();
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
                                                console.log("Settings saved!");
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