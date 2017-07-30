<?php
function readExternalLog($filename){
    $log = file($filename);
    $log = array_reverse($log);
    foreach($log as $line){
        echo $line.'<br/>';
    }
}
//Add Logs Here - APPEARS IN ORDER OF LIST
$logs = array(
    "Sonarr" => '/home/USER/.config/NzbDrone/logs/sonarr.txt',
    "Radarr" => '/home/USER/.config/Radarr/logs/radarr.txt',
    "Headphones" => "/home/plex/logs/headphones/headphones.log",
    "NZBGet" => "/home/USER/logs/nzbget/nzbget.log",
    "MP4 Converter" => "/home/USER/bin/sickbeard_mp4_automator/info.log",
    "NZBHydra" => "/home/USER/logs/nzbhydra/nzbhydra.log",
);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Logarr</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
		<style type="text/css">
		.auto-style1 {
			color: #3F51B5;
			font-family: "Segoe UI";
		}
		</style>
	</head>
	<body style="color: #FFFFFF; background-color: #252525">
		<?php foreach($logs as $k => $v){ ?>
		<div class="w3-container w3-center">
			<h3><span class="w3-text-indigo"><strong><?php echo $k; ?>:</strong></span></h3>
		</div>
		<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
			<p><?php readExternalLog($v); ?></p>
		</div>
		<?php } ?>
	</body>
</html>
