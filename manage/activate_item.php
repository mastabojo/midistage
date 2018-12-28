<?php
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);
if(!is_numeric($_POST['id']) or !in_array($_POST['act'], [0, 1])) {
	echo 'ERROR';
	exit();
}

$device_id = intval($_POST['id']);
$active = intval($_POST['act']);

$db->exec("UPDATE devices SET device_active=$active WHERE device_id=$device_id");
echo 'SUCCESS';