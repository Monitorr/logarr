
<p align="center"><img src="https://i.imgur.com/ckVKs0n.png"></p>
<br>
<p align="center"><b> Self-hosted, single-page, log consolidation tool written in PHP </b></p>

## Features:

 - Fully self-hosted, no applications to install
 - Reverse display of logs so most recent log entries are at the top
 - Consolidated search & highlight function
 - Collapsible tables (NEW)
 - Log file size and path display (NEW)
 - Ridiculously easy to configure
 - Error term Auto-highlighting for quick reference
 - Localized DTG display (updated)

 
 
 
## Screenshot:

![preview thumb] <img src="https://i.imgur.com/WzcyO1m.png">



## Configuration:
1) Clone/download repository to your webserver (Suggested a Sub DIR)
2) Configure log paths:  Edit the file \config\config.php -> insert PATH to your LOG files where indicated.  **NOTE:  Path locations are CASE SENSITIVE in a Windows enviroment**
3) Configure Time Display: Edit the file \config\config.php -> TIMEZONE tag for proper localized DTG display (only needed on WIN, LINUX auto-populates)
4) Edit permissions for LOG files so your webserver can display the data
5) Navigate to your webserver\Logarr\index.php (example)
6) Chill

## About Us:
- Maintained by [seanvree](https://github.com/seanvree) (Windows Wizard) &  [jonfinley](https://github.com/jonfinley) (Linux Dude) 
- We usually hang out here:   [![Gitter](https://img.shields.io/badge/Gitter-Organizr-ed1965.svg?style=flat-square)](https://gitter.im/Organizrr/Lobby)

[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://www.paypal.me/Seanvree)
