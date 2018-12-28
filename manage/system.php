<?php
include '../lib/global.php';
$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

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

<h2>System</h2>

<div class="row">
<div class="col-sm-4 center-el">
<div class="control">
<div class="control-marker"><img id="exit-browser" src="../img/btn-exit-browser.svg"></div><br>
exit browser
</div><!-- .control -->
</div><!-- .col -->
<div class="col-sm-4 center-el">
<div class="control">
<div class="control-marker"><img id="reboot" src="../img/btn-reboot.svg"></div><br>
reboot
</div><!-- .control -->
</div><!-- .col -->
<div class="col-sm-4 center-el">
<div class="control">
<div class="control-marker"><img id="shutdown" src="../img/btn-shutdown.svg"></div><br>
shutdown
</div><!-- .control -->
</div><!-- .col -->
</div><!-- .row -->

<script src="../js/jquery-2.2.4.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/main.js"></script>
</body>
</html>
