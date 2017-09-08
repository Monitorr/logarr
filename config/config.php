<?php // config style adapted from @causeFX and this website: https://www.abeautifulsite.net/a-better-way-to-write-config-files-in-php 
$logs = array(

// ** Log paths are CASE SENSITIVE in a windows enviroment **
// Ensure correct permissions are set on the target log file
// Ensure the logging applications' settings are set to "roll over/refresh" the log files at regular intervals
// Depening on your enviroment, large log files could cause your webserver to crash.
// Recomended individual log files be NO MORE than ~2MB in size  
// If this page is exposed to your WAN, check the logging applications' settings for senstive data within logs
// ** Add Logs BELOW paragraph **
// EXAMPLE
// "NameOfLog" =>'C:/link/to/log/file'
"NZBHydra" => "/home/plex/logs/nzbhydra/nzbhydra.log",    
"NZBtoMedia" => 'C:\logs\nzbtomedia\logs\nzbtomedia.log',
"MP4 Converter" => 'C:\sickbeard_mp4_automator\info.log',
"Radarr" => 'C:\ProgramData\Radarr\logs\radarr.txt',

// ** Add Logs ABOVE this line **
);

$config = array(
// if on Linux, the timezone script will automatically select your timezone
// For Windows, set the timezone. Default is UTC Time. Reference Here: http://php.net/manual/en/timezones.php
// I.E. ($timezone = 'America/Los_Angeles',)
    'timezone' => 'UTC',
    'title' => 'logarr', // Site Title
);
?>
