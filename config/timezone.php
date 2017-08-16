<?php
    // if on Linux, the following script will automatically select your timezone
    // else, set yourself on the following line 
    // I.E. ($timezone = 'America/Los_Angeles';)
$timezone = include('config.php'); // set in config.php
    
	// DO NOT EDIT BELOW THIS LINE //    
    if (is_link('/etc/localtime')) {
        // Mac OS X (and older Linuxes)    
        // /etc/localtime is a symlink to the 
        // timezone in /usr/share/zoneinfo.
        $filename = readlink('/etc/localtime');
        if (strpos($filename, '/usr/share/zoneinfo/') === 0) {
            $timezone = substr($filename, 20);
        }
    } elseif (file_exists('/etc/timezone')) {
        // Ubuntu / Debian.
        $data = file_get_contents('/etc/timezone');
        if ($data) {
            $timezone = $data;
        }
    } elseif (file_exists('/etc/sysconfig/clock')) {
        // RHEL / CentOS
        $data = parse_ini_file('/etc/sysconfig/clock');
        if (!empty($data['ZONE'])) {
            $timezone = $data['ZONE'];
        }
    }

date_default_timezone_set($timezone);
?>