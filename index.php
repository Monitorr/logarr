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
    "Headphones" => 'C:\logs\headphones\headphones.log',
);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>YOUR LOGs</title>
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
			border: 2px solid black;
		}
  
		#slide-body{  
			overflow: auto;
			transition:     height 500ms ease;
				-moz-transition:    height 500ms ease;
				-ms-transition:     height 500ms ease;
				-o-transition:      height 500ms ease;
				-webkit-transition: height 500ms ease;
		}
		.expanded {
			height: 600px !important;
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
	
	<body style="border: 10px solid #252525; color: #FFFFFF; background-color: #252525;">
		<?php foreach($logs as $k => $v){ ?>
		<div class="w3-container w3-center">
			<h3><span class="w3-text-indigo"><strong><?php echo $k; ?>:</strong></span></h3>
		</div>
		
		<div id="slide">
			<div id="slide-body" style="background-color: #404040; word-wrap: break-word; width: auto; height: 200px; overflow-y: scroll;">
			<p><?php readExternalLog($v); ?></p>
			</div>
			<div id="more">more...</div>
		</div>

		<?php } ?>

		
	</body>
	
</html>
