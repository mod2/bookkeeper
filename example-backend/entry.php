<?php
$theFile = 'data/entries.dat';
if (!file_exists($theFile)) {
	$f = fopen($theFile, 'w');
	fclose($f);
}
$entries = file($theFile);
$id = (array_key_exists('id', $_GET) && trim($_GET['id']) != '') ? intval($_GET['id']) : 0;
$goalId = (array_key_exists('goalid', $_GET) && trim($_GET['goalid']) != '') ? intval($_GET['goalid']) : 0;
$date = (array_key_exists('date', $_GET) && trim($_GET['date']) != '') ? trim($_GET['date']) : '';
if (($id == 0 && $goalId == 0) || ($goalId != 0 && $date == '')) {
	echo json_encode(array('error'=>'invalid request'));
	die();
}
$rtnEntry = array();
foreach ($entries as $entry) {
	$parts = explode(',', $entry);
	if ($id != 0 && intval($parts[0]) == $id) {
		$rtnEntry = array('id'=>intval($parts[0]), 'goalId'=>intval($parts[1]), 'page'=>intval($parts[2]), 'date'=>trim($parts[3]));
		break;
	} elseif ($id == 0 && $goalId != 0 && $goalId == intval($parts[1]) && trim($parts[3]) == $date) {
		$rtnEntry = array('id'=>intval($parts[0]), 'goalId'=>intval($parts[1]), 'page'=>intval($parts[2]), 'date'=>trim($parts[3]));
		break;
	}
}
echo json_encode($rtnEntry);
?>
