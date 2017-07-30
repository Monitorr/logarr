<?php
function readExternalLog($filename){
    $log = file($filename);
    $log = array_reverse($log);
    foreach($log as $line){
        echo $line.'<br/>';
    }
}
//Add Logs Here
$logs = array(
    "Radarr" => 'C:\ProgramData\Radarr\logs\radarr.txt',
    "MP4 Converter" => 'C:\sickbeard_mp4_automator\info.log',
    "Headphones" => 'C:\headphones\logs\headphones.log',
    "Sonarr" => 'C:\ProgramData\NzbDrone\logs\sonarr.txt',
    "SABNZBd" => 'C:\sabnzbd\logs\sabnzbd.log',
    "NZBHydra" => 'C:\logs\nzbhydra\nzbhydra.log',
);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Vree's PLEX Logs</title>
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