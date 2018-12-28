<?php
/*
 * NOT USED
 */
session_start();
if(isset($_POST['debug']) && $_POST['debug'] == 0 || $_POST['debug'] == 1) {
	$_SESSION['debug'] = intval($_POST['debug']);
} else {
	echo "ERROR";
	exit();
}