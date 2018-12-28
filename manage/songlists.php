<?php
include '../lib/global.php';
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// some data has been submited from edit_songlist.php
if(isset($_POST['submit'])) {
	$id = intval($_POST['id']);
	$name = $db->escapeString($_POST['name']);
	$description = $db->escapeString($_POST['description']);
	$sql = $id == 0 ?
	"INSERT INTO songlists (songlist_name, songlist_description) VALUES
	('$name', '$description')" :
	"UPDATE songlists SET songlist_name='$name', songlist_description='$description' WHERE songlist_id=$id";
	
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

<h2>Songlists</h2>

<div class="form-group">
<a href="edit_songlist.php">
<button type="button" class="btn btn-primary">Add songlist</button>
</a>
</div>

<table class="table table-striped item-table">
<tr><th>Name</th><th>Description</th><th>Songs</th><th><span class="glyphicon glyphicon-remove"></span></th></tr>
<?php
$sql = 'SELECT * FROM songlists ORDER BY songlist_id';
$results = $db->query($sql);
while ($row = $results->fetchArray()) {
	
	$sql = "SELECT COUNT(*) FROM songlist_songs WHERE songlist_songlist_id={$row['songlist_id']}";
	$songCount = $db->querySingle($sql);
	
    echo '<tr data-entity="songlist" data-id="' . $row['songlist_id'] . '" class="row-clickable">';
    echo '<td class="edit-entity">' . $row['songlist_name'] . '</td>';
    echo '<td class="edit-entity">' . $row['songlist_description'] . '</td>';
    echo '<td class="manage-entity">' . $songCount. '</td>';
    echo '<td><span class="glyphicon glyphicon-remove item-remove"></span></td>';
    echo '</tr>' . "\n";
}
?>
</table>

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
