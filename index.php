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
    "Sonarr" => '/home/plex/.config/NzbDrone/logs/sonarr.txt',
    "Radarr" => '/home/plex/.config/Radarr/logs/radarr.txt',
    "Headphones" => "/home/plex/logs/headphones/headphones.log",
    /*"NZBGet" => "/home/plex/logs/nzbget/nzbget.log",*/
    "MP4 Converter" => "/home/plex/bin/sickbeard_mp4_automator/info.log",
    "NZBHydra" => "/home/plex/logs/nzbhydra/nzbhydra.log",
);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Logarr</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
		<style type="text/css">
			body::-webkit-scrollbar {
					width: 10px;
					background-color: white;
			}
			body::-webkit-scrollbar-track {
					-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
					border-radius: 10px;
					background-color: #252525;
			}
			body::-webkit-scrollbar-thumb {
					border-radius: 10px;
					-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
					background-color: #8E8B8B;
			}
			#slide {
					order: 1px solid black;
			}
			#slide-body{
					height: 200px;
					margin: 5px 20px;
					border: 1px;
					padding: 5px 15px;
					overflow: auto;
					font-size: small;
					transition:     height 300ms ease;
							-moz-transition:    height 300ms ease;
							-ms-transition:     height 300ms ease;
							-o-transition:      height 300ms ease;
							-webkit-transition: height 300ms ease;
			}
			.expanded {
					height: 500px !important;
			}
			#more {
					cursor: pointer;
					text-align: right;
					font-size: small;
					margin: 5px 20px;
			}
		</style>
		<script type='text/javascript'>//<![CDATA[
			window.onload=function(){
			document.getElementById( 'slide' ).addEventListener( 'click', function() {
				var body = document.getElementById( 'slide-body' );
				if( body.className == 'expanded' ) {
					body.className = '';
					document.getElementById( 'more' ).textContent = 'more...';
				} else {
					body.className = 'expanded';
					document.getElementById( 'more' ).textContent = 'less...';
				};
			} );
			}//]]> 
		</script>
	</head>
	<body style="color: #FFFFFF; background-color: #252525">
		<?php foreach($logs as $k => $v){ ?>
		<div class="w3-container w3-center">
			<h3><span class="w3-text-indigo"><strong><?php echo $k; ?>:</strong></span></h3>
		</div>		
		<div id="slide">
			<div id="slide-body" style="background-color: #404040; word-wrap: break-word; width: auto; overflow-y: scroll;">
			<p><?php readExternalLog($v); ?></p>
			</div>
			<div id="more">more...</div>
		</div>
		<?php } ?>
	</body>
</html>
