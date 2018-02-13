
![logarr](https://i.imgur.com/BxmpBtA.png)

## - *Logarr* is a Self-hosted, single-page, log consolidation tool written in PHP 

[![version](https://img.shields.io/github/release/monitorr/monitorr.svg?style=flat)](https://github.com/monitorr/logarr/releases) [MASTER]

[![Docker build](https://img.shields.io/docker/build/monitorr/logarr.svg?maxAge=2592000)](https://hub.docker.com/r/monitorr/logarr/) [DOCKER]

**Version:** v4.1d [DEVELOP]


**NOTICE (12 FEB 18)**: If updating to from ANY version prior to **4.0**, you will need to re-create your config.php file in /assets/config/. You can do this by renaming your old config file, coping the config.sample-12feb18.php to config.php, and then edit the newly created config.php file with your values.


## Features:

**Latest major change:** Application update via GUI

 - Fully self-hosted, no applications to install
 - Live display of any .log, .txt, etc. file on the hosting webserver
 - Log auto-update enable/disable switch
 - Mobile ready (ish)
 - Reverse display of logs / lind numbering - most recent log entries are at the top (updated)
 - Consolidated search & highlight function (Update)
 - Collapsible log tiles with scroll to top feature/auto collapse log display (Updated)
 - Log file size and path display
 - Error term auto-highlighting for quick reference (update)
 - Localized server DTG display
 - Ridiculously easy to configure

 **Features in development:**
- Log rolling option
- Settings page
 
 
## Screenshot:

![logarr](https://i.imgur.com/bc5jDg5.png)


## Quick Start:
- See full configuration instructions in the WiKi: https://github.com/Monitorr/logarr/wiki
1) Clone/download repository to your webserver
2) Browse to <localhost\domain>/logarr/index.php (config.php will be auto populated in /assets/config/config.php)
3) Configure log paths: Edit the file \assets\config\config.php 
4) Edit permissions for LOG files so your webserver can display the data
5) Navigate to your  <localhost\domain>/logarr/index.php 
6) Chill

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
- [causefx](https://github.com/causefx)



[![CSS](https://jigsaw.w3.org/css-validator/images/vcss)](https://jigsaw.w3.org/css-validator/check/refererr/)
