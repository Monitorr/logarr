<?php
    function readExternalLog($filename)
    {
        $log = file($filename);
        $log = array_reverse($log);
        foreach ($log as $line) {
            echo $line.'<br/>';
        }
    }
    include 'assets/config/config.php';
    /*http://jeffreysambells.com/2012/10/25/human-readable-filesize-php*/
    function human_filesize($bytes, $decimals = 2)
    {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
?>

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

        <title><?php echo $config['title']; ?></title>
        <meta name="Lodarr" content="Logarr: Self-hosted, single-page, log consolidation tool." />


        <link rel="stylesheet" href="assets/css/bootstrap.css" />
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
        
        <!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/> -->
        <!-- <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400" /> -->
       
        <meta name="robots" content="NOINDEX, NOFOLLOW">
    
        <script src="assets/js/pace.js" async></script>

        <script src="assets/js/jquery.min.js"> </script>

        <!-- <script src="assets/js/jquery.highlight.js"> </script> -->
        
        <script type= "text/javascript">
            $(document).ready(function() {
                function update() {
                    $.ajax({
                        type: 'POST',
                        url: 'assets/config/timestamp.php',
                        success: function(data) {
                            $("#timer").html(data); 
                            window.setTimeout(update, 10000);
                            }
                    });
                }
                update();
            });

        </script>
        
        <script src="assets/js/hilitor.js" async></script>

        <script type="text/javascript">

            var myHilitor; // global variable
            document.addEventListener("DOMContentLoaded", function(e) {
            myHilitor = new Hilitor("content");
            myHilitor.apply("error");
            }, false);

        </script>


        <!-- REFRESH DIV ONLY - NOT WORKING -->

            <!-- 
            <script type="text/javascript">
                $(document).ready(function() {
                    function refreshDiv() {
                        document.getElementById("filesize").innerHTML = document.getElementById("filesize").innerHTML;
                        window.setTimeout(refreshDiv, 3000);
                    }
                });
            </script>
            -->

    </head>
    
    <body id="body" body style="border: 10px solid #252525; color: #FFFFFF; background-color: #252525;">
    
        <div class="Row">
        
            <div id="timer" class="Column"></div>

            <div id="logo" class="Column">
                <A HREF="javascript:history.go(0)">
				<img src="assets/images/log-icon.png" height="125px" />
				</A>
            </div>

            <div id="search" class="Column">                
                <form id="searchForm" method="post" action="" onsubmit="count(); blockUI(); return false;">
                    <input name="text-search" id="text-search" type="text" value="" class="input" placeholder="search & highlight">
                    <input id="submit" type="submit" value="Submit" class="button" />
                </form>
                    <div id="count" class="count"> </div>
            </div>
        </div>
        
           <?php foreach ($logs as $k => $v) { ?>
                <div class="row2">
                    <div id="filepath" class="left">
                        <strong><?php echo $v; ?></strong>
                    </div>

                    <div id="header" class="w3-container w3-center">
                        <h3><span class="header"><strong><?php echo $k; ?>:</strong></span></h3>
                    </div>
                                
                    <div id="filesize"  class="right">
                        Log File Size: <strong> <?php echo human_filesize(filesize($v)); ?></strong>
                    </div>
                </div>
                            
                <div class="slide">
                        <input class="toggle" type="checkbox" id="<?php echo $k; ?>" checked>
                        <label for="<?php echo $k; ?>"></label>
                        <div id="expand" class="expand">
                                <p><?php readExternalLog($v); ?></p>
                        </div>
                </div>

            <?php } ?>

        <script src="assets/js/jquery.blockUI.js"></script>

        <script src="assets/js/jquery_search.js"></script>

        <div class="footer">
      
            <a href="https://github.com/monitorr/logarr" target="_blank"> Repo: Logarr // Version: <?php echo file_get_contents( "assets/js/version/version.txt" );?> </a>

        </div>

    </body>
    
</html>
