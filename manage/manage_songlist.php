<?php
/*
 * Songlist management
 * - adding, rearranging and removing songs from a songlist
 */
include '../lib/global.php';
// songlist ID
$id = intval($_GET['id']);

if($id <= 0) {
	header("Location:songlists.php");
	exit();
}

$heading1 = 'Manage songlist';

$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// songlist info
$sql = "SELECT * FROM songlists WHERE songlist_id=$id";
$songlistInfo = $db->querySingle($sql, true);

// songs ids of this songlist (to exclude them from the dropdown for adding songs)
$sql = "SELECT songlist_song_id FROM songlist_songs WHERE songlist_songlist_id=$id";
$results = $db->query($sql);
$assignedSongs = [];

while($row = $results->fetchArray(SQLITE3_ASSOC)) {
	$assignedSongs[] = $row['songlist_song_id'];
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
<h1>Manage songlist</h1>

<?php echo "<p>Songlist: {$songlistInfo['songlist_name']} ({$songlistInfo['songlist_description']})</p>";?>

<div class="form-group">
<label for="select-song">Add song</label><br> 
<select name="select-song" id="select-song" data-songlist="<?php echo $id;?>">
<option value="-1" disabled selected>-- Add song --</option>
<?php
$sql = "SELECT song_id, song_title FROM songs ORDER BY song_title ASC";
$results = $db->query($sql);
while($row = $results->fetchArray(SQLITE3_ASSOC)){
	// don't add songs that are already in the songlist
	if(in_array($row['song_id'], $assignedSongs)) {
		continue;
	}
	echo "<option value=\"{$row['song_id']}\">";
	echo "{$row['song_title']}</option>" . NL;
}
?>
</select>
<a href="songlists.php"><button>Back to songlists</button></a>
</div>

<table class="table table-striped item-table" id="songlist_songs">
<thead>
<tr><th>Title</th><th><span class="glyphicon glyphicon-remove"></span></th></tr>
</thead>
<tbody>
<?php 
// songs

$sql = "SELECT songlist_song_id, song_title, songlist_song_order FROM songlist_songs
JOIN songs ON songlist_song_id=song_id
WHERE songlist_songlist_id=$id
ORDER BY songlist_song_order ASC";
$results = $db->query($sql);

while($row = $results->fetchArray(SQLITE3_ASSOC)) {
	echo '<tr id="item-' . $row['songlist_song_id'] . '">';
	echo '<td>' . $row['song_title'] . '</td>';
	echo '<td><span class="glyphicon glyphicon-remove item-remove" ';
	echo "data-songlist-id=\"$id\" data-song-id=\"{$row['songlist_song_id']}\"></span></td>";
	echo '</tr>' . "\n";
}
?>
</tbody>
</table>

<a href="songlists.php"><button>Back to songlists</button></a>

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
<script>$('tbody').sortable({
    axis: "y",
    cursor: "move",
    delay: 150,
    update: function (event, ui) {
        var data = $(this).sortable('serialize') + '&songlist_id=<?php echo $id;?>';
      	$.ajax({
          	data: data,
          	type: 'POST',
          	url: 'songlist_reorder.php'
      	});
  	}
});
</script>

</body>
</html>