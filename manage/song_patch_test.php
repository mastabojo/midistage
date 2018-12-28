<?php
/*
 * Test a patch before saving
 * JSON parameters
 * dataOut = {"device": device, "channel": channel, "bank0": bank_0, "bank32": bank_32, "patch": patch};
 */
include '../lib/global.php';
error_log(print_r($_POST, 1));
// test patch
if(isset($_POST['device']) && is_numeric($_POST['device'])) {
    $device = intval($_POST['device']);
} else {
    exit();
}
if(isset($_POST['channel']) && is_numeric($_POST['channel'])) {
    $channel = intval($_POST['channel']);
} else {
    exit();
}
if(isset($_POST['bank0']) && is_numeric($_POST['bank0'])) {
    $bank0 = intval($_POST['bank0']);
} else {
    exit();
}
if(isset($_POST['bank32']) && is_numeric($_POST['bank32'])) {
    $bank32 = intval($_POST['bank32']);
} else {
    exit();
}
if(isset($_POST['patch']) && is_numeric($_POST['patch'])) {
    $patch = intval($_POST['patch']);
} else {
    exit();
}
if(isset($_POST['volume']) && is_numeric($_POST['volume']) && $_POST['volume'] >= 0 && $_POST['volume'] <= 127) {
    $volume = intval($_POST['volume']);
} else {
    $volume = 'NULL';
}
if(isset($_POST['expression']) && is_numeric($_POST['expression']) && $_POST['expression'] >= 0 && $_POST['expression'] <= 127) {
    $expression = intval($_POST['expression']);
} else {
    $expression = 'NULL';
}
if(isset($_POST['custom_cc']) && $_POST['custom_cc'] != '') {
    $custom_cc = $_POST['custom_cc'];
} else {
    $custom_cc = '';
}

// get device port
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);
$sql = "SELECT device_port FROM devices WHERE device_id=$device";
// error_log($sql);
$result = $db->query($sql);
$row = $result->fetchArray();

// run python script to change patch
$patchData[0] = [
    'port' => $row['device_port'],
    'channel' => $channel,
    'bank0' => $bank0,
    'bank32' => $bank32,
    'program' => $patch
];

// call python script with appropriate arguments in JSON format
$pyScript = '/var/www/html/set_devices.py';
$pythonExec = '/usr/bin/python';
$pyArgs = json_encode($patchData, JSON_NUMERIC_CHECK);
$cmd = "sudo -u www-data $pythonExec $pyScript '$pyArgs' 2>&1";
error_log($cmd);
$output = shell_exec($cmd);
