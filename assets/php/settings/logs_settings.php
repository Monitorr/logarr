<?php
include ('../functions.php');
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
        $title = $preferences['sitetitle'];
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



<div id="modalloading" title="Monitorr logs are populating.">

    <div id="modalloadingspinner" style="transform:translateZ(0);"> </div>

    <script>
        window.paceOptions = {
            target: "#modalloadingspinner",
            ajax: false
        };
    </script>

    <p class="modaltextloading">Loading logs ...</p>

</div>

<div id="serviceform">
    <div id="servicesettings"></div>

    <script type="text/javascript">
        $(document).ready(function() {
            Alpaca.registerConnectorClass("custom");
            $("#servicesettings").alpaca({
                "connector": "custom",
                "dataSource": "./load-settings/logs_load.php",
                "schemaSource": "./schemas/logs.json",
                "view": {
                    "fields": {
                        "//logTitle": {
                            "templates": {
                                "control": "./templates/templates-logs_title.html"
                            },
                            "bindings": {
                                "serviceTitle": "#title_input"
                            }
                        },
                        "//path": {
                            "templates": {
                                "control": "./templates/templates-logs_path.html"
                            },
                            "bindings": {
                                "serviceTitle": "#path_input"
                            }
                        },
                        "//enabled": {
                            "templates": {
                                "control": "./templates/templates-logs_enabled.html"
                            },
                            "bindings": {
                                "enabled": "#enabled_option"
                            }
                        },
                        "//maxLines": {
                            "templates": {
                                "control": "./templates/templates-logs_maxLines.html"
                            },
                            "bindings": {
                                "maxLine": "#maxLines_option"
                            }
                        },
                        "//autoRollSize": {
                            "templates": {
                                "control": "./templates/templates-logs_autoRollSize.html"
                            },
                            "bindings": {
                                "autoRoleSize": "#autoRollSize_option"
                            }
                        },
                        "//category": {
                            "templates": {
                                "control": "./templates/templates-logs_category.html"
                            },
                            "bindings": {
                                "category": "#category_option"
                            }
                        }
                    }
                },
                "options": {
                    "toolbarSticky": true,
                    "focus": false,
                    "collapsible": true,
                    "focus": false,
                    "actionbar": {
                        "showLabels": true,
                        "actions": [{
                            "label": "Add Log",
                            "action": "add",
                            "iconClass": "fa fa-plus"
                        }, {
                            "label": "Remove Log",
                            "action": "remove",
                            "iconClass": "fa fa-minus"
                        }, {
                            "label": "Move Up",
                            "action": "up",
                            "iconClass": "fa fa-arrow-up",
                            "enabled": true
                        }, {
                            "label": "Move Down",
                            "action": "down",
                            "iconClass": "fa fa-arrow-down",
                            "enabled": true
                        }, {
                            "label": "Clear",
                            "action": "clear",
                            "iconClass": "fa fa-trash",
                            "click": function(key, action, itemIndex) {
                                var item = this.children[itemIndex];
                                item.setValue("");
                            }
                        }
                        ]
                    },
                    "items": {
                        "fields": {
                            "logTitle": {
                                "type": "text",
                                "validate": false,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Log Title:",
                                "constrainMaxLength": true,
                                "showMaxLengthIndicator": true,
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "logTitle",
                                "size": 20,
                                "placeholder": "Log Name",
                                "typeahead": {},
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
                            "path": {
                                "type": "text",
                                "validate": false,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Log Path:",
                                "constrainMaxLength": true,
                                "showMaxLengthIndicator": true,
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "path",
                                "size": 20,
                                "placeholder": "C:\\path\\to.log",
                                "typeahead": {},
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
                            "enabled": {
                                "type": "select",
                                "validate": false, // ** CHANGE ME ** change to TRUE to allow for user config propegation//
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Enabled:",
                                "hideInitValidationError": false,
                                "focus": false,
                                "name": "enabled",
                                "typeahead": {},
                                "allowOptionalEmpty": false,
                                "data": {},
                                "autocomplete": false,
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
                                "helper": "Log specific line maximum for logs.",
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "maxLines",
                                "placeholder": "E.g. 1000",
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
                            "autoRollSize": {
                                "type": "text",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Auto Roll Log:",
                                "helper": "Automatically roll log when equal or bigger then this size.",
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "autoRollSize",
                                "placeholder": "E.g. 2MB or 200KB",
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
                            "category": {
                                "type": "text",
                                "validate": false,
                                "showMessages": false,
                                "disabled": false,
                                "hidden": false,
                                "label": "Category:",
                                "constrainMaxLength": true,
                                "showMaxLengthIndicator": true,
                                "helpers": ["Category of the log, unused for now"],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "category",
                                "placeholder": "E.g. Media",
                                "typeahead": {},
                                "allowOptionalEmpty": true,
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
                            }
                        },
                    },
                    "form": {
                        "buttons": {
                            "submit": {
                                "type": "button",
                                "label": "Submit",
                                "name": "submit",
                                "value": "submit",
                                "click": function formsubmit() {
                                    var data = $('#servicesettings').alpaca().getValue();
                                    $.post('post-settings/post_receiver-logs.php', {
                                        data,
                                        success: function(data){
                                            alert("Settings saved! Applying changes...");
                                            // Refresh form after submit:
                                            setTimeout(location.reload.bind(location), 1000)
                                        },
                                        error: function(errorThrown){
                                            console.log(errorThrown);
                                        }
                                    },);
                                    $('.alpaca-form-button-submit').removeClass('buttonchange');
                                }
                            },
                            "reset":{
                                "label": "Clear Values"
                            }
                        }
                    }
                },
                "postRender": function(control) {
                    if (control.form) {
                        control.form.registerSubmitHandler(function (e) {
                            control.form.getButtonEl('submit').click();
                            return false;
                        });
                    }
                    document.getElementById("modalloading").remove();
                }
            });
        });
    </script>
</div>


</body>

</html>
