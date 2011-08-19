<?php
$theFile = 'data/goals.dat';
if (!file_exists($theFile)) {
	$f = fopen($theFile, 'w');
	fclose($f);
}
$goals = file($theFile);
$goalId = (array_key_exists('id', $_GET) && trim($_GET['id']) != '') ? intval($_GET['id']) : 0;
if ($goalId == 0) {
	echo json_encode(array('error'=>'invalid request'));
	die();
}
$rtnGoal = array();
foreach ($goals as $goal) {
	$parts = explode(',', $goal);
	if ($goalId == intval($parts[0])) {
		$rtnGoal = array('id'=>intval($parts[0]), 'name'=>$parts[1], 'totalPages'=>intval($parts[2]), 'startDate'=>$parts[3], 'endDate'=>$parts[4]);
		$readingDays = array();
		for ($i = 0; $i < 7; $i++) {
			$readingDays[$i] = intval($parts[5][$i]);
		}
		$rtnGoal['readingDays'] = $readingDays;
		break;
	}
}
echo json_encode($rtnGoal);
?>
