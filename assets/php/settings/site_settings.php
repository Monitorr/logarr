<?php
include ('..//functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <link type="text/css" href="../../css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="../../css/alpaca.min.css" rel="stylesheet">
    <!-- <link type="text/css" href="../../css/main.css" rel="stylesheet"> -->
    <link type="text/css" href="../../css/logarr.css" rel="stylesheet">
    <link type="text/css" href="../../css/custom.css" rel="stylesheet">

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script type="text/javascript" src="../../js/handlebars.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://code.cloudcms.com/alpaca/1.5.24/bootstrap/alpaca.min.js"></script>
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

        .auto-style1 {
            text-align: center;
        }

        #centertext {
            padding-bottom: 2rem !important;
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
        $title = $config['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | User Preferences
    </title>

</head>

<body class="transparent-background">

<script>
    document.body.className += ' fade-out';
    $(function() {
        $('body').removeClass('fade-out');
    });
</script>

<p id="response"></p>



<div id="siteform">

    <div id="sitesettings"></div>

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
                url: './load-settings/site_settings_load.php',
                data: data,
                success: function (data) {
                    console.log(data);
                },

                error: function(errorThrown){
                    console.log(errorThrown);
                    document.getElementById("response").innerHTML = "GET failed (ajax)";
                    alert( "GET failed (ajax)" );
                },
            });

            Alpaca.registerConnectorClass("custom", CustomConnector);
            $("#sitesettings").alpaca({
                "connector": "custom",
                "dataSource": "./load-settings/site_settings_load.php",
                "schemaSource": "./schemas/site_settings.json",
                "view": {
                    "parent": "bootstrap-edit-horizontal",
                    "layout": {
                        "template": './templates/two-column-layout-template.html',
                        "bindings": {
                            "autoHighlight": "leftcolumn",
                            "jumpOnSearch": "leftcolumn",
                            "logRefresh": "leftcolumn",
                            "maxLines": "leftcolumn",
                            "rfconfig": "rightcolumn",
                            "rflog": "rightcolumn",
                            "rftime": "rightcolumn",
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
                        "autoHighlight": {
                            "type": "radio",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Auto Highlight:",
                            "helpers": ["Highlight errors automatically."],
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [" True", " False"],
                            "name": "autoHighlight",
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
                                }
                            }
                        },
                        "jumpOnSearch": {
                            "type": "radio",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Jump on Search:",
                            "helpers": ["Jump to search result when searching."],
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [" True", " False"],
                            "name": "jumpOnSearch",
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
                                }
                            }
                        },
                        "logRefresh": {
                            "type": "radio",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Automatically Refresh Logs:",
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [" True", " False"],
                            "name": "logRefresh",
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
                                }
                            }
                        },
                        "maxLines": {
                            "type": "number",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Maximum amount of lines:",
                            "helper": "Default line maximum for logs.",
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [],
                            "name": "maxLines",
                            "placeholder": "1000",
                            "typeahead": {},
                            "size": "10",
                            "allowOptionalEmpty": false,
                            "data": {},
                            "autocomplete": false,
                            "disallowEmptySpaces": false,
                            "disallowOnlyEmptySpaces": false,
                            "fields": {},
                            "renderButtons": true,
                            "attributes": {},
                            "events": {
                                "change": function() {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                }
                            }
                        },
                        "rfconfig": {
                            "type": "number",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Config refresh interval:",
                            "helper": "Config refresh interval in milliseconds.",
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [],
                            "name": "rfconfig",
                            "placeholder": "5000",
                            "typeahead": {},
                            "size": "10",
                            "allowOptionalEmpty": false,
                            "data": {},
                            "autocomplete": false,
                            "disallowEmptySpaces": false,
                            "disallowOnlyEmptySpaces": false,
                            "fields": {},
                            "renderButtons": true,
                            "attributes": {},
                            "events": {
                                "change": function() {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                }
                            }
                        },
                        "rflog": {
                            "type": "number",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Log refresh interval:",
                            "helper": "Log refresh interval in milliseconds.",
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [],
                            "name": "rflog",
                            "placeholder": "5000",
                            "typeahead": {},
                            "size": "10",
                            "allowOptionalEmpty": false,
                            "data": {},
                            "autocomplete": false,
                            "disallowEmptySpaces": false,
                            "disallowOnlyEmptySpaces": false,
                            "fields": {},
                            "renderButtons": true,
                            "attributes": {},
                            "events": {
                                "change": function() {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                }
                            }
                        },
                        "rftime": {
                            "type": "number",
                            "validate": true,
                            "showMessages": true,
                            "disabled": false,
                            "hidden": false,
                            "label": "Time refresh interval:",
                            "helper": "UI clock display refresh interval in milliseconds.",
                            "hideInitValidationError": false,
                            "focus": false,
                            "optionLabels": [],
                            "name": "rftime",
                            "placeholder": "5000",
                            "typeahead": {},
                            "size": "10",
                            "allowOptionalEmpty": false,
                            "data": {},
                            "autocomplete": false,
                            "disallowEmptySpaces": false,
                            "disallowOnlyEmptySpaces": false,
                            "fields": {},
                            "renderButtons": true,
                            "attributes": {},
                            "events": {
                                "change": function() {
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                }
                            }
                        },
                    },
                    "form": {
                        "attributes": {
                            "action": "post-settings/post_receiver-site_settings.php",
                            "method": "post",
                        },
                        "buttons": {
                            "submit": {
                                "type": "button",
                                "label": "Submit",
                                "name": "submit",
                                "value": "submit",
                                click: function(){
                                    var data = $('#sitesettings').alpaca().getValue();
                                    $.post({
                                        url: 'post-settings/post_receiver-site_settings.php',
                                        data: $('#sitesettings').alpaca().getValue(),
                                        success: function(data) {
                                            alert("Settings saved!");
                                            // setTimeout(location.reload.bind(location), 500)
                                        },
                                        error: function(errorThrown){
                                            console.log(errorThrown);
                                            alert("Error submitting data.");
                                        }
                                    });
                                    $('.alpaca-form-button-submit').removeClass('buttonchange');
                                }
                            },
                            "reset":{
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

        });
    </script>

</div>

</body>

</html>
