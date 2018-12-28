<?php
session_start();
include 'lib/global.php';
$dbFile = 'data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// find active devices
$sql = "SELECT device_port FROM devices WHERE device_active=1 ORDER BY device_port";
$result = $db->query($sql);
$devicePorts = [];
while($row = $result->fetchArray()) {
	$devicePorts[] = $row['device_port'];
}
if(count($devicePorts) < 1) {
	echo "ERROR";
	exit();
}

// call python script with appropriate arguments in JSON format
$pyScript = 'all_notes_off.py';
$pythonExec = '/usr/bin/python';
$pyArgs = json_encode($devicePorts, JSON_NUMERIC_CHECK);
$cmd = "sudo -u www-data $pythonExec $pyScript '$pyArgs'";
$output = shell_exec($cmd);

/*
$midiCmd = [];
$k = 0;
foreach($devicePorts as $port) {
	for($ch = 0; $ch <= 15; $ch++) {
		$midiCmd[$k]['port'] = $port;
		$midiCmd[$k]['channel'] = $ch;
		$midiCmd[$k]['cmd'] = '7Bh';
		$k++;
	}
}
error_log(print_r($midiCmd, 1));
*/