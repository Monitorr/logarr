<?php
include('../functions.php');
include(__DIR__ . '/../auth_check.php');
?>

<!DOCTYPE html>
<html lang="en">

<!-- user_preferences.php -->

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
    <script src="../../js/vendor/ace.js"></script>
    <script src="../../js/vendor/jquery-ui.min.js"></script>

    <title>
        <?php
        $title = $GLOBALS['preferences']['sitetitle'];
        echo $title . PHP_EOL;
        ?>
        | User Preferences
    </title>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            showConfirmButton: false,
            showCloseButton: true,
            position: 'bottom-end',
            background: 'rgba(50, 1, 25, 0.75)',
            onBeforeOpen: () => {
                $(".swal2-container").draggable({
                    containment: "#containment-wrapper",
                    scroll: false
                });
            }
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
                title: 'Settings Saved! <br> Logarr is reloading',
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

    <script>
        $(document).on('click', "button[data-key='reset']", function(event) {
            document.getElementById("submitbtn").disabled = true;
            console.log("Cleared setting values");
            $('.sitetitlelabel').addClass('settingslabelerror');
            $('.siteurllabel').addClass('settingslabelchanged');
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

    <div id="preferenceform">

        <div id="preferencesettings"></div>

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
                    url: './load-settings/user_preferences_load.php',
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

                $("#preferencesettings").alpaca({
                    "connector": "custom",
                    "dataSource": "./load-settings/user_preferences_load.php",
                    "schemaSource": "./schemas/user_preferences.json",
                    "view": {
                        "parent": "bootstrap-edit-horizontal",
                        "layout": {
                            "template": './templates/two-column-layout-template-user-preferences.html',
                            "bindings": {
                                "sitetitle": "leftcolumn",
                                "siteurl": "leftcolumn",
                                "updateBranch": "leftcolumn",
                                "language": "rightcolumn",
                                "timezone": "rightcolumn",
                                "timestandard": "rightcolumn"
                            }
                        },
                        "fields": {
                            "/sitetitle": {
                                "templates": {
                                    "control": "./templates/templates-user-preferences_title.html"
                                },
                                "bindings": {
                                    "sitetitle": "#site_title_input"
                                }
                            },
                            "/siteurl": {
                                "templates": {
                                    "control": "./templates/templates-user-preferences_url.html"
                                },
                                "bindings": {
                                    "siteurl": "#site_url_input"
                                }
                            },
                            "/updateBranch": {
                                "templates": {
                                    "control": "./templates/templates-user-preferences_updatebranch.html"
                                },
                                "bindings": {
                                    "updateBranch": "#updatebranch"
                                }
                            },
                            "/timezone": {
                                "templates": {
                                    "control": "./templates/templates-user-preferences_timezone.html"
                                },
                                "bindings": {
                                    "timzone": "#timezone"
                                }
                            },
                            "/timestandard": {
                                "templates": {
                                    "control": "./templates/templates-user-preferences_timestandard.html"
                                },
                                "bindings": {
                                    "timzone": "#timestandard"
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
                            "sitetitle": {
                                "type": "text",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Site Title:",
                                "constrainMaxLength": true,
                                "showMaxLengthIndicator": true,
                                "helpers": ["Text that is displayed in the top header."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "sitetitle",
                                "placeholder": "Logarr",
                                "typeahead": {},
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
                                            console.log("ERROR: Invalid value for Site Title.");
                                            $('.sitetitlelabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        } else {
                                            Toast.close();
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            $('.sitetitlelabel').addClass('settingslabelchanged');
                                            $('.sitetitlelabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "keyup": function(e) {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.sitetitlelabel').addClass('settingslabelerror');
                                        } else {
                                            $('.sitetitlelabel').addClass('settingslabelchanged');
                                            $('.sitetitlelabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "blur": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.sitetitlelabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "focus": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.sitetitlelabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("ERROR: Invalid value for Site Title.");
                                            $('.sitetitlelabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        }
                                    }
                                }
                            },
                            "siteurl": {
                                "type": "url",
                                "validate": false,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Site URL:",
                                "helpers": ["URL of the Logarr UI."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "siteurl",
                                "placeholder": "http://localhost/logarr",
                                "typeahead": {},
                                "allowOptionalEmpty": true,
                                "data": {},
                                "autocomplete": "false",
                                "disallowEmptySpaces": true,
                                "disallowOnlyEmptySpaces": false,
                                "allowIntranet": true,
                                "fields": {},
                                "events": {
                                    "change": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("ERROR: Invalid value for Site URL.");
                                            $('.siteurllabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        } else {
                                            Toast.close();
                                            $('.alpaca-form-button-submit').addClass('buttonchange');
                                            $('.siteurllabel').addClass('settingslabelchanged');
                                            $('.siteurllabel').removeClass('settingslabelerror');
                                            settingchange();
                                        }
                                    },
                                    "blur": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.siteurllabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "focus": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            $('.siteurllabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("ERROR: Invalid value for Site URL.");
                                            $('.siteurllabel').addClass('settingslabelerror');
                                            validerror();
                                            this.focus();
                                        }
                                    }
                                }
                            },
                            "updateBranch": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Update Branch:",
                                "helpers": ["Monitorr repo branch to use when updating."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" Master", " Develop", " Alpha"],
                                "name": "updateBranch",
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
                                        $('.updatebranchlabel').removeClass('settingslabelerror');
                                        $('.updatebranchlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("ERROR: Invalid value for Update Branch.");
                                            $('.updatebranchlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            },
                            "timezone": {
                                "type": "select",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "fieldClass": "timezone",
                                "label": "Timezone:",
                                "helpers": ["Timezone to use for UI display."],
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [
                                    "(GMT-11:00) Samoa",
                                    "(GMT-10:00) Hawaii",
                                    "(GMT-09:00) Alaska",
                                    "(GMT-08:00) Pacific Time",
                                    "(GMT-07:00) Mountain Time",
                                    "(GMT-07:00) Chihuahua",
                                    "(GMT-07:00) Mazatlan",
                                    "(GMT-06:00) Mexico City",
                                    "(GMT-06:00) Monterrey",
                                    "(GMT-06:00) Saskatchewan",
                                    "(GMT-06:00) Central Time",
                                    "(GMT-05:00) Eastern Time",
                                    "(GMT-05:00) Indiana (East)",
                                    "(GMT-05:00) Bogota",
                                    "(GMT-05:00) Lima",
                                    "(GMT-04:30) Caracas",
                                    "(GMT-04:00) Atlantic Time (Canada)",
                                    "(GMT-04:00) La Paz",
                                    "(GMT-04:00) Santiago",
                                    "(GMT-03:30) Newfoundland",
                                    "(GMT-03:00) Buenos Aires",
                                    "(GMT-03:00) Greenland",
                                    "(GMT-02:00) Stanley",
                                    "(GMT-01:00) Azores",
                                    "(GMT-01:00) Cape Verde Is.",
                                    "(GMT) GMT",
                                    "(GMT) Monrovia",
                                    "(GMT+01:00) Dublin",
                                    "(GMT+01:00) Lisbon",
                                    "(GMT+01:00) London",
                                    "(GMT+01:00) Amsterdam",
                                    "(GMT+01:00) Belgrade",
                                    "(GMT+01:00) Berlin",
                                    "(GMT+01:00) Bratislava",
                                    "(GMT+01:00) Brussels",
                                    "(GMT+01:00) Budapest",
                                    "(GMT+01:00) Copenhagen",
                                    "(GMT+01:00) Ljubljana",
                                    "(GMT+01:00) Madrid",
                                    "(GMT+01:00) Paris",
                                    "(GMT+01:00) Prague",
                                    "(GMT+01:00) Rome",
                                    "(GMT+01:00) Sarajevo",
                                    "(GMT+01:00) Skopje",
                                    "(GMT+01:00) Stockholm",
                                    "(GMT+01:00) Vienna",
                                    "(GMT+01:00) Warsaw",
                                    "(GMT+01:00) Zagreb",
                                    "(GMT+01:00) Casablanca",
                                    "(GMT+02:00) Athens",
                                    "(GMT+02:00) Bucharest",
                                    "(GMT+02:00) Africa/Cairo",
                                    "(GMT+02:00) Harare",
                                    "(GMT+02:00) Helsinki",
                                    "(GMT+02:00) Istanbul",
                                    "(GMT+02:00) Jerusalem",
                                    "(GMT+02:00) Kyiv",
                                    "(GMT+02:00) Minsk",
                                    "(GMT+02:00) Riga",
                                    "(GMT+02:00) Sofia",
                                    "(GMT+02:00) Tallinn",
                                    "(GMT+02:00) Vilniu",
                                    "(GMT+03:00) Baghdad",
                                    "(GMT+03:00) Kuwait",
                                    "(GMT+03:00) Nairobi",
                                    "(GMT+03:00) Riyadh",
                                    "(GMT+03:00) Moscow",
                                    "(GMT+03:30) Tehran",
                                    "(GMT+04:00) Baku",
                                    "(GMT+04:00) Volgograd",
                                    "(GMT+04:00) Muscat",
                                    "(GMT+04:00) Tbilisi",
                                    "(GMT+04:00) Yerevan",
                                    "(GMT+04:30) Kabul",
                                    "(GMT+05:00) Karachi",
                                    "(GMT+05:00) Tashkent",
                                    "(GMT+05:30) Kolkata",
                                    "(GMT+05:45) Kathmandu",
                                    "(GMT+06:00) Ekaterinburg",
                                    "(GMT+06:00) Almaty",
                                    "(GMT+06:00) Dhaka",
                                    "(GMT+07:00) Novosibirsk",
                                    "(GMT+07:00) Bangkok",
                                    "(GMT+07:00) Jakarta",
                                    "(GMT+08:00) Krasnoyarsk",
                                    "(GMT+08:00) Chongqing",
                                    "(GMT+08:00) Hong Kong",
                                    "(GMT+08:00) Kuala Lumpur",
                                    "(GMT+08:00) Perth",
                                    "(GMT+08:00) Singapore",
                                    "(GMT+08:00) Taipei",
                                    "(GMT+08:00) Ulaan Bataar",
                                    "(GMT+08:00) Urumqi",
                                    "(GMT+09:00) Irkutsk",
                                    "(GMT+09:00) Seoul",
                                    "(GMT+09:00) Tokyo",
                                    "(GMT+09:30) Adelaide",
                                    "(GMT+09:30) Darwin",
                                    "(GMT+10:00) Yakutsk",
                                    "(GMT+10:00) Brisbane",
                                    "(GMT+10:00) Canberra",
                                    "(GMT+10:00) Guam",
                                    "(GMT+10:00) Hobart",
                                    "(GMT+10:00) Melbourne",
                                    "(GMT+10:00) Port Moresby",
                                    "(GMT+10:00) Sydney",
                                    "(GMT+11:00) Vladivostok",
                                    "(GMT+12:00) Magadan",
                                    "(GMT+12:00) Auckland",
                                    "(GMT+12:00) Fiji"
                                ],
                                "name": "timezone",
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
                                        $('.timezonelabel').removeClass('settingslabelerror');
                                        $('.timezonelabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("ERROR: Invalid value for Timezone.");
                                            $('.timezonelabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            },
                            "timestandard": {
                                "type": "radio",
                                "validate": true,
                                "showMessages": true,
                                "disabled": false,
                                "hidden": false,
                                "label": "Time Standard:",
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [" True (12h time)", " False (24h time)"],
                                "name": "timestandard",
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
                                        $('.timestandardlabel').removeClass('settingslabelerror');
                                        $('.timestandardlabel').addClass('settingslabelchanged');
                                        settingchange();
                                    },
                                    "ready": function() {
                                        this.refreshValidationState(true);
                                        if (!this.isValid(true)) {
                                            console.log("ERROR: Invalid value for Time Standard.");
                                            $('.timestandardlabel').addClass('settingslabelerror');
                                            validerror();
                                        }
                                    }
                                }
                            },
                            "language": {
                                "type": "text",
                                "validate": false,
                                "showMessages": false,
                                "disabled": false,
                                "hidden": false,
                                "label": "Language:",
                                "helpers": ["Beta"], // BETA - TODO //
                                "hideInitValidationError": false,
                                "focus": false,
                                "optionLabels": [],
                                "name": "language",
                                "placeholder": "EN",
                                "typeahead": {},
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
                                        $('.alpaca-form-button-submit').addClass('buttonchange');
                                    }
                                }
                            },
                        },
                        "form": {
                            "attributes": {
                                "action": "post-settings/post_receiver-user_preferences.php",
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
                                        let preferenceSettings = $('#preferencesettings');
                                        let cssEditor = ace.edit("customCSSEditor");
                                        $.post('post-settings/post_receiver_custom_css.php', {
                                                css: cssEditor.getSession().getValue()
                                            },
                                            function(data) {
                                                console.log(data);
                                            }
                                        );

                                        let JSEditor = ace.edit("customJSEditor");
                                        $.post('post-settings/post_receiver_custom_js.php', {
                                                js: JSEditor.getSession().getValue()
                                            },
                                            function(data) {
                                                console.log(data);
                                            }
                                        );

                                        $.post({
                                            url: 'post-settings/post_receiver-user_preferences.php',
                                            data: preferenceSettings.alpaca().getValue(),
                                            success: function(data) {
                                                console.log("Settings Saved! Applying changes...");
                                                settingapply();
                                                //alert("Settings saved! Applying changes...");
                                                setTimeout(function() {
                                                    window.top.location.reload(true);
                                                }, 3000);
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
                                            },
                                            error: function(errorThrown) {
                                                console.log(errorThrown);
                                                settingserror();
                                                //alert("Error submitting data.");
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
                    "postRender": function(control) {
                        let cssEditor = ace.edit("customCSSEditor");
                        cssEditor.getSession().setMode("ace/mode/css");
                        cssEditor.setTheme("ace/theme/idle_fingers");

                        //load custom css file into the form
                        $.when($.get("../../data/custom.css"))
                            .done(function(response) {
                                cssEditor.getSession().setValue(response);
                                cssEditor.getSession().on('change', function() {
                                    settingchange()
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                });
                            });

                        let jsEditor = ace.edit("customJSEditor");
                        jsEditor.getSession().setMode("ace/mode/javascript");
                        jsEditor.setTheme("ace/theme/idle_fingers");

                        //load custom js file into the form
                        $.when($.get("../../data/custom.js"))
                            .done(function(response) {
                                jsEditor.getSession().setValue(response);
                                jsEditor.getSession().on('change', function() {
                                    settingchange();
                                    $('.alpaca-form-button-submit').addClass('buttonchange');
                                });
                            });
                    }
                });
            });
        </script>

    </div>

</body>

</html>