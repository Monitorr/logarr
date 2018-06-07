
![logarr](https://i.imgur.com/BxmpBtA.png)

## - *Logarr* is a Self-hosted, single-page, log consolidation tool written in PHP 

[![](https://img.shields.io/github/release/monitorr/logarr.svg?style=flat)](https://github.com/monitorr/logarr/releases) [MASTER]


[![Docker build](https://img.shields.io/docker/build/monitorr/logarr.svg?maxAge=2592000)](https://hub.docker.com/r/monitorr/logarr/) [DOCKER]

[![GitHub (pre-)release](https://img.shields.io/github/release/monitorr/logarr/all.svg)](https://github.com/monitorr/logarr/releases) [DEVELOP]



**NOTICE (06 June 18)**: If updating to version **4.5** from ANY previous version, you will need to re-create your config.php file in the /assets/config/ directory after updating. You can do this by renaming your old config.php file, copying the file config.sample-06jun18.php to config.php, and then edit the newly created config.php file with your values. See the [Wiki](https://github.com/Monitorr/logarr/wiki/NOTICE:-Updating-Logarr) for more information.



## Features:

**Latest major change** (_06 June 18 - v4.5_): Log rolling tool / Log download tool/ Max line limit

 - Fully self-hosted, no applications to install
 - Live display any .log, .txt, etc. file on the hosting webserver
 - Reverse display of logs / line numbering - most recent log entries are at the top
 - Application update via GUI
 - Consolidated search & highlight function (Updated)
 - Collapsible log tiles with scroll to top feature/auto collapse log display (Updated)
 - Log file size and path display
 - Error term auto-highlighting for quick reference (updated)
 - Localized server DTG display
 - Log auto-update enable/disable switch
 - Ridiculously easy to configure

 **Features in development:**
- Settings page
- Custom CSS
 
 
## Screenshot:

![logarr](https://i.imgur.com/GjrSWfk.png)


## Prerequisites:
1) [PHP](https://secure.php.net/downloads.php) (7.1+ recommended)
2) [PHP ZipArchive](http://www.php.net/manual/en/zip.installation.php)


## Quick Start:
- See full configuration instructions in the WiKi: https://github.com/Monitorr/logarr/wiki
1) Clone/download repository to your webserver
2) Browse to <localhost\domain>/logarr/index.php (config.php will be auto populated in /assets/config/config.php)
3) Configure log paths: Edit the file \assets\config\config.php 
4) Edit permissions for LOG files so your webserver can display the data
5) Browse to: <localhost\domain>/logarr/index.php 
6) Chill

## Feature Requests:
 [![Feature Requests](https://cloud.githubusercontent.com/assets/390379/10127973/045b3a96-6560-11e5-9b20-31a2032956b2.png)](https://feathub.com/Monitorr/logarr)

**Current feature requests:**

[![Feature Requests](http://feathub.com/Monitorr/logarr?format=svg)](http://feathub.com/Monitorr/logarr)




## Connect:
- Need live help?  Join us on Discord here :   [![Discord](https://img.shields.io/discord/102860784329052160.svg)](https://discord.gg/YKbRXtt)

- E-mail: monitorrapp@gmail.com

- Buy us a beer! Donate:        [![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/monitorrapp)

- Check out our sister app **Monitorr**:  https://github.com/Monitorr/Monitorr

## About Us:
- [seanvree](https://github.com/seanvree) (Windows Wizard)
- [jonfinley](https://github.com/jonfinley) (Linux Dude)
- [wjbeckett](https://github.com/wjbeckett)

### Credits:
- [Causefx](https://github.com/Causefx)
- [Roxedux](https://github.com/si0972)
- [christronyxyocum](https://github.com/christronyxyocum)
- [rob1998](https://github.com/rob1998)


[![CSS](https://jigsaw.w3.org/css-validator/images/vcss)](https://jigsaw.w3.org/css-validator/check/refererr/)
