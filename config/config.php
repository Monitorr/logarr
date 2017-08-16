<?php
$logs = array(
// ** Add Logs BELOW this paragraph, under the term "array" **
//Ensure correct permissions are set on the target log file
//If this page is exposed ot your WAN, check the logging applications' settings for senstive data within logs. 
// EXAMPLE
// "NameOfLog" =>'/link/to/log/file'

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