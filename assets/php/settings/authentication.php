<?php
include('../functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../../css/alpaca.min.css" rel="stylesheet">
    <!-- <link type="text/css" href="../../css/main.css" rel="stylesheet"> -->
    <link type="text/css" href="../../css/logarr.css" rel="stylesheet">
    <link type="text/css" href="../../data/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646"/>
    <meta name="theme_color" content="#464646"/>

    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/handlebars.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://code.cloudcms.com/alpaca/1.5.24/bootstrap/alpaca.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script>

    <style>

        body {
            margin: 2vw !important;
            overflow-y: auto;
            overflow-x: hidden;
            color: white !important;
        }

        legend {
            color: white;
        }

        body::-webkit-scrollbar {
            width: 10px;
            background-color: #252525;
        }

        body::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            background-color: #252525;
        }

        body::-webkit-scrollbar-thumb {
            border-radius: 10px;
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
            background-color: #8E8B8B;
        }

        body.offline #link-bar {
            display: none;
        }

        body.online #link-bar {
            display: block;
        }

        label {
            width: 100% !important;
            max-width: 100% !important;
        }

        input[type=checkbox], input[type=radio] {
            cursor: pointer;
        }

    </style>


    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | User Preferences
    </title>

</head>

<body class="transparent-background">

<script>
    document.body.className += ' fade-out';
    $(function () {
        $('body').removeClass('fade-out');
    });
</script>

<p id="response"></p>


<div id="authenticationform">

    <div id="authenticationsettings"></div>

    <script type="text/javascript">
        $(document).ready(function () {
            var CustomConnector = Alpaca.Connector.extend({
                buildAjaxConfig: function (uri, isJson) {
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
                success: function (data) {
                    console.log(data);
                },

                error: function (errorThrown) {
                    console.log(errorThrown);
                    document.getElementById("response").innerHTML = "GET failed (ajax)";
                    alert("GET failed (ajax)");
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
                            "registrationEnabled": "leftcolumn",
                            "settingsEnabled": "leftcolumn",
                            "logsEnabled": "rightcolumn"
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
                            "helpers": ["Enable registration page."],
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
                                "change": function () {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                }
                            }
                        },
                        "settingsEnabled": {
                            "type": "radio",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Enable Settings Authentication:",
                            "helpers": ["Enable authentication for the settingspage."],
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
                                "change": function () {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                }
                            }
                        },
                        "logsEnabled": {
                            "type": "radio",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Enable authentication for the logs page:",
                            "helpers": ["Enable authentication for the homepage showing the logs."],
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
                                "change": function () {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
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
                                click: function () {
                                    let authenticationsettings = $('#authenticationsettings');
                                    var data = authenticationsettings.alpaca().getValue();
                                    $.post({
                                        url: 'post-settings/post_receiver-authentication.php',
                                        data: authenticationsettings.alpaca().getValue(),
                                        success: function (data) {
                                            alert("Settings saved!");
                                            setTimeout(window.top.location.reload.bind(window.top.location), 500)
                                        },
                                        error: function (errorThrown) {
                                            console.log(errorThrown);
                                            alert("Error submitting data.");
                                        }
                                    });
                                    $('.alpaca-form-button-submit').removeClass('buttonchange');
                                }
                            },
                            "reset": {
                                "label": "Clear Values"
                            }
                            // "view": {
                            //     "type": "button",
                            //     "label": "View JSON",
                            //     "value": "View JSON",
                            //     "click": function() {
                            //         alert(JSON.stringify(this.getValue(), null, "  "));
                            //     }
                            // }
                        },
                    }
                },
            });

        })
    </script>

</div>

</body>

</html>
