<?php 
session_start();
?>
<a href="../index.php?songlist=<?php echo isset($_SESSION['songlist_id']) ? $_SESSION['songlist_id'] : 0;?>">
<button type="button" class="btn btn-primary btn-xs pull-right">Performance mode</button>
</a>
<?php
$script = basename($_SERVER["SCRIPT_FILENAME"], '.php');
$tabs = ['songs', 'songlists', 'devices', 'patches', 'test', 'system'];
echo '<ul class="nav nav-tabs">';
foreach($tabs as $tab) {
	echo '<li';
	echo $tab == $script ? ' class="active">' : '>';
	echo '<a href="' . $tab . '.php">' . ucfirst($tab) . '</a></li>';
}
echo '</ul>';
?>
