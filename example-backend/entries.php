<?php
$theFile = 'data/entries.dat';
if (!file_exists($theFile)) {
	$f = fopen($theFile, 'w');
	fclose($f);
}
$entries = file($theFile);
$goalId = $_GET['id'];
$rtnEntries = array();
foreach ($entries as $entry) {
	$parts = explode(',', $entry);
	if ($parts[1] == $goalId) {
		$rtnEntries[] = array('id'=>intval($parts[0]), 'goalId'=>intval($parts[1]), 'page'=>intval($parts[2]), 'date'=>trim($parts[3]));
	}
}
echo json_encode($rtnEntries);
?>
