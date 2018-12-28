<?php
include '../lib/global.php';
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// some data has been submited from edit_device.php
if(isset($_POST['submit'])) {
	$id = intval($_POST['id']);
	$name = $db->escapeString($_POST['name']);
	$description = $db->escapeString($_POST['description']);
	$port = intval($_POST['port']);
	$active = isset($_POST['active']) && $_POST['active'] == 'on' ? 1 : 0;
	$sql = $id == 0 ?
	"INSERT INTO devices (device_name, device_description, device_port, device_active) VALUES
	('$name', '$description', $port, 1)" :
	"UPDATE devices SET device_name='$name', device_description='$description', device_port=$port, device_active=$active 
    WHERE device_id=$id";
	$db->exec($sql);
	unset($_POST);
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

<h2>Devices</h2>

<div class="form-group">
<a href="edit_device.php">
<button type="button" class="btn btn-primary">Add device</button>
</a>
</div>

<table class="table table-striped item-table">
<tr><th>Name</th><th>Description</th><th>Port</th><th>Active</th><th><span class="glyphicon glyphicon-remove"></span></th></tr>
<?php
$sql = 'SELECT * FROM devices ORDER BY device_port';
$results = $db->query($sql);
while ($row = $results->fetchArray()) {
	$activeGlyph = $row['device_active'] == 0 ? 'glyphicon-minus' : 'glyphicon-ok';
    echo '<tr data-entity="device" data-id="' . $row['device_id'] . '" class="row-clickable">';
    echo '<td class="edit-entity">' . $row['device_name'] . '</td>';
    echo '<td class="edit-entity">' . $row['device_description'] . '</td>';
    echo '<td class="edit-entity">' . $row['device_port'] . '</td>';
    echo "<td><span class=\"glyphicon $activeGlyph item-activate\"></span></td>";
    echo '<td><span class="glyphicon glyphicon-remove item-remove"></span></td>';
    echo '</tr>' . NL;
}
?>
</table>

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
