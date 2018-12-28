<?php
/*
 * Test patches
 */


$scripName = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

include '../lib/global.php';
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);
// $db = new PDO("sqlite:$dbFile");

// get active devices
$devices = [];
$sql = "SELECT * FROM devices WHERE device_active=1 ORDER BY device_name";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
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
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/main.css">
</head>

<body>
<h1>midistage - management</h1>

<?php include 'menu_top.php';?>

<h2>Test</h2>

<div class="container-fluid">

<div class="row">
<div class="col-md-3"><label>Device</label></div><!-- .col-XXX -->
<div class="col-md-1"><label>Channel</label></div><!-- .col-XXX -->
<div class="col-md-2"><label>Bank</label></div><!-- .col-XXX -->
<div class="col-md-2"><label>Patch</label></div><!-- .col-XXX -->
<div class="col-md-1"><label>Volume</label></div><!-- .col-XXX -->
<div class="col-md-1"><label>Expression</label></div><!-- .col-XXX -->
<div class="col-md-2"></div><!-- .col-XXX -->
</div><!-- .row -->

<!-- row for testing patches -->
<form action="<?php echo $scripName;?>" method="post">
<div class="row" id="add-patch" data-song-patch-id="-1" data-song-id="<?php echo intval($_GET['song_id']);?>">

<div class="col-md-3">

<div class="form-group">
<select class="form-control device-selector" name="device" id="device">

<?php
// get first key of devices array
reset($devices);
$firstDeviceId = key($devices);

foreach($devices as $deviceId => $deviceName) {
	echo "<option value=\"$deviceId\">$deviceName</option>";
}
?>
</select>
</div><!-- .form-group -->

</div>

<div class="col-md-1">
<div class="form-group">
<select class="form-control channel-selector" name="channel" id="channel">
<?php 
for($ch = 0; $ch < 16; $ch++) {
	echo "<option value=\"$ch\">" . ($ch + 1) . '</option>';
}
?>
</select>
</div><!-- .form-group -->
</div><!-- .col-XXX -->

<div class="col-md-2">
<div class="form-group">
<select class="form-control bank-selector" name="bank" id="bank">

<?php 
/**/
// get all banks for first device
$sql = "SELECT * FROM banks WHERE bank_device=$firstDeviceId";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	echo "<option value=\"{$row['bank0_no']}-{$row['bank32_no']}\">";
	echo "{$row['bank0_no']} {$row['bank32_no']} [{$row['bank_name']}]</option>";
}
?>
</select>
</div><!-- .form-group -->
</div>

<div class="col-md-2">
<div class="form-group">
<select class="form-control patch-selector" name="patch" id="patch">

<?php
// get first bank
$sql = "SELECT * FROM banks WHERE bank_device=$firstDeviceId ORDER BY bank0_no, bank32_no LIMIT 1";
$rowF = $db->querySingle($sql, true);
// get all patches for first bank
$sql = "SELECT * FROM patches WHERE patch_device=$firstDeviceId AND patch_bank0={$rowF['bank0_no']} AND patch_bank32={$rowF['bank32_no']}";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	echo "<option value=\"{$row['patch_no']}\">";
	echo "{$row['patch_no']} [{$row['patch_name']}]</option>";
}
$db->close();
?>
</select>
</div><!-- .form-group -->
</div><!-- .col-XXX -->

<div class="col-md-1">
  <div class="form-group">
    <input type="number" min="0" max="127" class="form-control volume-input" name="volume" id="volume" placeholder="Volume">
  </div>
</div><!-- .col-XXX -->

<div class="col-md-1">
  <div class="form-group">
    <input type="number" min="0" max="127" class="form-control expression-input" name="expression" id="expression" placeholder="Expression">
  </div>
</div><!-- .col-XXX -->

<div class="col-md-2">
<button class="btn btn-info song-patch-test" type="button" name="test">Test</button>
</div><!-- .col-XXX -->

</div><!-- .row -->


<div class="row" data-song-patch-id="<?php echo $songPatchId;?>" data-song-id="<?php echo intval($_GET['song_id']);?>">
<div class="col-md-10">
<div class="form-group">
<textarea class="form-control txt-midi-custom" rows="1" placeholder="custom midi cc"></textarea>
</div>
</div><!-- .col-XXX -->

<div class="col-md-2">
</div><!-- .col-XXX -->

</div><!-- .row -->

</form>

</div><!-- .container -->

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
