<?php
include '../lib/global.php';
// song ID (0 -> new song)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$heading1 = $id == 0 ? 'Add song' : 'Edit song';

$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// get categories
$sql = "SELECT * FROM categories";
$result = $db->query($sql);
$categories = [];
while($row = $result->fetchArray()) {
	$categories[$row['category_id']] = $row['category_name'];
}

// edit song
if($id > 0) {
    $sql = "SELECT * FROM songs WHERE song_id=$id";
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

<form method="post" action="songs.php">
<div class="form-group">
<label for="title">Title</label>
<input type="text" class="form-control" id="titleaction=" name="title" placeholder="Title" value="
<?php echo isset($row['song_title']) ? $row['song_title'] : '';?>">
</div>
<div class="form-group">
<label for="description">Description</label>
<input type="text" class="form-control" id="description" name="description" placeholder="Description" value="
<?php echo isset($row['song_description']) ? $row['song_description'] : '';?>">
</div>
<div class="form-group">
<label for="key">Key</label>
<input type="text" class="form-control" id="key" name="key" placeholder="Key"
 value="<?php echo isset($row['song_key']) ? $row['song_key'] : '';?>">
</div>
<div class="form-group">
<label for="tempo">Tempo</label>
<input type="text" class="form-control" id="tempo" name="tempo" placeholder="Tempo" value="<?php echo isset($row['song_tempo']) ? $row['song_tempo'] : '';?>"
>
</div>
<div class="form-group">
<label for="category">Category</label>  
<select class="form-control" name="category">
<option value="-1">-- Select category --</option>
<?php
foreach($categories as $category_id => $category_name) {
    echo '<option value="' . $category_id . '"';
    echo (isset($row['song_category']) && $row['song_category'] == $category_id) ? '  selected="selected">' : '>';
    echo "$category_name</option>";
}
?>
</select>
</div> 
<div class="form-group">
<label for="notes">Notes</label>
<textarea class="form-control" rows="3" id="notes" name="notes">
<?php echo isset($row['song_notes']) ? $row['song_notes'] : '';?></textarea>
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