<?php
$theFile = 'data/goals.dat';
if (!file_exists($theFile)) {
	$f = fopen($theFile, 'w');
	fclose($f);
}
$goals = file($theFile);
$id = (array_key_exists('id', $_GET) && trim($_GET['id']) != '') ? intval($_GET['id']) : 0;
$name = (array_key_exists('name', $_GET) && trim($_GET['name']) != '') ? trim($_GET['name']) : '';
$totalPages = (array_key_exists('totalpages', $_GET) && trim($_GET['totalpages']) != '') ? intval($_GET['totalpages']) : 0;
$startDate = (array_key_exists('startdate', $_GET) && trim($_GET['startdate']) != '') ? trim($_GET['startdate']) : '';
$endDate = (array_key_exists('enddate', $_GET) && trim($_GET['enddate']) != '') ? trim($_GET['enddate']) : '';
$readingDays = (array_key_exists('readingdays', $_GET) && trim($_GET['readingdays'] != '')) ? trim($_GET['readingdays']) : '0000000';
$hidden = (array_key_exists('hidden', $_GET) && trim($_GET['hidden'] != '')) ? intval($_GET['hidden']) : 0

if ($name == '' || $totalPages == 0 || $startDate == '' || $endDate == '' || $readingDays == '0000000') {
	die();
}

if ($id == 0) {
	$last = explode(',', $goals[count($goals) - 1]);
	$newId = intval($last[0]) + 1;
	$fin = fopen($theFile, 'a');
	fwrite($fin, strval($newId) . ',' . $name . ',' . strval($totalPages) . ',' . $startDate . ',' . $endDate . ',' . $readingDays . ',' . $hidden . "\n");
} else {
	$content = '';
	foreach ($goals as $goal) {
		$parts = explode(',', $goal);
		if (intval($parts[0]) == $id) {
			$content .= $parts[0] . ',' . $name . ',' . strval($totalPages) . ',' . $startDate . ',' . $endDate . ',' . $readingDays . ',' . strval($hidden) . "\n");
		} else {
			$content .= $goal;
		}
	}

	$fi = fopen($theFile, 'w');
	fwrite($fi, $content);
}
?>
