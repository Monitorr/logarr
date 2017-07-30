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


<div class="w3-container w3-center">
  <h3><span class="w3-text-indigo"><strong>Radarr:</strong></span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
 <p> <?php
        $filename=  'C:\ProgramData\Radarr\logs\radarr.txt';
  //          $file_ptr     = fopen ( $filename, "r" );
  //          $file_size    = filesize ( $filename );
  //          $text         = fread ( $file_ptr, $file_size );
  //          fclose ( $file_ptr );
  //          echo nl2br ( $text );

              if( $v = @fopen('C:\ProgramData\Radarr\logs\radarr.txt', "r") ){ //open the file
    fseek($v, 0, SEEK_END); //move cursor to the end of the file
    /* help functions: */
    //moves cursor one step back if can - returns true, if can't - returns false
    function moveOneStepBack( &$f ){ 
      if( ftell($f) > 0 ){ fseek($f, -1, SEEK_CUR); return true; }
        else return false;
    }
    //reads $length chars but moves cursor back where it was before reading 
    function readNotSeek( &$f, $length ){ 
      $r = fread($f, $length);
      fseek($f, -$length, SEEK_CUR);
      return $r;  
    }

    /* THE READING+PRINTING ITSELF: */
    while( ftell($v) > 0 ){ //while there is at least 1 character to read
      $newLine = false;
      $charCounter = 0;

      //line counting
      while( !$newLine && moveOneStepBack( $v ) ){ //not start of a line / the file
        if( readNotSeek($v, 1) == "r" ) $newLine = true;
        $charCounter++;              
      } 

      //line reading / printing
      if( $charCounter>1 ){ //if there was anything on the line
        if( !$newLine ) echo "r"; //prints missing "\n" before last *printed* line            
        echo readNotSeek( $v, $charCounter ); //prints current line  
      }   

    }
    fclose( $v ); //close the file, because we are well-behaved
  }
        ?></p>
</div>

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
  <h3><span class="w3-text-indigo"><strong>SABNzbd:</strong></span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = 'C:\sabnzbd\logs\sabnzbd.log';
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>


<div class="w3-container w3-center">
  <h3><span class="w3-text-indigo"><strong>NZBHydra:</strong></span></h3>
</div>
<div class="w3-container w3-border w3-margin" style="background-color:#404040; word-wrap: break-word; width:auto; height:200px; overflow-y: scroll;">
  <p><?php
            $filename     = 'C:\logs\nzbhydra\nzbhydra.log';
            $file_ptr     = fopen ( $filename, "r" );
            $file_size    = filesize ( $filename );
            $text         = fread ( $file_ptr, $file_size );
            fclose ( $file_ptr );
            echo nl2br ( $text );
        ?></p>
</div>


</body>

</html>