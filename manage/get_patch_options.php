<?php
/*
 * Returns options for related select element (triggered on change) 
 * 
 * $_POST['element'] - changed element (devices select | bank select) -defines the target
 * $_POST['device'] - device ID
 * Additional values when bank selector was clicked
 * $_POST['value0']
 * $_POST['value32']
 */

if(!in_array($_POST['element'], ['device-selector', 'bank-selector']) || !is_numeric($_POST['device'])) {
	exit();
}

// data contains additional parameters
if($_POST['element'] == 'bank-selector' && 
	(!isset($_POST['value0']) || !is_numeric($_POST['value0']) || 
	!isset($_POST['value32']) || !is_numeric($_POST['value32']))) {
	exit();
}

$dbFile = '../data/mpdata.sqlite';
$db = new SQLite3($dbFile);

$element = $_POST['element'];
$deviceId = intval($_POST['device']);
if($element == 'bank-selector') {
	$bank0 = intval($_POST['value0']);
	$bank32 = intval($_POST['value32']);
}

// Array of HTML strings of options to be returned
$options['bank'] = '';
$options['patch'] = '';
$optionsObj['bank'] = [];
$optionsObj['patch'] = [];

// device selector clicked - prepare options for related bank selector and patches for 1st bank
if($_POST['element'] == 'device-selector') {
	// banks for bank selector
	$sql =  "SELECT bank0_no, bank32_no, bank_name FROM banks WHERE bank_device=$deviceId ORDER BY bank_device, bank0_no, bank32_no";
	$result = $db->query($sql);
	$firstBankSaved = false;
	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		// save first bank numbers (0 and 32) for querying patches
		if(!$firstBankSaved) {
			$firstBank0 = $row['bank0_no'];
			$firstBank32 = $row['bank32_no'];
			$firstBankSaved = true;
		}
		// $options['bank'] .= "<option value=\"{$row['bank0_no']}-{$row['bank32_no']}\">";
		// $options['bank'] .= "{$row['bank_name']}</option>";
		
		$optionsObj['bank']["{$row['bank0_no']}-{$row['bank32_no']}"] = "{$row['bank0_no']}-{$row['bank32_no']} [{$row['bank_name']}]" ;
	}
	// patches for first bank of the bank selector
	$sql =  "SELECT patch_no, patch_name FROM patches WHERE patch_device=$deviceId AND patch_bank0=$firstBank0 AND patch_bank32=$firstBank32 ORDER BY patch_no";
	$result = $db->query($sql);
	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		// $options['patch'] .= "<option value=\"{$row['patch_no']}\">";
		// $options['patch'] .= "{$row['patch_name']}</option>";
		
		$optionsObj['patch'][$row['patch_no']] = "{$row['patch_no']} [{$row['patch_name']}]";
	}
// bank selector clicked - prepare options for related patch selector
} elseif($_POST['element'] == 'bank-selector') {
	$sql =  "SELECT patch_no, patch_name FROM patches WHERE patch_device=$deviceId AND patch_bank0=$bank0 AND patch_bank32=$bank32 ORDER BY patch_no";
	$result = $db->query($sql);
	while($row = $result->fetchArray(SQLITE3_ASSOC)) {
		// $options['patch'] .= "<option value=\"{$row['patch_no']}\">";
		// $options['patch'] .= "{$row['patch_no']} [{$row['patch_name']}]</option>";
		
		$optionsObj['patch'][$row['patch_no']] = "{$row['patch_no']} [{$row['patch_name']}]";
	}
} else {
	exit();
}
// error_log(json_encode($optionsObj, JSON_FORCE_OBJECT));
// echo json_encode($options);
echo json_encode($optionsObj, JSON_FORCE_OBJECT);


