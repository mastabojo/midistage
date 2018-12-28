<?php
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

$songlist_id = intval($_POST['songlist_id']);
$i = 0;
foreach ($_POST['item'] as $value) {
	$song_id = intval($value);
	$db->exec("UPDATE songlist_songs SET songlist_song_order= $i WHERE songlist_song_id=$song_id AND songlist_songlist_id=$songlist_id");
	$i++;
}
