ALSA
----

Alsa - display sound and Midi cards
cat /proc/asound/cards
cat /proc/asound/clients
cat /sys/class/sound/card*/id

Alsa - show devices
amidi -l
amidi -L
aplay -L

Alsa - list connected ports
aconnect  -i -o

Links:
http://tedfelix.com/linux/linux-midi.html
http://www.alsa-project.org
https://alsa.opensrc.org

Add www-data in the sudoers file
--------------------------------
sudo visudo
www-data         ALL=(ALL) NOPASSWD: ALL

Get Chromium to auto start in kiosk mode
----------------------------------------
Create .config/autostart/autoChromium.desktop with the following contents:
[Desktop Entry]                                                                                                                                                                         
Type=Application                                                                                                                                                                        
Exec=/usr/bin/chromium-browser --noerrdialogs --disable-session-crashed-bubble --disable-infobars --kiosk http://midistage-pi.local                                                     
Hidden=false                                                                                                                                                                            
X-GNOME-Autostart-enabled=true                                                                                                                                                          
Name[en_US]=AutoChromium                                                                                                                                                                
Name=AutoChromium                                                                                                                                                                       
Comment=Start Chromium when GNOME starts 

Disable Restore pages in Chromium
---------------------------------
Settings->Advanced Settings->System-> uncheck Continue running background apps when Chrome is closed

Disable screensaver and power settings:
https://www.danpurdy.co.uk/web-development/raspberry-pi-kiosk-screen-tutorial/

Hide scrollbars with CSS (Webkit browsers)
#songs-div::-webkit-scrollbar {
    width: 0px;
}

Hide cursor
-----------
sudo apt-get install unclutter
nano ~/.config/lxsession/LXDE-pi/autostart
add: @unclutter -idle 0

Change hostname to midistage-pi to get device discoverable by Bonjour
---------------------------------------------------------------------
(remote PC must have Bonjour i.e. avahi daemon)
sudo nano /etc/hosts
sudo nano /etc/hostname 

Toggle GUI on Raspberry Pi
--------------------------
sudo raspi-config

Clear the chromium dirty exit flag
----------------------------------
sed -i 's/"exited_cleanly":false/"exited_cleanly":true/' ~/.config/chromium/'Local State'
sed -i 's/"exited_cleanly":false/"exited_cleanly":true/; s/"exit_type":"[^"]\+"/"exit_type":"Normal"/' ~/.config/chromium/Default/Preferences

