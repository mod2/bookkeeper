<?php
$theFile = 'data/goals.dat';
$entriesFile = 'data/entries.dat';
if (!file_exists($theFile) || !file_exists($entriesFile)) {
	$f = fopen($theFile, 'w');
	fclose($f);
	$f = fopen($entriesFile, 'w');
	fclose($f);
}
$goals = file($theFile);
$id = (array_key_exists('id', $_GET) && trim($_GET['id']) != '') ? intval($_GET['id']) : 0;

if ($id == 0 || count($goals) == 0) {
	die();
} else {
	$content = '';
	foreach ($goals as $goal) {
		$parts = explode(',', $goal);
		if (intval($parts[0]) != $id) {
			$content .= $goal;
		}
	}

	$fi = fopen($theFile, 'w');
	fwrite($fi, $content);
	fclose($fi);

	$content = '';
	$entries = file($entriesFile);
	foreach ($entries as $entry) {
		$parts = explode(',', $entry);
		if (intval($parts[1]) != $id) {
			$content .= $entry;
		}
	}

	$fi = fopen($entriesFile, 'w');
	fwrite($fi, $content);
	fclose($fi);
}
?>
