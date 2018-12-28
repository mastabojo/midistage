<?php
include '../lib/global.php';
// song ID (0 -> new song)
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$heading1 = $id == 0 ? 'Add songlist' : 'Edit songlist';

$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

// edit songlist
if($id > 0) {
    $sql = "SELECT * FROM songlists WHERE songlist_id=$id";
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

<form method="post" action="songlists.php">
<div class="form-group">
<label for="name">Name</label>
<input type="text" class="form-control" id="name" name="name" placeholder="Name" value="
<?php echo isset($row['songlist_name']) ? $row['songlist_name'] : '';?>">
</div>
<div class="form-group">
<label for="description">Description</label>
<input type="text" class="form-control" id="description" name="description" placeholder="Description" value="
<?php echo isset($row['songlist_description']) ? $row['songlist_description'] : '';?>">
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