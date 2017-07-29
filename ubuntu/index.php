<!DOCTYPE html>
<html>
<title>Logarr</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>
<body style="color: #FFFFFF; background-color: #252525">
<div class="w3-container w3-center">
  <h3>MP4 Converter:</h3>
</div>

<div class="w3-container w3-border w3-margin" style="word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p> <?php
            $filename     = "/home/plex/bin/sickbeard_mp4_automator/info.log";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>
<div class="w3-container w3-center">
  <h3>Headphones:</h3>
</div>
<div class="w3-container w3-border w3-margin" style="word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = "/opt/headphones/logs/headphones.log";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>
<div class="w3-container w3-center">
  <h3>Sonarr:</h3>
</div>
<div class="w3-container w3-border w3-margin" style="word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = "/home/plex/.config/NzbDrone/logs/sonarr.txt";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>
<div class="w3-container w3-center">
  <h3>Radarr:</h3>
</div>
<div class="w3-container w3-border w3-margin" style="word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = '/home/plex/.config/Radarr/logs/radarr.txt';
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>
<div class="w3-container w3-center">
  <h3>NZBGET:</h3>
</div>
<div class="w3-container w3-border w3-margin" style="word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = "/mnt/hdd/downloads/nzbget.log";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>
</body>
</html>