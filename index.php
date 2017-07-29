<!DOCTYPE html>
<html>

<head>
<title>CHANGEME</title>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"/>
<style type="text/css">
.auto-style1 {
	color: #3F51B5;
	font-family: "Segoe UI";
}
</style>
</head>

<body style="color: #FFFFFF; background-color: #252525">

<div class="w3-container w3-center">
  <h3 class="auto-style1"><strong>MP4 Converter:</strong></h3>
</div>

<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p> <?php
            $filename     = "C:\sickbeard_mp4_automator\info.log";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>

<div class="w3-container w3-center">
  <h3><span class="w3-text-indigo"><strong>Headphones</strong>:</span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = "C:\headphones\logs\headphones.log";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>

<div class="w3-container w3-center">
  <h3><span class="w3-text-indigo"><strong>Sonarr:</strong></span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = 'C:\ProgramData\NzbDrone\logs\sonarr.txt';
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>

<div class="w3-container w3-center">
  <h3><span class="w3-text-indigo"><strong>Radarr:</strong></span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = 'C:\logs\radarr\radarr.txt';
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>

<div class="w3-container w3-center">
  <h3><span class="w3-text-indigo"><strong>SABNzbd:</strong></span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = "C:\sabnzbd\logs\sabnzbd.log";
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>

</body>

</html>