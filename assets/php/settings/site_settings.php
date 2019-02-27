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
    <link rel="stylesheet" href="../../css/alpaca.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/logarr.css">
    <link rel="stylesheet" href="../../data/custom.css">

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <script type="text/javascript" src="../../js/jquery.min.js"></script>
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

</head>

<body id="settings-frame-wrapper">

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
                    success: function(data) {
                        console.log(data);
                    },

                    error: function(errorThrown) {
                        console.log(errorThrown);
                        document.getElementById("response").innerHTML = "GET failed (ajax)";
                        alert("GET failed (ajax)");
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
                                "maxLines": "leftcolumn",
                                "rfconfig": "leftcolumn",
                                "rflog": "leftcolumn",
                                "rftime": "leftcolumn",
                                "customHiglightTerms": "leftcolumn",
                                "autoHighlight": "rightcolumn",
                                "jumpOnSearch": "rightcolumn",
                                "logRefresh": "rightcolumn",
                                "liveSearch": "rightcolumn",
                            }
                        },
                        "fields": {
                            "/rfconfig": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_rfconfig.html"
                                },
                                "bindings": {
                                    "rfconfig": "#rfconfig_input"
                                }
                            },
                            "/maxLines": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_maxlines.html"
                                },
                                "bindings": {
                                    "maxLines": "#maxlines_input"
                                }
                            },
                            "/rflog": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_rflog.html"
                                },
                                "bindings": {
                                    "rflog": "#rflog_input"
                                }
                            },
                            "/rftime": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_rftime.html"
                                },
                                "bindings": {
                                    "rftime": "#rftime_input"
                                }
                            },
                            "/customHighlightTerms": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_customhighlighterms.html"
                                },
                                "bindings": {
                                    "customHighlightTerms": "#customhighlighterms_input"
                                }
                            },
                            "/autoHighlight": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_autohighlight.html"
                                },
                                "bindings": {
                                    "autoHighlight": "#autohighlight_input"
                                }
                            },
                            "/jumpOnSearch": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_jumponsearch.html"
                                },
                                "bindings": {
                                    "jumpOnSearch": "#jumponsearch"
                                }
                            },
                            "/logRefresh": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_logrefresh.html"
                                },
                                "bindings": {
                                    "logRefresh": "#logrefresh"
                                }
                            },
                            "/liveSearch": {
                                "templates": {
                                    "control": "./templates/settings/templates-settings_livesearch.html"
                                },
                                "bindings": {
                                    "liveSearch": "#livesearch"
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
                            "rfconfig": {
                                "type": "number",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Config refresh interval:",
                                "helper": "Time (in milliseconds) the main Logarr UI will check for and apply newly configured settings.",
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
                                        $('.rfconfiglabel').addClass('settingslabelchanged');
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
                                        $('.maxlineslabel').addClass('settingslabelchanged');
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
                                        $('.rflog_inputlabel').addClass('settingslabelchanged');
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
                                        $('.rftime_inputlabel').addClass('settingslabelchanged');
                                    }
                                }
                            },
                            "customHighlightTerms": {
                                "type": "text",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Highlight Terms:",
                                "helper": "Highlight these terms. ('Auto Highlight' must be set to TRUE)",
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "customHighlightTerms",
                                "placeholder": "E.g. error,warn",
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
                                        $('.customhighlighterms_inputlabel').addClass('settingslabelchanged');
                                    }
                                }
                            },
                            "autoHighlight": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Auto Highlight:",
                                "helpers": ["Highlight terms automatically."],
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
                                        $('.autohighlightlabel').addClass('settingslabelchanged');
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
                                "helpers": ["Jump to 1st search result when a search is performed."],
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
                                        $('.jumponsearchlabel').addClass('settingslabelchanged');
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
                                "helpers": ["Automatically Refresh Logs."],
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
                                        $('.logrefreshlabel').addClass('settingslabelchanged');
                                    }
                                }
                            },
                            "liveSearch": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Live Search:",
                                "helpers": ["Automatically highlight terms when typing in the Search input field."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" True", " False"],
                                "name": "liveSearch",
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
                                        $('.livesearchlabel').addClass('settingslabelchanged');
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
                                    click: function() {
                                        let siteSettings = $('#sitesettings');
                                        var data = siteSettings.alpaca().getValue();
                                        $.post({
                                            url: 'post-settings/post_receiver-site_settings.php',
                                            data: siteSettings.alpaca().getValue(),
                                            success: function(data) {
                                                alert("Settings saved!");
                                                console.log("Settings Saved!");
                                                // setTimeout(location.reload.bind(location), 500)
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
                                                $('.livesearchlabel').removeClass('settingslabelchanged');
                                                $('.logrefreshlabel').removeClass('settingslabelchanged');
                                                $('.jumponsearchlabel').removeClass('settingslabelchanged');
                                                $('.autohighlightlabel').removeClass('settingslabelchanged');
                                                $('.customhighlighterms_inputlabel').removeClass('settingslabelchanged');
                                                $('.rftime_inputlabel').removeClass('settingslabelchanged');
                                                $('.rflog_inputlabel').removeClass('settingslabelchanged');
                                                $('.maxlineslabel').removeClass('settingslabelchanged');
                                                $('.rfconfiglabel').removeClass('settingslabelchanged');
                                            },
                                            error: function(errorThrown) {
                                                console.log(errorThrown);
                                                alert("Error submitting data.");
                                            }
                                        });
                                    }
                                },
                                "reset": {
                                    "label": "Clear Values"
                                }
                            },
                        }
                    },
                });

            });
        </script>

    </div>

</body>

</html> 