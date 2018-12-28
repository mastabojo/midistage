<?php
/*
 * Assign patches to a song
 */

if(!isset($_GET['song_id']) && !is_numeric($_GET['song_id'])) {
	header("location:songs.php");
	exit();
}

$scripName = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

include '../lib/global.php';
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);
// $db = new PDO("sqlite:$dbFile");

$song_id = intval($_GET['song_id']);
$sql = "SELECT song_title FROM songs WHERE song_id=$song_id";
$song_data = $db->querySingle($sql, true);

// get active devices
$devices = [];
$sql = "SELECT * FROM devices WHERE device_active=1 ORDER BY device_name";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	$devices[$row['device_id']] = $row['device_name'];
}

// current patch data
$patches = [];
// used midi channels (channel can only be set once for any one device)
$usedChannels = [];
$sql = "SELECT * FROM song_patches 
JOIN patches ON song_patch_patch=patch_no AND song_patch_device=patch_device
JOIN banks on song_patch_device=bank_device AND song_patch_bank0=bank0_no AND song_patch_bank32=bank32_no 
JOIN devices ON song_patch_device=device_id AND song_patch_bank0=patch_bank0 AND song_patch_bank32=patch_bank32 
WHERE song_patch_song=$song_id AND device_active=1 ORDER BY device_name";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	$patches[$row['song_patch_id']] = [
			'device_id' => $row['song_patch_device'], 
			'device_name' => $devices[$row['song_patch_device']],
			'channel' => $row['song_patch_channel'], 
			'bank0' => $row['song_patch_bank0'], 
			'bank32' => $row['song_patch_bank32'],
			'bank_name' => $row['bank_name'],
			'patch' => $row['song_patch_patch'],
			'patch_name' => $row['patch_name'],
			'volume' => $row['song_patch_volume'],
			'expression' => $row['song_patch_expression'],
			'custom_cc' => $row['song_patch_customcc']
	];
	$usedChannels[$row['song_patch_device']][] = $row['song_patch_channel'];
}
// D($usedChannels);
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
<h1>Assign patches <small><?php echo $song_data['song_title'];?></small></h1>

<div class="container-fluid">

<div class="row">
<div class="col-md-12">
<a href="songs.php"><button>Back to songs</button></a>
</div><!-- .col-XXX -->
</div><!-- .row -->

<div class="row">
<div class="col-md-12">&nbsp;</div><!-- .col-XXX -->
</div><!-- .row -->

<div class="row">
<div class="col-md-3"><label>Device</label></div><!-- .col-XXX -->
<div class="col-md-1"><label>Channel</label></div><!-- .col-XXX -->
<div class="col-md-2"><label>Bank</label></div><!-- .col-XXX -->
<div class="col-md-2"><label>Patch</label></div><!-- .col-XXX -->
<div class="col-md-1"><label>Volume</label></div><!-- .col-XXX -->
<div class="col-md-1"><label>Expression</label></div><!-- .col-XXX -->
<div class="col-md-2"></div><!-- .col-XXX -->
</div><!-- .row -->

<?php 
foreach($patches as $songPatchId => $patchData):
?>

<form action="<?php echo $scripName;?>" method="post">
<div class="row" id="patchRow-<?php echo $songPatchId;?>" 
data-song-patch-id="<?php echo $songPatchId;?>" 
data-song-id="<?php echo intval($_GET['song_id']);?>">

<div class="col-md-3">

<div class="form-group">
<select class="form-control device-selector" name="device-<?php echo $songPatchId;?>" id="device-<?php echo $songPatchId;?>">
<?php 
foreach($devices as $deviceId => $deviceName) {
	$selectedAttr= $deviceId == $patchData['device_id'] ? ' selected="selected"' : '';
	echo "<option value=\"$deviceId\"$selectedAttr readonly>$deviceName</option>";
}
?>
</select>
</div><!-- .form-group -->

</div><!-- .col-XXX -->

<div class="col-md-1">

<div class="form-group">
<select class="form-control channel-selector" name="channel-<?php echo $songPatchId;?>" id="channel-<?php echo $songPatchId;?>">
<?php 
for($ch = 0; $ch < 16; $ch++) {
	$selectedAttr= $ch == $patchData['channel'] ? ' selected="selected"' : '';
	$disabledAttr = ($ch != $patchData['channel'] && in_array($ch, $usedChannels[$patchData['device_id']])) ? ' disabled="disabled"' : '';
	echo "<option value=\"$ch\"{$selectedAttr}{$disabledAttr}>" . ($ch + 1) . '</option>';
}
?>
</select>
</div><!-- .form-group -->
</div><!-- .col-XXX -->

<div class="col-md-2">
<div class="form-group">
<select class="form-control bank-selector" name="bank-<?php echo $songPatchId;?>" id="bank-<?php echo $songPatchId;?>">
<?php 

// get all banks for this device
$sql = "SELECT * FROM banks WHERE bank_device={$patchData['device_id']}";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	$selectedAttr = ($row['bank0_no'] == $patchData['bank0'] && $row['bank32_no'] == $patchData['bank32']) ? ' selected="selected"' : '';
	echo "<option value=\"{$row['bank0_no']}-{$row['bank32_no']}\"$selectedAttr>";
	echo "{$row['bank0_no']} {$row['bank32_no']} [{$row['bank_name']}]</option>";
}
?>
</select>
</div><!-- .form-group -->
</div><!-- .col-XXX -->

<div class="col-md-2">

<div class="form-group">
<select class="form-control patch-selector" name="patch-<?php echo $songPatchId;?>">
<?php 
// get all patches for current bank
$sql = "SELECT * FROM patches WHERE patch_device={$patchData['device_id']} AND patch_bank0={$patchData['bank0']} AND patch_bank32={$patchData['bank32']}";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	$selectedAttr = $row['patch_no'] == $patchData['patch'] ? ' selected="selected"' : '';
	echo "<option value=\"{$row['patch_no']}\"$selectedAttr>";
	echo "{$row['patch_no']} [{$row['patch_name']}]</option>";
}
?>
</select>
</div><!-- .form-group -->

</div><!-- .col-XXX -->

<div class="col-md-1">
<div class="form-group">
<input type="number" min="-1" max="127" class="form-control volume-input" name="volume-<?php echo $songPatchId;?>" 
id="volume-<?php echo $songPatchId;?>"  placeholder="Volume"
value="<?php echo is_numeric($patchData['volume']) ? $patchData['volume'] : "";?>">
</div>
</div><!-- .col-XXX -->

<div class="col-md-1">
<div class="form-group">
<input type="number" min="0" max="127" class="form-control expression-input" name="expression-<?php echo $songPatchId;?>" 
id="expression-<?php echo $songPatchId;?>" placeholder="Expression"
value="<?php echo is_numeric($patchData['expression']) ? $patchData['expression'] : "";?>">
</div>
</div><!-- .col-XXX -->

<div class="col-md-2">
<button class="btn btn-primary song-patch-save" type="button" name="save-<?php echo $songPatchId;?>">Save</button>&nbsp;
<button class="btn btn-warning song-patch-remove" type="button" name="remove-<?php echo $patchData['device_id'];?>">Remove</button>
</div><!-- .col-XXX -->

</div><!-- .row -->

<div class="row" data-song-patch-id="<?php echo $songPatchId;?>" data-song-id="<?php echo intval($_GET['song_id']);?>">
<div class="col-md-10">
<div class="form-group">
<textarea class="form-control txt-midi-custom" rows="1" placeholder="custom midi cc"><?php echo $patchData['custom_cc'];?></textarea>
</div>
</div><!-- .col-XXX -->

<div class="col-md-2">
</div><!-- .col-XXX -->

</div><!-- .row -->
</form>

<?php endforeach; // foreach($patches as $patchData) ?>

<div class="row">
<div class="col-md-12">
<label>Add patch</label>
</div><!-- .col-XXX -->
</div><!-- .row -->

<!-- Additional row for adding patches -->
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
<button class="btn btn-primary song-patch-save" type="button" name="save" id="save-new-song-patch">Save</button>
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

<div class="row">
<div class="col-md-12">
<a href="songs.php"><button>Back to songs</button></a>
</div><!-- .col-XXX -->
</div><!-- .row -->

</div><!-- .container -->

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
