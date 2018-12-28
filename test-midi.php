<?php
// command and arguments
// python set_devices.py '[{"port": 2, "channel": 0, "bank0": 0, "bank32": 6, "program": 22}, {"port": 4, "channel": 1, "bank32": 0, "bank0": 0, "program": 5}]'

$pyScript = 'set_devices.py';
$pythonExec = '/usr/bin/python';
$pyArgs = '[{"port": 2, "channel": 0, "bank0": 0, "bank32": 6, "program": 22}, {"port": 4, "channel": 1, "bank32": 0, "bank0": 0, "program": 5}]';
$cmd = "sudo -u www-data $pythonExec $pyScript '$pyArgs'";

echo "\n\n $cmd \n\n";
$output = shell_exec($cmd);
echo "\n\n";
echo $output;
echo "\n\n";
?>

