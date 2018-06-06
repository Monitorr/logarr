<!DOCTYPE html>
<html lang="en">

    <!--
                    LOGARR
        by @seanvree, @wjbeckett, and @jonfinley 
            https://github.com/Monitorr
    -->

    <head>

        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="manifest" href="webmanifest.json">
        
        <meta name="Logarr" content="Logarr: Self-hosted, single-page, log consolidation tool." />

        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link href='//fonts.googleapis.com/css?family=Lato:300,400,900' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="assets/css/logarr.css" />
        <link rel="stylesheet" href="assets/css/custom.css" />

        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="assets/images/favicon/apple-touch-icon-57x57.png" />
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon/apple-touch-icon-114x114.png" />
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon/apple-touch-icon-72x72.png" />
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon/apple-touch-icon-144x144.png" />
        <link rel="apple-touch-icon-precomposed" sizes="60x60" href="assets/images/favicon/apple-touch-icon-60x60.png" />
        <link rel="apple-touch-icon-precomposed" sizes="120x120" href="assets/images/favicon/apple-touch-icon-120x120.png" />
        <link rel="apple-touch-icon-precomposed" sizes="76x76" href="assets/images/favicon/apple-touch-icon-76x76.png" />
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="assets/images/favicon/apple-touch-icon-152x152.png" />
        <link rel="icon" type="image/png" href="assets/images/favicon/favicon-196x196.png" sizes="196x196" />
        <link rel="icon" type="image/png" href="assets/images/favicon/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/png" href="assets/images/favicon/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="assets/images/favicon/favicon-16x16.png" sizes="16x16" />
        <link rel="icon" type="image/png" href="assets/images/favicon/favicon-128.png" sizes="128x128" />
        <meta name="application-name" content="Logarr"/>
        <meta name="msapplication-TileColor" content="#FFFFFF" />
        <meta name="msapplication-TileImage" content="assets/images/favicon/mstile-144x144.png" />
        <meta name="msapplication-square70x70logo" content="assets/images/favicon/mstile-70x70.png" />
        <meta name="msapplication-square150x150logo" content="assets/images/favicon/mstile-150x150.png" />
        <meta name="msapplication-wide310x150logo" content="assets/images/favicon/mstile-310x150.png" />
        <meta name="msapplication-square310x310logo" content="assets/images/favicon/mstile-310x310.png" />
        <meta name="theme-color" content="#252525"/>
        <meta name="theme_color" content="#252525"/>
      
        <meta name="robots" content="NOINDEX, NOFOLLOW">

        <!-- // CHANGE ME - Change to new config file  -->
        
        <?php
            $file = 'assets/config/config.json';

            function convertConfig()
            {
                include(__DIR__ . '/../config/config.php');
                $new_config = json_decode(file_get_contents(__DIR__ . '/../config/config.sample-03jun2018.json'), 1);
                $old_config = array('config' => $config, 'logs' => $logs);
                $json = json_encode(array_merge($new_config, $old_config), JSON_PRETTY_PRINT);
                file_put_contents(__DIR__ . '/../config/config.json', $json);
            }
            if (is_file('assets/config/config.php') && !is_file($file)) convertConfig();

            //Use the function is_file to check if the config file already exists or not.
            if(!is_file($file)){
                copy('assets/config/config.sample-03jun2018.json', $file);
            }
            include ('assets/php/functions.php');
        ?>

        <title><?php echo $preferences['sitetitle']; ?></title>

        <script src="assets/js/jquery.min.js"> </script>

        <script src="assets/js/pace.js" async></script>

        <script src="assets/js/jquery.blockUI.js" async></script>

        <script src="assets/js/jquery.highlight.js" async> </script>

        <script src="assets/js/jquery.mark.min.js" async> </script>

        <script src="assets/js/logarr.main.js"></script>

        <!-- sync config with javascript -->
        <script>
            var settings = <?php echo json_encode($settings);?>;
            var preferences = <?php echo json_encode($preferences);?>;
            function refreshConfig() {
                $.ajax({
                    url: "assets/php/sync-config.php",
                    data: {settings:settings,preferences:preferences},
                    type: "POST",
                    success: function (response) {

                        var json = JSON.parse(response);
                        var settings = json.settings;
                        var preferences = json.preferences;

                        setTimeout(function () {
                            refreshConfig()
                        }, settings.rfconfig); //delay is rftime


                        if(settings.logRefresh != "false" && !$('#buttonStart :checkbox').prop('checked')){
                            console.log('log refresh true');
                            $('#autoRefreshLog').click();
                            $('#buttonStart :checkbox').prop('checked', 'true').change();
                        } else if(settings.logRefresh != "true" && $('#buttonStart :checkbox').prop('checked')) {
                            console.log('log refresh false');
                            $('#autoRefreshLog').click();
                            $('#buttonStart :checkbox').removeProp('checked');
                        }
                        document.title = preferences.sitetitle; //update page title to configured title
                        console.log('Refreshed config variables');
                    }
                });
            }
            refreshConfig();
        </script>

               <!-- Highlight error terms onload:  -->

        <script>
            function highlightjsload() {
                if(settings.autoHighlight == "true"){
                    $.growlUI('Loading logs...');
                    setTimeout(function () {
                        highlightjs();
                    }, 300);
                }
            }
        </script>

                <!-- // Set global timezone from config file: -->
            <?php 
                //Why is this necessary? - rob1998
                if($preferences['timezone'] == "") {

                    date_default_timezone_set('UTC');
                    $timezone = date_default_timezone_get(); 

                }

                else {

                    $timezoneconfig = $preferences['timezone'];
                    date_default_timezone_set($timezoneconfig);
                    $timezone = date_default_timezone_get();

                }
            ?>

        <script>
            <?php
                //initial values for clock:
                //$timezone = $preferences['timezone'];
                $dt = new DateTime("now", new DateTimeZone("$timezone"));
                $timeStandard = (int) ($preferences['timestandard']);
                $rftime = $settings['rftime'];
                $timezone_suffix = '';
                if(!$timeStandard){
                    $dateTime = new DateTime();
                    $dateTime->setTimeZone(new DateTimeZone($timezone));
                    $timezone_suffix = $dateTime->format('T');
                }
                $serverTime = $dt->format("D d M Y H:i:s");
            ?>
            var servertime = "<?php echo $serverTime;?>";
            var timeStandard = <?php echo $timeStandard;?>;
            var timeZone = "<?php echo $timezone_suffix;?>";
            var rftime = <?php echo $settings['rftime'];?>;
            function updateTime() {
                setInterval(function() {
                    var timeString = date.toLocaleString('en-US', {hour12: timeStandard, weekday: 'short', year: 'numeric', day: '2-digit', month: 'short', hour:'2-digit', minute:'2-digit', second:'2-digit'}).toString();
                    var res = timeString.split(",");
                    var time = res[3];
                    var dateString = res[0]+'&nbsp; | &nbsp;'+res[1].split(" ")[2]+" "+res[1].split(" ")[1]+'<br>'+res[2];
                    var data = '<div class="dtg">' + time + ' ' + timeZone + '</div>';
                    data+= '<div id="line">__________</div>';
                    data+= '<div class="date">' + dateString + '</div>';
                    $("#timer").html(data);
                }, 1000);
            }
            function syncServerTime() {
                $.ajax({
                    url: "assets/php/time.php",
                    type: "GET",
                    success: function (response) {
                        var response = $.parseJSON(response);
                        servertime = response.serverTime;
                        timeStandard = parseInt(response.timeStandard);
                        timeZone = response.timezoneSuffix;
                        rftime = response.rftime;
                        date = new Date(servertime);
                        setTimeout(function() {syncServerTime()}, settings.rftime); //delay is rftime
                        console.log('Logarr time update START');
                    }
                });
            }
            $(document).ready(function() {
                setTimeout(syncServerTime(), settings.rftime); //delay is rftime
                updateTime();
            });
        </script>

        <script src="assets/js/clock.js" async></script>

        <script>
            var nIntervId;
            var onload;
            var logInterval = false;
            $(document).ready(function () {
                $('#buttonStart :checkbox').change(function () {
                    console.log(settings.logRefresh);
                    if ($(this).is(':checked') && logInterval == false) {
                        nIntervId = setInterval(refreshblockUI, settings.rflog);
                        logInterval = true;
                    } else {
                        clearInterval(nIntervId);
                        logInterval = false;
                    }
                });
            });
        </script>


             <!-- LOG UNLINK FUNCTION  -->
        <script>
            $(document).on('click', 'button[data-action=\'unlink-log\']', function(event) {
                event.preventDefault(); // stop being refreshed
                console.log('Attempting log roll');
                $.growlUI("Attempting <br> log roll");
                var logName = $(this).data('service');
                $.ajax({
                    type: 'POST',
                    url: 'assets/php/unlink.php',
                    processData: false,
                    data: "file=" + $(".path[data-service='" + $(this).data('service') + "']").html().trim(),
                    success: function (data) {
                        $('#modalContent').html(data);
                        setTimeout(refreshblockUI(), 1000);
                        console.log('Logarr unlink '+ data);
                        var modal = document.getElementById('responseModal');
                        var span = document.getElementsByClassName("closemodal")[0];
                        modal.style.display = "block";
                        span.onclick = function() {
                            modal.style.display = "none";
                        };
                        window.onclick = function(event) {
                            if (event.target == modal) {
                                modal.style.display = "none";
                            }
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert( "Posting failed (ajax)" );
                        console.log("Posting failed (ajax)");
                    }
                });
                return false;
            });
        </script>

            <!-- LOG DOWNLOAD FUNCTION  -->
        <script>
            $(document).on('click', 'button[data-action=\'download-log\']', function(event) {
                event.preventDefault(); // using this page stop being refreshing
                var logFilePath = ($(".path[data-service='" + $(this).data('service') + "']").html()).replace('file=','').trim();
                console.log(logFilePath);
                window.open('assets/php/download.php?file='+logFilePath);
                return false;
            });
        </script>

             <!-- Execute search on ENTER keyup:  -->
        <script>

            $(document).ready(function () {
                $("#text-search2").keyup(function(event) {
                    if (event.keyCode === 13) {
                        $("#marksearch").click();
                    }
                });
            });

        </script>


    </head>
    
    <body id="body" style="color: #FFFFFF;" onload="highlightjsload()">
        <div id="ajaxtimestamp" title="Analog clock timeout. Refresh page."></div>
        <div id="ajaxmarquee" title="Offline marquee timeout. Refresh page."></div>


        <div class="header">
        
            <div id="left" class="Column">

                <div id="clock">
                    <canvas id="canvas" width="120" height="120"></canvas>
                    <div class="dtg" id="timer"></div>
                </div>

            </div>

            <div id="logo" class="Column">
                 <img src="assets/images/log-icon.png" alt="Logarr" style="height:8em;border:0;" title="Reload Logarr" onclick="window.location.reload(true);">
            </div>

            <div id="right" class="Column"> 

                <div id="righttop" class="righttop">
                    <div id="markform">
                        <input type="search" name="markinput"  id="text-search2" class="input" title="Input search term" placeholder=" Search & highlight . . .">
                        <input type="button" name="marksearch"  id="marksearch" value="Search" class="btn marksearch btn-primary" title="Execute search. Results will be highlighted in yellow.">
                        <button data-search="next" name="nextBtn" class="btn search-button btn-primary btn-visible btn-hidden" title="Focus to first search result">&darr;</button>
                        <button data-search="prev" name="prevBtn" class="btn search-button btn-primary btn-visible btn-hidden" title="Focus to last search result" >&uarr;</button>
                        <button data-search="clear" class="btn search-button btn-primary" title="Clear search results">✖</button>
                        
                    </div>
                </div>
                
                <div id="rightmiddle" class="rightmiddle">

                     <div id="count" class="count" title="Search results have been highlighted in yellow. NOTE: Search results will be cleared if a log update is triggered."> </div>

                </div>

                <div id="rightbottom" class="rightbottom">
                    
                    <table id="slidertable">
                        <tr title="Enable log auto-update | Interval: <?php echo $settings['rflog']; ?> ms ">

                            <th id="textslider">
                                Auto Update:
                            </th>

                            <th id="slider">
                                <label class="switch" id="buttonStart">
                                    <input id="autoRefreshLog" type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </th>

                            <th>
                                <input id="Update" type="button" name="updateBtn" class="button2 btn btn-primary" value="Update" title="Trigger log manual update" onclick="refreshblockUI(); return false" />
                            </th>

                        </tr>
                    </table>

                </div>

            </div>
            
        </div>

        <div id="logcontainer">

            <div id="logwrapper" class="flex">
            <?php
            foreach ($logs as $log) {
                if($log['enabled'] == "Yes"){?>

                <div id="<?php echo str_replace(" ", "-", $log['logTitle']); ?>-log-container" class="flex-child">

                    <div class="row2">

                        <div id="filedate" class="left">
                            <br>
                            <?php echo "Last modified: " . date (" H:i | D, d M", filemtime($log['path'])); $log['path']; ?>
                        </div>

                        <div class="logheader">
                            <strong><?php echo $log['logTitle']; ?>:</strong>
                        </div>

                        <div id="filepath"  class="right">
                            <div class="filesize">
                                Log file size: <?php echo human_filesize(filesize($log['path'])); ?>
                            </div>
                            <div class="path" data-service="<?php echo $log['logTitle'];?>">
                                <?php echo $log['path']; ?>
                            </div>
                        </div>

                    </div>

                    <div class="slide">
                        <input class="expandtoggle" type="checkbox" name="slidebox" id="<?php echo $log['logTitle']; ?>" checked>
                        <label for="<?php echo $log['logTitle']; ?>" class="expandtoggle" title="Increase/decrease log view"></label>

                        <div id="expand" class="expand">
                            <p id="<?php echo $log['logTitle']; ?>-log"><?php readExternalLog($log); ?></p>
                        </div>

                    </div>

                    <table id="slidebottom">
                        <tr>
                            <td id="unlinkform">
                                <button type="button" class="log-action-button slidebutton btn btn-primary" data-action="unlink-log" data-service="<?php echo $log['logTitle'];?>"  title="Attempt log file roll. NOTE: This function will copy the current log file to '[logfilename].bak', delete the original log file, and create a new blank log file with the orginal log filename. This function may not succeed if log file is in use.">Roll Log</button>
                            </td>
                            <td id="downloadform">
                                <button type="button" class="log-action-button slidebutton btn btn-primary" data-action="download-log" data-service="<?php echo $log['logTitle'];?>" title="Download full log file">Download</button>
                            </td>
                        </tr>
                    </table>

                </div>

            <?php
                }
            } ?>
            </div>
        </div>

            <!-- Unlink response modal: -->

        <div id='responseModal'>

            <span class="closemodal"  aria-hidden="true" title="Close">&times;</span>

            <div id='modalContent'></div>
        
        </div>

        <button onclick="topFunction(), checkAll1()" id="myBtn" title="Go to top"></button>
        
        <div id="footer">

            <script src="assets/js/update_auto.js" async></script>
            
            <script src="assets/js/update.js" async></script>

            <div id="logarrid">
                <a href="https://github.com/monitorr/logarr" title="Logarr GitHub repo" target="_blank" class="footer">Logarr </a> |
                <a href="settings.php" title="Logarr Settings" target="_blank" class="footer">Settings</a> |
                <a href="https://github.com/Monitorr/logarr/releases" title="Logarr releases" target="_blank" class="footer"> Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?></a>
                <br>
            </div>

            <div id="version">
                <a id="version_check" title="Check and execute update" style="cursor: pointer">Check for Update</a>
                <br>
            </div>

            <div id="version_check_auto"></div>

        </div>

        <!-- scroll to top   -->

        <script>
                 
                // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function() {scrollFunction()};

            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    document.getElementById("myBtn").style.display = "block";
                } else {
                    document.getElementById("myBtn").style.display = "none";
                }
            }

                // When the user clicks on the button, scroll to the top of the document
            function topFunction() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }

        </script>

        <script>

            function checkedAll(isChecked) {
                var c = document.getElementsByName('slidebox');

                for (var i = 0; i < c.length; i++) {
                    if (c[i].type == 'checkbox') {
                        c[i].checked = isChecked;
                    }
                }
            }

           function checkAll1() {
                checkedAll(true);
           }

        </script>

    </body>
    
</html>
