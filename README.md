# Wake PC

Wake PC is a super tiny password protected webapp for linux machines that sends WOL packets, written in PHP.

![screenshot](https://github.com/szabodanika/wake-pc/blob/master/readme-header.png)

## How to set up

### Quick setup
You can run this command to run to quickly set up and serve Wake PC on port 9010 using PHP's embedded webserver. This will load a default configuration, so you need to edit `config.ini` later. For Wake PC to work make sure to follow the instructions for configuring your target machine as well, otherwise it might not work!

```
sudo sh -c 'curl -s https://raw.githubusercontent.com/szabodanika/wake-pc/master/quicksetup.sh | bash'
```

Or you can do it manually:

1. Create a folder in /var/www/ and navigate to it
```
mkdir /var/www/wake-pc
cd /var/www/wake-pc
```
3. Download index.php and config template
```
wget https://raw.githubusercontent.com/szabodanika/wake-pc/master/index.php
wget https://raw.githubusercontent.com/szabodanika/wake-pc/master/config-template.ini
```
3. Copy template
```
cp config-template.ini config.ini
```
4. Edit template using any text editor
```
sudo vim config.ini
```
5. Start embedded PHP webserver or host with your webserver of choice (NGINX, Apache etc.)
```
php -S 0.0.0.0:9010
```
### Client configuration

For WOL to work it needs ot be enabled on the target PC, by default this is usually not the case. You will likely find this setting in your BIOS.

You also need to make sure that the machine will only be woken up by WOL packets and nothing else, otherwise the initial ping that checks whether the machine is online will also turn it on.

Go in Control Panel\All Control Panel Items\Network Connections, select your connection -> right click -> Properties -> Power Management, tick "Allow this device to wake the computer" and make sure to also tick "Only allow a magic packet to wake the computer".


## Planned improvements
- Remove plaintext password haha
- GUI config
- Scheduling
- Shutdown and reboot maybe?
- Toggling pinging on and off
