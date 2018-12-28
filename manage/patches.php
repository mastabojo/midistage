<?php
include '../lib/global.php';
if(isset($_POST['device']) && is_numeric($_POST['device']) && $_POST['device'] >= 0) {
	$device_id = intval($_POST['device']);
	$deviceIdWhere = "AND patch_device=$device_id";
} else {
	$device_id = -1;
	$deviceIdWhere = '';
}

$dbFile = '../data/mpdata.sqlite'; 
$db = new SQLite3($dbFile);

// get devices
$sql = "SELECT * FROM devices WHERE device_active=1 ORDER BY device_id";
$results = $db->query($sql);
$devices = [];
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
	$devices[$row['device_id']] = $row['device_name'];
}
?>
<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>MIDI Stage</title>
<meta name="description" content="Midistage">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="apple-touch-icon" href="apple-touch-icon.png">
<!-- Place favicon.ico in the root directory -->

<!-- link rel="stylesheet" href="../css/normalize.css" -->
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/main.css">
</head>

<body>
<h1>midistage - management</h1>

<?php include 'menu_top.php';?>

<h2>Patches</h2>

<form method="post" action="patches.php">
<div class="form-group">
<label for="devices">Device</label><br>
<select id="device" name="device" class="autosubmit">
<option value="-1">All</option>
<?php 
foreach($devices as $devId => $devName) {
	echo "<option value=\"$devId\"";
	echo $devId == $device_id ? ' selected>' : '>';
 	echo "$devName</option>";
}
?>
</select>
</div>
</form>

<table class="table table-striped">
<tr><th>Device</th><th>Patch name</th><th>Patch #</th><th>Bank No.</th><th>Bank name</th></tr>
<?php
$sql = "SELECT * FROM patches 
LEFT JOIN banks ON patch_bank0=bank0_no AND patch_bank32=bank32_no AND patch_device=bank_device 
LEFT JOIN devices ON patch_device=device_id
WHERE device_active=1 $deviceIdWhere 
ORDER BY bank0_no, bank32_no, patch_no";
$results = $db->query($sql);
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    echo '<tr>';
    echo '<td>' . $row['patch_device'] . '</td>';
    echo '<td>' . $row['patch_name'] . '</td>';
    // echo '<td>' . $row['patch_description'] . '</td>';
    echo '<td>' . $row['patch_no'] . '</td>';
    echo "<td>{$row['patch_bank0']}-{$row['patch_bank32']}</td>";
    echo '<td>' . $row['bank_name']. '</td>';
    echo '</tr>' . "\n";
}
?>
</table>

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
