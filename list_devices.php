<?php
// call python script with appropriate arguments in JSON format
$pyScript = 'list_devices.py';
$pythonExec = '/usr/bin/python';
$cmd = "sudo -u www-data $pythonExec $pyScript";
echo nl2br(shell_exec($cmd));

/*
// query alsa for midi devices 
$output = shell_exec("sudo -u www-data amidi -l");
error_log($output);
4 midi devices found\nALSA name: Midi Through Port-0 input: 0 output: 1 opened: 0\nALSA name: Midi Through Port-0 input: 1 output: 0 opened: 0\nALSA name: CH345 MIDI 1 input: 0 output: 1 opened: 0\nALSA name: CH345 MIDI 1 input: 1 output: 0 opened: 0\nDefault device: 0\n
*/