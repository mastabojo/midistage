<?php
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);
if(!isset($_POST['entity']) || !isset($_POST['id']) || !is_numeric($_POST['id'])) {
	error_log('ERROR - Entity: ' . $_POST['entity'] . ' ID: ' . $_POST['id']);
	exit();
}

$id = intval($_POST['id']);

switch($_POST['entity']) {
	case 'song':
		$sql = "DELETE FROM songs WHERE song_id=$id LIMIT 1";
		$db->exec($sql);
		break;
	case 'songlist':
		$sql = "DELETE FROM songlists WHERE songlist_id=$id LIMIT 1";
		$db->exec($sql);
		// also delete songs of the deleted songlist
		$sql = "DELETE FROM songlist_songs WHERE songlist_songlist_id=$id";
		$db->exec($sql);
		break;
	case 'device':
		$sql = "DELETE FROM devices WHERE device_id=$id LIMIT 1";
		$db->exec($sql);
		break;
	case 'songlist_song':
		if(isset($_POST['songlist_id'])) {
			$songlist_id = intval($_POST['songlist_id']);
		} else {
			error_log("ERROR - songlist_id: {$_POST['songlist_id']}");
			exit();
		}
		$sql = "DELETE FROM songlist_songs WHERE songlist_songlist_id=$songlist_id AND songlist_song_id=$id LIMIT 1";
		$db->exec($sql);
		break;		
}

