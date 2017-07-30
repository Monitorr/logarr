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
);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>PAGE TITLE</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
		<style type="text/css">
		.auto-style1 {
			color: #3F51B5;
			font-family: "Segoe UI";
		}
		    #slide {
			border: 1px solid black;
		}
		#slide-body{
			height: 0px;    
			overflow: hidden;
			transition:     height 500ms ease;
				-moz-transition:    height 500ms ease;
				-ms-transition:     height 500ms ease;
				-o-transition:      height 500ms ease;
				-webkit-transition: height 500ms ease;
		}
		.expanded {
			height: 800px !important;
		}
		#more {    
			cursor: pointer;
			text-align: right;
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
			<div id="slide-body" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
			<p><?php readExternalLog($v); ?></p>
			</div>
			<div id="more">more...</div>
		</div>

		<?php } ?>

		
	</body>
	
</html>
