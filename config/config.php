<?php // config style adapted from @causeFX and this website: https://www.abeautifulsite.net/a-better-way-to-write-config-files-in-php 
$logs = array(
// ** Add Logs BELOW this paragraph, under the term "array" **
//Ensure correct permissions are set on the target log file
//If this page is exposed ot your WAN, check the logging applications' settings for senstive data within logs. 
// EXAMPLE
// "NameOfLog" =>'/link/to/log/file'
"Sonarr" => '/home/plex/.config/NzbDrone/logs/sonarr.txt',
"Radarr" => '/home/plex/.config/Radarr/logs/radarr.txt',
"Headphones" => "/home/plex/logs/headphones/headphones.log",
/*"NZBGet" => "/home/plex/logs/nzbget/nzbget.log",*/
"MP4 Converter" => "/home/plex/bin/sickbeard_mp4_automator/info.log",
"NZBHydra" => "/home/plex/logs/nzbhydra/nzbhydra.log",
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