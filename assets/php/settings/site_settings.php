<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>

<!DOCTYPE html>
<html lang="en">

<!-- site_settings.php -->

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/alpaca.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
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
        | Logarr Settings
    </title>

    <style>
        .swal2-popup.swal2-toast {
            cursor: default !important;
        }

        #settings-frame-wrapper {
            margin-top: 10vh;
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
                title: 'Settings change pending',
                customClass: "settingchange"
            })
        };

        function settingapply() {
            Toast.fire({
                type: 'success',
                title: 'Settings Saved!',
                timer: 3000,
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

        function clearsettings() {
            Toast.fire({
                type: 'warning',
                title: 'Cleared Setting Values!',
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

    <!-- if clear button is fired, prevent form sumbit if required setting values are not valid -->
    <script>
        $(document).on('click', "button[data-key='reset']", function(event) {
            document.getElementById("submitbtn").disabled = true;
            console.log("Cleared setting values");
            $('.rfconfiglabel').addClass('settingslabelerror');
            $('.maxlineslabel').addClass('settingslabelerror');
            $('.rflog_inputlabel').addClass('settingslabelerror');
            $('.rftime_inputlabel').addClass('settingslabelerror');
            $('.customhighlighterms_inputlabel').addClass('settingslabelchanged');
            clearsettings();
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
                        document.getElementById("response").innerHTML = "ERROR: GET failed (ajax)";
                        //alert("GET failed (ajax)");
                        ajaxerror();
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
                                "customHighlightTerms": "leftcolumn",
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
                                "placeholder": "15000",
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
                                "inputType": "search",
                                "events": {
                                    "change": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Config refresh interval.", "color: red;");
                                            $('.rfconfiglabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        } else {
                                            Toast.close();
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            $('.rfconfiglabel').addClass('settingslabelchanged');
                                            $('.rfconfiglabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "keyup": function(e) {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rfconfiglabel').addClass('settingslabelerror');
                                        } else {
                                            $('.rfconfiglabel').addClass('settingslabelchanged');
                                            $('.rfconfiglabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "blur": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rfconfiglabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "focus": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rfconfiglabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Config refresh interval.", "color: red;");
                                            $('.rfconfiglabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        }
                                    }
                                }
                            },
                            "maxLines": {
                                "type": "number",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Maximum lines:",
                                "helper": "Default maximum line display for all logs.",
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "maxLines",
                                "placeholder": "2000",
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
                                "inputType": "search",
                                "events": {
                                    "change": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Maximum Lines.", "color: red;");
                                            $('.maxlineslabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        } else {
                                            Toast.close();
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            $('.maxlineslabel').addClass('settingslabelchanged');
                                            $('.maxlineslabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "keyup": function(e) {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.maxlineslabel').addClass('settingslabelerror');
                                        } else {
                                            $('.maxlineslabel').addClass('settingslabelchanged');
                                            $('.maxlineslabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "blur": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.maxlineslabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "focus": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.maxlineslabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Maximum Lines.", "color: red;");
                                            $('.maxlineslabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        }
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
                                "placeholder": "30000",
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
                                "inputType": "search",
                                "events": {
                                    "change": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Log refresh interval.", "color: red;");
                                            $('.rflog_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        } else {
                                            Toast.close();
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            $('.rflog_inputlabel').addClass('settingslabelchanged');
                                            $('.rflog_inputlabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "keyup": function(e) {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rflog_inputlabel').addClass('settingslabelerror');
                                        } else {
                                            $('.rflog_inputlabel').addClass('settingslabelchanged');
                                            $('.rflog_inputlabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "blur": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rflog_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "focus": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rflog_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Log refresh interval.", "color: red;");
                                            $('.rflog_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        }
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
                                "placeholder": "60000",
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
                                "inputType": "search",
                                "events": {
                                    "change": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Time refresh interval.", "color: red;");
                                            $('.rftime_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        } else {
                                            Toast.close();
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            $('.rftime_inputlabel').addClass('settingslabelchanged');
                                            $('.rftime_inputlabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "keyup": function(e) {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rftime_inputlabel').addClass('settingslabelerror');
                                        } else {
                                            $('.rftime_inputlabel').addClass('settingslabelchanged');
                                            $('.rftime_inputlabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "blur": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rftime_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "focus": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.rftime_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Time refresh interval.", "color: red;");
                                            $('.rftime_inputlabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        }
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
                                "helper": "Highlight these terms. (NOTE: Terms must be comma separated and 'Auto Highlight' must be set to TRUE. Instructions for setting custom highlight colors can be found on the Logarr Wiki.)",
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "customHighlightTerms",
                                "placeholder": "E.g. error,warn",
                                "typeahead": {},
                                "size": "10",
                                "allowOptionalEmpty": true,
                                "data": {},
                                "autocomplete": false,
                                "disallowEmptySpaces": false,
                                "disallowOnlyEmptySpaces": false,
                                "fields": {},
                                "renderButtons": true,
                                "attributes": {},
                                "inputType": "search",
                                "events": {
                                    "change": function() {
                                        $('.alpaca-form-button-submit').addClass('buttonchange');
                                        $('.customhighlighterms_inputlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "keyup": function(e) {
                                        $('.customhighlighterms_inputlabel').addClass('settingslabelchanged');
                                    },
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
                                        $('.autohighlightlabel').removeClass('settingslabelerror');
                                        $('.autohighlightlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Auto Highlight.", "color: red;");
                                            $('.autohighlightlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
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
                                "helpers": ["Jump to first search result when a search is performed."],
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
                                        $('.jumponsearchlabel').removeClass('settingslabelerror');
                                        $('.jumponsearchlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Jump on Search.", "color: red;");
                                            $('.jumponsearchlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            },
                            "logRefresh": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Auto Refresh Logs:",
                                "helpers": ["Auto Refresh Logs."],
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
                                        $('.logrefreshlabel').removeClass('settingslabelerror');
                                        $('.logrefreshlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Auto Refresh Logs.", "color: red;");
                                            $('.logrefreshlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
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
                                        $('.livesearchlabel').removeClass('settingslabelerror');
                                        $('.livesearchlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("%cERROR: Invalid value for Live Search.", "color: red;");
                                            $('.livesearchlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
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
                                    "id": "submitbtn",
                                    "value": "submit",
                                    click: function() {
                                        let siteSettings = $('#sitesettings');
                                        var data = siteSettings.alpaca().getValue();
                                        $.post({
                                            url: 'post-settings/post_receiver-site_settings.php',
                                            data: siteSettings.alpaca().getValue(),
                                            success: function(data) {
                                                settingapply();
                                                console.log("Settings Saved!");
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
                                                settingserror();
                                            }
                                        });
                                    }
                                },
                                "reset": {
                                    "label": "Clear Values",
                                    "id": "clearbtn",
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