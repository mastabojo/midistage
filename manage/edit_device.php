<?php
include '../lib/global.php';
// deviceID (0 -> new device)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$heading1 = $id == 0 ? 'Add device' : 'Edit device';

$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// edit device
if($id > 0) {
    $sql = "SELECT * FROM devices WHERE device_id=$id";
    $result = $db->query($sql);
    $row = $result->fetchArray(SQLITE3_ASSOC);
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
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/main.css">
</head> 

<body>
<h1><?php echo $heading1;?></h1>

<form method="post" action="devices.php">
<div class="form-group">
<label for="name">Name</label>
<input type="text" class="form-control" id="name" name="name" placeholder="Name" value="
<?php echo isset($row['device_name']) ? $row['device_name'] : '';?>">
</div>
<div class="form-group">
<label for="description">Description</label>
<input type="text" class="form-control" id="description" name="description" placeholder="Description" value="
<?php echo isset($row['device_description']) ? $row['device_description'] : '';?>">
</div>

<div class="form-group">
<label for="port">Port</label>
<select class="form-control" name="port">
<option value="-1">-- Select port --</option>
<?php
$maxPort = 8;
for($p = 0; $p <= $maxPort; $p++) {
	echo '<option value="' . $p . '"';
	echo (isset($row['device_port']) && $row['device_port'] == $p) ? '  selected="selected">' : '>';
	echo "$p</option>";
}
?>
</select>
</div>


<?php 
// if editing device and device is not active
$checked = $row['device_active'] == 0 && $id > 0 ? '' : 'checked';
?>
<div class="form-group">
<label><input type="checkbox" name="active" id="active" <?php echo $checked;?>> Active</label>
</div>

<input type="hidden" name="id" value="<?php echo $id;?>">
<button type="submit" name="submit" class="btn btn-default">Save</button>
<button type="button" name="cancel" class="btn btn-default btn-cancel">Cancel</button>
</form>

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
