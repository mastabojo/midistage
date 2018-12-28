<?php
/*
 * Send midi data for selected song
 */
if(isset($_POST['song_id']) && is_numeric($_POST['song_id'])) {
	$song_id = intval($_POST['song_id']);
} else {
	echo "MIDISTAGE ERROR: Song ID {$_POST['song_id']} does not exist.";
        error_log("MIDISTAGE ERROR: Song ID {$_POST['song_id']} does not exist.");
        exit();
}
// read MIDI data for this song from the DB
$dbFile = 'data/mpdata.sqlite';
$db = new SQLite3($dbFile);
$sql = "SELECT * FROM song_patches JOIN devices ON song_patch_device=device_id WHERE song_patch_song=$song_id";
$result = $db->query($sql);
$patchData = [];
// construct an array of data to be converted to JSON and sent to set_devices.py script
while($row = $result->fetchArray()) {
	// $patchData[$row['song_patch_device']] = [$row['song_patch_channel'], $row['song_patch_bank'], $row['song_patch_patch']];
	$patchData[] = array(
			'port' => $row['device_port'],
			'channel' => $row['song_patch_channel'], 
			'bank0' => $row['song_patch_bank0'],
			'bank32' => $row['song_patch_bank32'],
			'program' => $row['song_patch_patch']
	);
}

// call python script with appropriate arguments in JSON format
$pyScript = 'set_devices.py';
$pythonExec = '/usr/bin/python';
$pyArgs = json_encode($patchData, JSON_NUMERIC_CHECK);
$cmd = "sudo -u www-data $pythonExec $pyScript '$pyArgs' 2>&1";
$output = shell_exec($cmd);
