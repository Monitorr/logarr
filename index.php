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

        <?php $file = 'assets/config/config.php';
            //Use the function is_file to check if the config file already exists or not.
            if(!is_file($file)){
                copy('assets/config/config.sample-12feb18.php', $file);
            } 
        ?>

        <?php include ('assets/config/config.php'); ?>

        <title><?php echo $config['title']; ?></title>

        <script src="assets/js/jquery.min.js"> </script>

        <script src="assets/js/pace.js" async></script>

        <script>
            <?php
            $timezone = $config['timezone'];
            $dt = new DateTime("now", new DateTimeZone("$timezone"));
            $timeStandard = (int) ($config['timestandard']);
            ?>
            var servertime = "<?php echo $dt->format("D M d Y H:i:s"); ?>";
            var timeStandard = <?php echo $timeStandard; ?>;
            var servertimezone = "<?php echo $timezone; ?>";
            function updateTime() {
                var timeString = date.toLocaleString('en-US', {hour12: timeStandard, weekday: 'short', year: 'numeric', month: 'long', day: 'numeric', hour:'2-digit', minute:'2-digit', second:'2-digit'}).toString();
                var res = timeString.split(",");
                var time = res[3];
                var dateString = res[0]+' | '+res[1].split(" ")[2]+" "+res[1].split(" ")[1]+','+res[2];
                var data = '<div class="dtg">' + time + '</div>';
                data+= '<div id="line">__________</div>';
                data+= '<div class="date">' + dateString + '</div>';
                $("#timer").html(data);
                window.setTimeout(updateTime, 1000);
            }
            $(document).ready(function() {

                updateTime();
            });
        </script>

        <script src="assets/js/clock.js" async></script>

        <script src="assets/js/hilitor.js" async></script>

        <script>

            var myHilitor; // global variable
            document.addEventListener("DOMContentLoaded", function(e) {
            myHilitor = new Hilitor("content");
            myHilitor.apply("error");
            }, false);

        </script>

        <script>

            var nIntervId;
            var onload;

             $(document).ready(function () {
                $('#buttonStart :checkbox').change(function () {
                    if ($(this).is(':checked')) {
                        nIntervId = setInterval(refreshblockUI, <?php echo $config['rflog']; ?>);
                    } else {
                        clearInterval(nIntervId);
                    }
                });
                // Uncomment line below to set auto-refresh to ENABLE on page load
                // $('#buttonStart :checkbox').attr('checked', 'checked').change();
            });  

        </script>

        <script src="assets/js/logarr.main.js"></script>

        <script>
            $(document).ready(function () {
                $(".forms").submit(function(event){

                    event.preventDefault(); // using this page stop being refreshing

                    var logName = $(this).attr('id');;

                    $.ajax({
                        type: 'POST',
                        url: 'assets/php/unlink.php',
                        processData: false,
                        data: $(this).serialize(),
                        success: function (data) {
                            $('#response').html(data);
                            $('#'+logName+'-log').html(''); //empty the log on screen
                            console.log('Logarr unlink '+ data);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert( "Posting failed (ajax)" );
                            console.log("Posting failed (ajax)");
                        }
                    });
                    return false;
                });
            });
        </script>

    </head>
    
    <body id="body" style="border: 10px solid #252525; color: #FFFFFF;">

        <?php

            function readExternalLog($filename, $maxLines)
            {
                ini_set("auto_detect_line_endings", true);
                $log = file($filename);
                $log = array_reverse($log);
                $lines = $log;

                foreach ($lines as $line_num => $line) {
                    echo "<b>Line {$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
                    if($line_num == $maxLines) break;
                }
                
            }

            function human_filesize($bytes, $decimals = 2)
            {
                $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
                $factor = floor((strlen($bytes) - 1) / 3);
                return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
            }

        ?>

        <div id="ajaxtimestamp" title="Analog clock timeout. Refresh page."></div>
        <div id="ajaxmarquee" title="Offline marquee timeout. Refresh page."></div>

        <div class="header">
        
            <div id="left" class="Column">

                <div id="left" class="Column">
                    <div id="clock">
                        <canvas id="canvas" width="120" height="120"></canvas>
                        <div class="dtg" id="timer"></div>
                    </div>
                </div>
                
                <div id="leftbottom" class="leftbottom">
                    
                    <table id="slidertable">
                        <tr>
                            <th id="textslider">
                            Auto Update:
                            </th>
                            <th id="slider">
                                <label class="switch" id="buttonStart">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </th>
                        </tr>
                    </table>
                        
                    <input id="Update" class="button2" type="button" value="Update" onclick="refreshblockUI();  return false" />

                </div>

            </div>

            <div id="logo" class="Column">

                <a href="javascript:history.go(0)"> 
                    <img src="assets/images/log-icon.png" alt="Logarr" style="height:8em;border:0;">
                </a>

            </div>

            <div id="right" class="Column"> 

                <div id="righttop" class="righttop">
                    
                    <div id="count" class="count"> </div>

                </div>
                
                <div id="rightmiddle" class="rightmiddle">
                    <form id="searchForm" method="post" action="index.php" onsubmit="searchblockUI(); return false;">
                        <input name="text-search" id="text-search" type="search" value="" class="input" placeholder="search & highlight...">
                        <input id="submit" type="submit" value="Submit" class="button" />
                    </form>
                </div>

            </div>
            
        </div>

        <div id="logcontainer">

            <div id="logwrapper" class="flex">

                <?php foreach ($logs as $k => $v) { ?>

                    <div id="logs" class="flex-child">

                        <div class="row2">
                    
                            <div id="filedate" class="left">
                                <br>
                                <?php echo "Last modified: " . date (" H:i", filemtime($v))."L |" . date ( " D, d M", filemtime($v)); $v; ?>
                            </div>

                            <div class="logheader">
                                <strong><?php echo $k; ?>:</strong>
                            </div>

                            <div id="filepath"  class="right">
                                <div class="filesize">
                                    Log File Size: <?php echo human_filesize(filesize($v)); ?>
                                </div>
                                <div class="path">
                                    <?php echo $v; ?>
                                </div>
                            </div>

                        </div>

                        <div class="slide">
                            <input class="expandtoggle" type="checkbox" name="slidebox" id="<?php echo $k; ?>" checked>
                            <label for="<?php echo $k; ?>" class="expandtoggle"></label>

                                <div id="expand" class="expand">
                                    <p id="<?php echo $k; ?>-log"><?php readExternalLog($v, $config['max-lines']); ?></p>
                                </div>

                        </div>

                        <!-- <form id="<?php echo $k; ?>"> -->
                        <form id="<?php echo $k; ?>" class="forms">
                            <!-- <input name="file" value=" unlink file " required> -->
                            <input name="file" type="text" value="<?php echo $v; ?>" required readonly />
                            <!-- <input name="file" type="text" value=" unlink " required> WORKS -->
                                <br>
                            <input name="submit" type="submit" class="btn btn-primary" value="Unlink" />
                        </form>

                    </div>
                        
                <?php } ?>

            </div>
                
        </div>

        <div id='response'></div>

        
        <button onclick="topFunction(), checkAll1()" id="myBtn" title="Go to top"></button>
        
        <div class="footer">

            <script src="assets/js/update_auto.js" async></script>
            
            <script src="assets/js/update.js" async></script>

            <a href="https://github.com/monitorr/logarr" target="_blank">Repo: Logarr </a> |
            <a href="https://github.com/Monitorr/logarr/releases" target="_blank"> Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?></a>
                 <br>
            <a class="footer" id="version_check" style="cursor: pointer">Check for Update</a>
                <br>

            <div id="version_check_auto"></div>

        </div>

        <script src="assets/js/jquery.blockUI.js"></script>

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
            };
            
        </script>

    </body>
    
</html>
