<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>

<!DOCTYPE html>
<html lang="en">

<!-- logs_settings.php -->

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

    <meta name="theme-color" content="#464646" />
    <meta name="theme_color" content="#464646" />

    <script type="text/javascript" src="../../js/jquery.min.js"></script>
    <script src="../../js/vendor/sweetalert2.min.js"></script>
    <script type="text/javascript" src="../../js/handlebars.js"></script>
    <script type="text/javascript" src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript" src="../../js/alpaca.min.js"></script>
    <script type="text/javascript" src="../../js/vendor/jquery-ui.min.js"></script>

    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | Log Config
    </title>

    <style>
        .alpaca-form-buttons-container {
            text-align: left;
            position: fixed;
            bottom: 0;
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

        function logloadinganimate() {
            Toast.fire({
                toast: true,
                title: 'Loading Logs...',
                showCloseButton: false,
                background: 'rgba(50, 1, 25, 0.75)',
                onBeforeOpen: () => {
                    Swal.showLoading()
                }
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

        function dupeerror() {
            Toast.fire({
                type: 'error',
                title: 'This title has already been used!',
                background: 'rgba(207, 0, 0, 0.75)'
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

    <script>
        $(document).ready(function() {
            logloadinganimate();
        });
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

    <div id="logsform">
        <div id="logssettings"></div>

        <script type="text/javascript">
            $(document).ready(function() {
                Alpaca.registerConnectorClass("custom");

                $("#logssettings").alpaca({
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
                                    "logTitle": "#title_input"
                                }
                            },
                            "//path": {
                                "templates": {
                                    "control": "./templates/templates-logs_path.html"
                                },
                                "bindings": {
                                    "path": "#path_input"
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
                                    "maxLines": "#maxLines_option"
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
                        "actionbar": {
                            "showLabels": true,
                            "actions": [{
                                "label": "Add Log",
                                "action": "add",
                                "iconClass": "fa fa-plus"
                            }, {
                                "label": "Remove Log",
                                "action": "remove",
                                "iconClass": "fa fa-trash"
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
                                "iconClass": "fa fa-eraser",
                                "click": function(key, action, itemIndex) {
                                    var item = this.children[itemIndex];
                                    item.setValue("");
                                }
                            }]
                        },
                        "items": {
                            "fields": {
                                "logTitle": {
                                    "type": "text",
                                    "fieldClass": "log-title-input",
                                    "validate": true,
                                    "showMessages": true,
                                    "label": "Log Title:",
                                    "constrainMaxLength": true,
                                    "showMaxLengthIndicator": true,
                                    "name": "logTitle",
                                    "size": 20,
                                    "placeholder": "Log Name",
                                    "inputType": "search",
                                    "events": {
                                        "change": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                console.log("ERROR: Invalid value for Log Title.");
                                                validerror();
                                                this.focus();
                                            } else {
                                                Toast.close();
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                                settingchange();
                                            }
                                        },
                                        "keyup": function(e) {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                                console.log("ERROR: Invalid value for Log Title");
                                            } else {
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                                settingchange();
                                            }
                                        },
                                        "blur": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                            }
                                        },
                                        "focus": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                            }
                                        },
                                        "ready": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                console.log("ERROR: Invalid value for Log Title.");
                                                validerror();
                                                this.focus();
                                            }
                                        }
                                    },
                                    "validator": function(callback) {
                                        var currentFieldValue = this.getValue();
                                        var calledBack = false;
                                        var results = 0;
                                        $('.log-title-input input').each(function(index, value) {
                                            if (value.value == currentFieldValue) {
                                                results += 1;
                                                if (results > 1) {
                                                    callback({
                                                        "status": false,
                                                        "message": "This title has already been used for another log!"
                                                    });
                                                    console.log("ERROR: This title has already been used for another log!");
                                                    dupeerror();
                                                    calledBack = true;
                                                }
                                            }
                                        });
                                        if (!calledBack) {
                                            callback({
                                                "status": true
                                            });
                                        }
                                    }
                                },
                                "path": {
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "focus": true,
                                    "label": "Log Path:",
                                    //"helpers": ["Can be dynamic - see <a class='footer' href='https://github.com/Monitorr/logarr/wiki/Settings#dynamic-paths' target='_blank'>wiki</a>"],
                                    "name": "path",
                                    "inputType": "search",
                                    "placeholder": "C:\\path\\to.log",
                                    "events": {
                                        "change": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                console.log("ERROR: Invalid value for Log Path.");
                                                validerror();
                                                this.focus();
                                            } else {
                                                Toast.close();
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                                settingchange();
                                            }
                                        },
                                        "keyup": function(e) {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                                console.log("ERROR: Invalid value for Log Path.");
                                            } else {
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                                settingchange();
                                            }
                                        },
                                        "blur": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                            }
                                        },
                                        "focus": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                            }
                                        },
                                        "ready": function() {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                console.log("ERROR: Invalid value for Log Path.");
                                                validerror();
                                                this.focus();
                                            }
                                        }
                                    }
                                },
                                "enabled": {
                                    "type": "select",
                                    "showMessages": true,
                                    "label": "Enabled:",
                                    "name": "enabled",
                                    // "helpers": ["Enable or disable this log"],
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            settingchange();
                                        }
                                    }
                                },
                                "maxLines": {
                                    "type": "number",
                                    "validate": true,
                                    "showMessages": true,
                                    "label": "Maximum lines:",
                                    //"helpers": ["Maximum line display for this log."],
                                    "name": "maxLines",
                                    "placeholder": "1000",
                                    "size": "10",
                                    "inputType": "search",
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            settingchange();
                                        },
                                        "keyup": function(e) {
                                            this.refreshValidationState(true);
                                            if (!this.isValid(true)) {
                                                validerror();
                                                console.log("ERROR: Invalid value for Max Lines");
                                            } else {
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
                                                settingchange();
                                            }
                                        },
                                    }
                                },
                                "autoRollSize": {
                                    "type": "text",
                                    "validate": true,
                                    "showMessages": true,
                                    "label": "Auto Roll Log:",
                                    //"helpers": ["Automatically roll log when equal or bigger then this size."],
                                    "name": "autoRollSize",
                                    "placeholder": "E.g. 2MB or 200KB",
                                    "size": "10",
                                    "inputType": "search",
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            settingchange();
                                        }
                                    }
                                },
                                "category": {
                                    "type": "text",
                                    "label": "Category:",
                                    //"helpers": ["Category of the log, will create a tab on the homepage"],
                                    "hideInitValidationError": false,
                                    "name": "category",
                                    "placeholder": "E.g. Media",
                                    "inputType": "search",
                                    "events": {
                                        "change": function() {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            settingchange();
                                        },
                                        "keyup": function(e) {
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            settingchange();
                                        },
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
                                    "id": "submitbtn",
                                    "value": "submit",
                                    "click": function formsubmit() {
                                        var data = $('#logssettings').alpaca().getValue();
                                        $.post('post-settings/post_receiver-logs.php', {
                                            data,
                                            success: function(data) {
                                                settingapply();
                                                console.log("Settings saved! Applying changes...");
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
                                                $('.btn-sm').click(function() {
                                                    settingchange();
                                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                                });
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) {
                                                console.log(errorThrown);
                                            }
                                        });
                                    }
                                },
                                "reset": {
                                    "label": "Clear Values",
                                    "id": "clearbtn",
                                }
                            }
                        }
                    },
                    "postRender": function(control) {
                        if (control.form) {
                            control.form.registerSubmitHandler(function(e) {
                                control.form.getButtonEl('submit').click();
                                return false;
                            });
                        }
                        Toast.close();
                        $('.btn-sm').click(function() {
                            settingchange();
                            $('.alpaca-form-button-submit').addClass('buttonchange');
                        });
                    }
                });
            });
        </script>
    </div>

</body>

</html>