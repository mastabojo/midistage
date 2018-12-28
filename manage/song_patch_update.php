<?php
/*
 * Update table song_patches
 * - insert (add) new song patch data
 * - update existing song patch data
 * - remove existing song patch data 
 * JSON parameters
 * dataOut = {"song": song, "songpatch": song_patch, "device": device, "channel": channel, "bank0": bank_0, "bank32": bank_32, "patch": patch};
 */
include '../lib/global.php';

if(isset($_POST['songpatch']) && is_numeric($_POST['songpatch'])) {
	$songPatch = intval($_POST['songpatch']);
} else {
    error_log("MIDISTAGE ERROR: Wrong song/patch ID");
	exit();
}
error_log("SONG: $songPatch");
$dbFile = '../data/mpdata.sqlite';
// $db = new SQLite3($dbFile);
$db = new PDO("sqlite:$dbFile");

// insert or update
if(!isset($_POST['action']) && $_POST['action'] == 'save') {
	if(isset($_POST['song']) && is_numeric($_POST['song'])) {
		$song = intval($_POST['song']);
	} else {
	    error_log("MIDISTAGE ERROR: Wrong song ID");
		exit();
	}
	if(isset($_POST['device']) && is_numeric($_POST['device'])) {
		$device = intval($_POST['device']);
	} else {
	    error_log("MIDISTAGE ERROR: Wrong device ID");
		exit();
	}
	if(isset($_POST['channel']) && is_numeric($_POST['channel'])) {
		$channel = intval($_POST['channel']);
	} else {
	    error_log("MIDISTAGE ERROR: Wrong MIDI channel");
		exit();
	}
	if(isset($_POST['bank0']) && is_numeric($_POST['bank0'])) {
		$bank0 = intval($_POST['bank0']);
	} else {
	    error_log("MIDISTAGE ERROR: Wrong bank0 value");
		exit();
	}
	if(isset($_POST['bank32']) && is_numeric($_POST['bank32'])) {
		$bank32 = intval($_POST['bank32']);
	} else {
	    error_log("MIDISTAGE ERROR: Wrong bank32 value");
		exit();
	}
	if(isset($_POST['patch']) && is_numeric($_POST['patch'])) {
		$patch = intval($_POST['patch']);
	} else {
	    error_log("MIDISTAGE ERROR: Wrong patch number");
		exit();
	}
	if(isset($_POST['volume']) && is_numeric($_POST['volume']) && $_POST['volume'] >= 0 && $_POST['volume'] <= 127) {
		$volume = intval($_POST['volume']);
	} else {
		$volume = 100;
	}
	if(isset($_POST['expression']) && is_numeric($_POST['expression']) && $_POST['expression'] >= 0 && $_POST['expression'] <= 127) {
		$expression = intval($_POST['expression']);
	} else {
		$expression = 0;
	}
	if(isset($_POST['custom_cc']) && $_POST['custom_cc'] != '') {
		$custom_cc = $_POST['custom_cc'];
	} else {
		$custom_cc = '';
	}
	
	// $songPatch == -1: INSERT
	// $songPatch > -1: UPDATE 
	$sql = $songPatch == -1 ? 
	"INSERT INTO song_patches 
     (song_patch_song, song_patch_device, song_patch_channel, song_patch_bank0, song_patch_bank32, song_patch_patch, 
     song_patch_volume, song_patch_expression, song_patch_customcc) VALUES
    ($song, $device, $channel, $bank0, $bank32, $patch, $volume, $expression, '$custom_cc')" : 
	"UPDATE song_patches SET 
     song_patch_device=$device, song_patch_channel=$channel, song_patch_bank0=$bank0, song_patch_bank32=$bank32, 
     song_patch_patch=$patch, song_patch_volume=$volume, song_patch_expression=$expression, song_patch_customcc='$custom_cc'
	WHERE song_patch_id=$songPatch LIMIT 1";
	error_log($sql);
	// $result = $db->exec($sql) ? 'SUCCESS' : 'ERROR';
	$result = $db->execute($sql) ? 'SUCCESS' : 'ERROR';
	echo $result;
	// $db->close();
	exit();
}
// remove
if(isset($_POST['action']) && $_POST['action'] == 'remove') {
	$sql = "DELETE FROM song_patches WHERE song_patch_id=$songPatch";
	// $db->exec($sql);
	$db->execute($sql);
	// echo $result ? 'SUCCESS' : 'ERROR';
	// $db->close();
	exit();
}
