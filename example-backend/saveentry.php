<?php
$entries = file('entries.dat');
$id = (array_key_exists('id', $_GET) && trim($_GET['id']) != '') ? intval($_GET['id']) : 0;
$goalId = (array_key_exists('goalid', $_GET) && trim($_GET['goalid']) != '') ? intval($_GET['goalid']) : 0;
$page = (array_key_exists('page', $_GET) && trim($_GET['page']) != '') ? intval($_GET['page']) : 0;
$date = (array_key_exists('date', $_GET) && trim($_GET['date']) != '') ? trim($_GET['date']) : '';

if ($id == 0) {
	$last = explode(',', $entries[count($entries) - 1]);
	$newId = intval($last[0]) + 1;
	$fin = fopen('entries.dat', 'a');
	fwrite($fin, strval($newId) . ',' . strval($goalId) . ',' . strval($page) . ',' . $date . "\n");
} else {
	$rtnEntry = array();
	$content = '';
	foreach ($entries as $entry) {
		$parts = explode(',', $entry);
		if (intval($parts[0]) == $id) {
			$content .= strval($id) . ',' . strval($goalId) . ',' . strval($page) . ',' . $date . "\n";
		} else {
			$content .= $entry;
		}
	}

	$fi = fopen('entries.dat', 'w');
	fwrite($fi, $content);
}
?>
