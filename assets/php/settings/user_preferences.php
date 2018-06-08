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
                            success: function (data) {
                                console.log(data);
                            },

                            error: function(errorThrown){
                                console.log(errorThrown);
                                document.getElementById("response").innerHTML = "GET failed (ajax)";
                                alert( "GET failed (ajax)" );
                            },
                        });

                        //Alpaca.registerConnectorClass("custom", CustomConnector);

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
                                        "validate": false,
                                        "showMessages": false,
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
                                        "placeholder": "Monitorr",
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
                                    },
                                    "siteurl": {
                                        "type": "url",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
                                        "label": "Site URL:",
                                        "helpers": ["URL of the Monitorr UI."],
                                        "hideInitValidationError": false,
                                        "focus": false,
                                        "optionLabels": [],
                                        "name": "siteurl",
                                        "placeholder": "http://localhost/monitorr",
                                        "typeahead": {},
                                        "allowOptionalEmpty": false,
                                        "data": {},
                                        "autocomplete": "false",
                                        "disallowEmptySpaces": true,
                                        "disallowOnlyEmptySpaces": false,
                                        "allowIntranet": true,
                                        "fields": {},
                                        "events": {
                                            "change": function() {
                                                $('.alpaca-form-button-submit').addClass('buttonchange');
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
                                        "optionLabels": [" Master", " Develop"],
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
                                            }
                                        }
                                    },
                                    "timezone": {
                                        "type": "select",
                                        "validate": true,
                                        "showMessages": true,
                                        "disabled": false,
                                        "hidden": false,
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
                                            "(GMT) Casablanca",
                                            "(GMT) Dublin",
                                            "(GMT) Lisbon",
                                            "(GMT) London",
                                            "(GMT) Monrovia",
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
                                        "helpers": ["Beta"], // BETA - CHANGE ME //
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
                                            "value": "submit",
                                            click: function(){
                                                let preferenceSettings = $('#preferencesettings');
                                                var data = preferenceSettings.alpaca().getValue();
                                                $.post({
                                                    url: 'post-settings/post_receiver-user_preferences.php',
                                                    data: preferenceSettings.alpaca().getValue(),
                                                    success: function(data) {
                                                        alert("Settings saved! Applying changes...");
                                                        setTimeout(function () { window.top.location.reload(true); }, 3000);
                                                    },
                                                    error: function(errorThrown){
                                                        console.log(errorThrown);
                                                        alert("Error submitting data.");
                                                    }
                                                });

                                                $.post('post-settings/post_receiver_custom_css.php', {
                                                    css: cssEditor.getSession().getValue()
                                                    }, 
                                                    function(data) {}
                                                );
                                                $('.alpaca-form-button-submit').removeClass('buttonchange');
                                            }
                                        },
                                        "reset":{
                                            "label": "Clear Values"
                                        }
                                    },
                                }
                            },
                            "postRender": function(control) {
                                const cssEditor = ace.edit("customCSSEditor");
                                cssEditor.getSession().setMode("ace/mode/css");
                                cssEditor.setTheme("ace/theme/idle_fingers");

                                //load the custom css file into the form
                                $.when($.get("../../css/custom.css"))
                                .done(function(response) {
                                    cssEditor.getSession().setValue(response);
                                });
                            }
                        });
                    });
                </script>

        </div>

    </body>

</html>