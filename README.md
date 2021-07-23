# Wake PC

![screenshot](https://github.com/szabodanika/wake-pc/blob/master/readme-header.png)

## How to set up

### 1. Install Wake PC

All you need is `wake-pc.php` and `wake-pc-config.ini`. You can either clone this repo or just download the php file and `wake-pc-config-default.ini` that you need to copy and rename and fill out with your own connection data. You will be able to serve Wake PC with your webserver of choice as long as it supports PHP. To host with NGINX, follow the instructions [here](https://www.nginx.com/resources/wiki/start/topics/examples/phpfcgi/).

### 2. Config Server

You will need to install wakeonlan for this to work or you can replace the WOL command with anything else in line 19 of `wake-pc.php`.

You can install wakeonlan on Ubuntu with `sudo apt install wakeonlan`.

### 3. Config Target PC 

For WOL to work it needs ot be enabled on the target PC, by default this is usually not the case. You will likely find this setting in your BIOS.

You also need to make sure that the machine will only be woken up by WOL packets and nothing else, otherwise the initial ping that checks whether the machine is online will also turn it on.

Go in Control Panel\All Control Panel Items\Network Connections, select your connection -> right click -> Properties -> Power Management, tick "Allow this device to wake the computer" and make sure to also tick "Only allow a magic packet to wake the computer".
