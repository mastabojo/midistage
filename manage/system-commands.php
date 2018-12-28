<?php
// include '../lib/global.php';
$command = $_POST['cmd'];
error_log($command);
$timeString = date("d.m.Y H:i:s");
// $cmdExitBrowser = 'sudo pkill --oldest --signal TERM chromium';
// $cmdExitBrowser = 'sudo killall --quiet --signal 15 chromium';
// $cmdExitBrowser = 'export DISPLAY=:0;sudo /usr/bin/wmctrl -c Chromium';
$cmdExitBrowser = '/var/www/html/manage/exit-browser.sh';
$cmdReboot = 'sudo reboot now';
$cmdShutdown = 'sudo halt';

switch($command) {
    case 'exit-browser':
        error_log("$timeString: Killing Chromium...");
        exec($cmdExitBrowser);
        break;
        
    case 'reboot':
        error_log("$timeString: Killing Chromium...");
        exec($cmdExitBrowser);
        error_log("$timeString: Rebooting now...");
        exec($cmdReboot);
        break;

    case 'shutdown':
        error_log("$timeString: Killing Chromium...");
        exec($cmdExitBrowser);
        error_log("$timeString: Shutting down...");
        exec($cmdShutdown);
        break;

}
