<?php
$theFile = 'data/goals.dat';
if (!file_exists($theFile)) {
	$f = fopen($theFile, 'w');
	fclose($f);
}
$goals = file($theFile);
$rtnGoals = array();
foreach ($goals as $goal) {
	$parts = explode(',', $goal);
	$theGoal = array('id'=>intval($parts[0]), 'name'=>$parts[1], 'totalPages'=>intval($parts[2]), 'startDate'=>$parts[3], 'endDate'=>$parts[4]);
	$readingDays = array();
	for ($i = 0; $i < 7; $i++) {
		$readingDays[$i] = intval($parts[5][$i]);
	}
	$theGoal['readingDays'] = $readingDays;
	$theGoal['hidden'] = intval($parts[6]);
	$rtnGoals[] = $theGoal;
}
echo json_encode($rtnGoals);
?>
