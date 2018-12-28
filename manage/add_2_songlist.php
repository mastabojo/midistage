<?php
// error_log(print_r($_POST, 1));
if(!isset(
	$_POST['song']) || 
	!is_numeric($_POST['song']) || 
	!isset($_POST['songlist']) || 
	!is_numeric($_POST['songlist'])) {
	echo 'ERROR';
	exit();
}
$song_id = intval($_POST['song']);
$songlist_id = intval($_POST['songlist']);
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// get last song order number + 1
$sql = "SELECT MAX(songlist_song_order) FROM songlist_songs WHERE songlist_songlist_id=$songlist_id";
$song_order = $db->querySingle($sql) + 1;

// insert song into songlist
$sql  = "INSERT INTO songlist_songs (songlist_songlist_id, songlist_song_id, songlist_song_order) VALUES (
$songlist_id, $song_id, $song_order)";
$db->exec($sql);
echo 'SUCCESS';