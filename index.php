<?php
session_start();
include 'lib/global.php';
$dbFile = 'data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// build an array of devices
$sql = "SELECT * FROM devices WHERE device_active=1 ORDER BY device_name";
$result = $db->query($sql);
$devices = [];
while($row = $result->fetchArray()) {
    $devices[$row['device_id']] = $row['device_name'];
}
// get first device ID
$deviceIds = array_keys($devices);
$firstDeviceId = $deviceIds[0];

// build an array of songlists
$sql = "SELECT * FROM songlists ORDER BY songlist_name";
$result = $db->query($sql);
$songlists = [];
while($row = $result->fetchArray()) {
    $songlists[$row['songlist_id']] = $row['songlist_name'];
}
// default songlist (all songs)
$list_id = 0;
// song list ID
if(isset($_GET['songlist']) && array_key_exists($_GET['songlist'], $songlists)) {
    $list_id = intval($_GET['songlist']);
}
$_SESSION['songlist_id'] = $list_id;
// do I show the table titke
$showTableTitle = false;

// do I show the patch data below the song
$showPatchData = false;
?>
<!DOCTYPE html>
<html lang="sl">
<head>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>MIDI Stage</title>
<meta name="description" content="midistage">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="apple-touch-icon" href="apple-touch-icon.png">
<!-- Place favicon.ico in the root directory -->

<!-- link rel="stylesheet" href="css/normalize.css" -->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/main.css">
</head>

<body class="performance">

<div class="container" id="container">

<div id="title-div">
<div>&nbsp;</div>

<div class="row">
<div class="col-sm-5">

<form class="form-inline" action="<?php echo $_SERVER['SCRIPT_NAME'];?>" method="GET">
<div class="form-group">
<select name="songlist" class="autosubmit">
<option value="0">All songs</option>
<?php
foreach($songlists as $sl_id => $sl_name) {
    echo "<option value=\"$sl_id\"";
    echo $sl_id == $list_id ? ' selected="selected"' : '';
    echo ">$sl_name</option>" . NL;
}
?>
</select>
</div><!-- .form-group -->
</form> 
</div><!-- .col -->

<div class="col-sm-2 center-el">
<h4>midistage</h4>
</div><!-- .col -->

<div class="col-sm-5">

<div class="pull-right">
<!-- input class="btn btn-danger btn-xs" id="btn-panic" type="button" value="P A N I C" -->
<input class="btn btn-primary btn-xs" id="btn-show-devices" type="button" value="Show devices">
<a href="manage/songs.php">
<input class="btn btn-primary btn-xs" type="button" value="Manage">
</a>
</div>


</div><!-- .col -->
</div><!-- .row -->
</div><!-- #title-div -->
<?php
// select all songs for the current song list
if($list_id > 0) {
	$sql = "SELECT * FROM songlist_songs
	JOIN songs ON songlist_song_id=song_id
	WHERE songlist_songlist_id=$list_id
	ORDER BY songlist_song_order";
// if no current song list select all songs from the songs table
} else {
	$sql = "SELECT *  FROM songs ORDER BY song_title";
}
$result1 = $db->query($sql);
?>

<div id="songs-div">

<table class="table table-condensed tbl-songlist">
<?php if($showTableTitle): ?>
<tr><th></th><th>Song title</th><th>Key</th><th>Tempo</th></tr>
<?php endif;?>
<?php
$song_data_class = 'song-data-active';
$patch_data_class = 'patch-data-active';
$play_char_class = 'play-char-active';

while($row1 = $result1->fetchArray()) {
    // row containing song data
	echo "<tr id=\"{$row1['song_id']}\" class=\"$song_data_class\" data-song-id=\"{$row1['song_id']}\">";
    echo '<td class="' . $play_char_class . '"><span class="send-patch"><img src="img/btn-resend.svg"></span></td>';
    echo '<td class="td-title align-bottom">' . strtoupper($row1['song_title']) . '</td>';
    echo '<td class="td-key align-bottom">' . $row1['song_key'] . '</td>';
    echo '<td class="td-tempo align-bottom">' . $row1['song_tempo'] . '</td>';
    echo '</tr>' . "\n";
    
    if($showPatchData) {
        // get patch data for this song
        $sql = "SELECT * FROM song_patches 
        JOIN patches ON song_patch_patch=patch_id
        WHERE song_patch_song={$row1['song_id']}";DE($sql);
        $result2 = $db->query($sql);
        $patchData = '';
        while($row2 = $result2->fetchArray()) {
            $device = $devices[$row2['song_patch_device']];
            $channel = $row2['song_patch_channel'];
            $bank = $row2['song_patch_bank'];
            $patch = $row2['song_patch_patch'] . ' [' . $row2['patch_name'] . ']';
            $patchData .= "$device - CH: $channel BANK: $bank PATCH: $patch<br>";
        }
        // only display patch data if it exists
        if($patchData != '') {
            $patchData = rtrim($patchData, '<br>');
            // row containig patch data for all devices
            echo "<tr class=\"$patch_data_class\">";
            echo '<td></td>';
            echo '<td colspan="3">' . $patchData . '</td>';
            echo '</tr>' . "\n";
        }

        $patch_data_class = 'patch-data';
    }
    
    // reinitialize classes
    $song_data_class = 'song-data';
    $play_char_class = 'play-char';
}
?>
</table>

</div><!-- #songs-div -->

<!-- Controls -->
<div id="controls-div">
<div class="row">
<div class="col-sm-3 center-el">
<div class="control">
<div id="next-song-marker" class="control-marker"><img src="img/btn-next.svg"></div><br>
next song
</div><!-- .control -->
</div><!-- .col -->
<div class="col-sm-3 center-el">
<div class="control">
<div id="prev-song-marker" class="control-marker"><img src="img/btn-prev.svg"></div><br>
previous song
</div><!-- .control -->
</div><!-- .col -->
<div class="col-sm-3 center-el">
<div class="control">
<div id="resend-marker" class="control-marker"><img src="img/btn-resend.svg"></div><br>
resend
</div><!-- .control -->
</div><!-- .col -->
<div class="col-sm-3 center-el">
<div class="control">
<div id="resend-marker" class="control-marker"><img src="img/btn-panic.svg" id="btn-panic"></div><br>
panic
</div><!-- .control -->
</div><!-- .col -->
</div>
</div><!-- controls-div -->

<?php /*
<!-- Prestavi v svojo skripto -->

<div class="control-custom text-right align-bottom" id="control-custom">
<form class="form-inline ">
<label for="device-custom">device</label>
<div class="form-group">
<select class="form-control device-selector" name="device-custom" id="device-custom">
<?php 
foreach($devices as $deviceId => $deviceName) {
	echo "<option value=\"$deviceId\">$deviceName</option>";
}
?>
</select>
</div><!-- .form-group -->

<label for="channel-custom">channel</label>
<div class="form-group">
<select class="form-control channel-selector" name="channel-custom" id="channel-custom">
<?php 
for($ch = 0; $ch < 16; $ch++) {
	echo "<option value=\"$ch\">" . ($ch + 1) . '</option>';
}
?>
</select>
</div><!-- .form-group -->

<label for="bank-custom">bank</label>
<div class="form-group">
<select class="form-control bank-selector" name="bank-custom" id="bank-custom">
<?php 

// get all banks for this device
$sql = "SELECT * FROM banks WHERE bank_device=$firstDeviceId";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	echo "<option value=\"{$row['bank0_no']}-{$row['bank32_no']}\">";
	echo "{$row['bank0_no']}-{$row['bank32_no']} [{$row['bank_name']}]</option>";
}
?>
</select>
</div><!-- .form-group -->

<label for="patch-custom">patch</label>
<div class="form-group">
<select class="form-control patch-selector" name="patch-custom">
<?php 
// get all patches for current bank
$sql = "SELECT * FROM patches WHERE patch_device=$firstDeviceId AND patch_bank0=0 AND patch_bank32=0";
$result = $db->query($sql);
while($row = $result->fetchArray(SQLITE3_ASSOC)) {
	echo "<option value=\"{$row['patch_no']}\">";
	echo "{$row['patch_no']} [{$row['patch_name']}]</option>";
}
?>
</select>
</div><!-- .form-group -->

<div class="form-group">
<button class="btn btn-primary" type="button" name="send-custom" id="send-custom">Send</button>&nbsp;
</div><!-- .form-group -->

</form>
</div><!-- .control-custom -->
*/?>

</div><!-- #container -->

<div class="debug" style="display: <?php echo $debug ? 'block' : 'none';?>;">
<div class="debug-title">Debug window<span class="glyphicon glyphicon-remove pull-right close-window"></span></div>
<div class="debug-text"></div>
</div>

<div class="device-list" style="display: none;">
<div class="device-list-title">Devices<span class="glyphicon glyphicon-remove pull-right close-window"></span></div>
<div class="device-list-text"></div>
</div>

<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
var dbg = <?php echo $debug ? '1' : '0';?>
</script>
</body>
</html>
