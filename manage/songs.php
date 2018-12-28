<?php
include '../lib/global.php';
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

if(isset($_GET['category']) && is_numeric($_GET['category'])) {
	$where = 'WHERE song_category=' . intval($_GET['category']);
} else {
	$where = '';
}

// some data has been submited from edit_song.php
if(isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $title = $db->escapeString($_POST['title']);
    $description = $db->escapeString($_POST['description']);
    $key = $db->escapeString($_POST['key']);
    $tempo = $db->escapeString($_POST['tempo']);
    $category = intval($_POST['category']);
    $notes = $db->escapeString($_POST['notes']);
    $sql = $id == 0 ?
    "INSERT INTO songs (song_title, song_description, song_category, song_key, song_tempo, song_notes) VALUES 
    ('$title', '$description', $category, '$key', $tempo, '$notes')" :
    "UPDATE songs SET song_title='$title', song_description='$description', song_category=$category, song_key='$key', song_tempo=$tempo, song_notes='$notes'
    WHERE song_id=$id";
    $db->exec($sql);
    unset($_POST);
}

// song categories
$sql = "SELECT * FROM categories ORDER BY category_name";
$categories = [];
$results = $db->query($sql);
while($row = $results->fetchArray(SQLITE3_ASSOC)) {
	$categories[$row['category_id']] = $row['category_name'];
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

<h2>Songs</h2>

<div class="form-group">
<a href="edit_song.php">
<button type="button" class="btn btn-primary">Add song</button>
</a>
</div>

<form name="category" action="songs.php" method="get">
<div class="form-group">
<label for="category">Category</label>  
<select class="form-control autosubmit" name="category">
<option value="">-- All categories --</option>
<?php
foreach($categories as $category_id => $category_name) {
    echo '<option value="' . $category_id . '"';
    echo (isset($_GET['category']) && $_GET['category'] == $category_id) ? '  selected="selected">' : '>';
    echo "$category_name</option>";
}
?>
</select>
</div> 
</form>

<table class="table table-striped item-table" id="songs">
<tr><th>Title</th><th>Description</th><th>Key</th><th>Tempo</th><th>Category</th>
<th><span class="glyphicon glyphicon-music"></span></th>
<th><span class="glyphicon glyphicon-remove"></span></th></tr>

<?php
$sql = "SELECT * FROM songs  $where ORDER BY song_title ASC";
$results = $db->query($sql);
while($row = $results->fetchArray(SQLITE3_ASSOC)) {
    echo '<tr data-entity="song" data-id="' . $row['song_id'] . '" class="row-clickable">';
    echo '<td class="edit-entity">' . $row['song_title'] . '</td>';
    echo '<td class="edit-entity">' . $row['song_description'] . '</td>';
    echo '<td class="edit-entity">' . $row['song_key'] . '</td>';
    echo '<td class="edit-entity">' . $row['song_tempo'] . '</td>';
    echo '<td class="edit-entity">' . $categories[$row['song_category']] . '</td>';
    echo '<td><a href="patch_assign.php?song_id=' . $row['song_id'] . '"><span class="glyphicon glyphicon-music"></span></a></td>';
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
