
<p align="center"><img src="https://i.imgur.com/ckVKs0n.png">

 ## <p align="center"> Self-hosted, single-page, log consolidation tool written in PHP
</p>

<b> Version:</b> v1.6m

<b> Latest major change: </b>  Search indicator function

## Features:

 - Fully self-hosted, no applications to install
 - Mobile ready (NEW)
 - Reverse display of logs so most recent log entries are at the top
 - Consolidated search & highlight function
 - Collapsible tables
 - Log file size and path display
 - Ridiculously easy to configure
 - Error term auto-highlighting for quick reference
 - Localized DTG display

 <b> Features in development: </b>
- Return number of search hits.
- Application update via GUI.
- Log categories.


## Screenshot:

![preview thumb] <img src="https://i.imgur.com/WzcyO1m.png">



## Installation & Configuration:
1) Clone/download repository to your webserver (Suggested a Sub DIR)
2) Configure log paths:  Edit the file \assets\config\config.php -> insert PATH to your LOG files where indicated.  **NOTE:  Path locations are CASE SENSITIVE in a Windows enviroment**
3) Configure Time Display: Edit the file \assets\config\config.php -> TIMEZONE tag for proper localized DTG display (only needed on WIN, LINUX auto-populates)
4) Edit permissions for LOG files so your webserver can display the data
5) Navigate to your webserver\Logarr\index.php (example)
6) Chill

### Docker
#### Usage
```
docker create \
  --name=logarr \
  --restart=on-failure \
  -v <host path for config:/config \
  -v <host path for logs>:/var/log \
  -e TZ=<timezone> \
  -p 80:80 \
  tronyx/docker-logarr
```

#### Parameters
* `--name` - The name of the container - Call it whatever you want.
* `--restart=on-failure` Container restart mode - Docker attempts to restarts the container if the container returns a non-zero exit code. More info [HERE](https://docs.docker.com/engine/admin/start-containers-automatically/ "HERE") on container restart policies.
* `-v /home/logarr/config:/config` - Your preferred app data config path, IE: where you're storing the Logarr config files.
* `-v /home/logarr/config/log:/var/log` Your preferred app log path, IE: where you're storing the Logarr, Nginx, and PHP logs.
* `-e TZ` - Your timezone, IE: `America/New_York`.

### Info
* To monitor the logs of the container in realtime `docker logs -f logarr`

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
