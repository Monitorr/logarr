
<p align="center"><img src="https://i.imgur.com/ckVKs0n.png">
 
 ## <p align="center"> Self-hosted, single-page, log consolidation tool written in PHP 
</p>

<b> Version:</b> v2.1d
<br>

[![Docker build](https://img.shields.io/docker/build/monitorr/logarr.svg?maxAge=2592000)](https://hub.docker.com/r/monitorr/logarr/)


<b> Latest major change: </b>  Search indicator function

## Features:

 - Fully self-hosted, no applications to install
 - Mobile ready (NEW)
 - Reverse display of logs so most recent log entries are at the top
 - Consolidated search & highlight function (Update)
 - Collapsible tables
 - Log file size and path display
 - Ridiculously easy to configure
 - Error term auto-highlighting for quick reference
 - Localized DTG display

 <b> Features in development: </b>
- Application update via GUI.
- Log categories.
 
 
## Screenshot:

![preview thumb] <img src="https://i.imgur.com/WzcyO1m.png">



## Configuration:
1) Clone/download repository to your webserver (Suggested a Sub DIR)
2) Configure log paths: Edit the file \assets\config\config.php -> insert PATH to your LOG files where indicated.  **NOTE:  Path locations are CASE SENSITIVE in a Windows environment**
3) Configure Time Display: Edit the file \assets\config\config.php -> TIMEZONE tag for proper localized DTG display (only needed on WIN, LINUX auto-populates)
4) Edit permissions for LOG files so your webserver can display the data
5) Navigate to your webserver\Logarr\index.php (example)
6) Chill

## Connect:
Need live help?  Join here :   [![Discord](https://img.shields.io/discord/102860784329052160.svg)](https://discord.gg/YKbRXtt)
<br>
E-mail: monitorrapp@gmail.com
<br>
Buy us a beer! Donate:        [![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/monitorrapp)
<br>
Check out our sister app Monitorr:  https://github.com/Monitorr/Monitorr

## About Us:
- [seanvree](https://github.com/seanvree) (Windows Wizard)
- [jonfinley](https://github.com/jonfinley) (Linux Dude)
- [wjbeckett](https://github.com/wjbeckett)


<p>
    <a href="https://jigsaw.w3.org/css-validator/check/referer">
        <img style="border:0;width:88px;height:31px"
            src="https://jigsaw.w3.org/css-validator/images/vcss"
            alt="Valid CSS!" />
    </a>
</p>
