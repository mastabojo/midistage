<?php
/*
 * Send custom midi data (performance mode)
 */

include 'lib/global.php';
if(
	isset($_POST['device_id']) && is_numeric($_POST['device_id']) &&
	isset($_POST['channel']) && is_numeric($_POST['channel']) &&
	isset($_POST['bank0']) && is_numeric($_POST['bank0']) &&
	isset($_POST['bank32']) && is_numeric($_POST['bank32']) &&
	isset($_POST['program']) && is_numeric($_POST['program'])
) {

	$patchData[0] = array(
            // 'port' => intval($_POST['device_id']),

            // TEMP
		'port' => 2,

		'channel' => intval($_POST['channel']),
		'bank0' => intval($_POST['bank0']),
		'bank32' => intval($_POST['bank32']),
		'program' => intval($_POST['program'])
	);

	// call python script with appropriate arguments in JSON format
	$pyScript = 'set_devices.py';
	$pythonExec = '/usr/bin/python';
	$pyArgs = json_encode($patchData, JSON_NUMERIC_CHECK);
	$cmd = "sudo -u www-data $pythonExec $pyScript '$pyArgs' 2>&1";
	error_log($cmd);
	$output = shell_exec($cmd);
} else {
	echo 'ERROR';
	exit();
}



