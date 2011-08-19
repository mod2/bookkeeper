<?php
$goals = file('goals.dat');
$rtnGoals = array();
foreach ($goals as $goal) {
	$parts = explode(',', $goal);
	$theGoal = array('id'=>intval($parts[0]), 'name'=>$parts[1], 'totalPages'=>intval($parts[2]), 'startDate'=>$parts[3], 'endDate'=>$parts[4]);
	$readingDays = array();
	for ($i = 0; $i < 7; $i++) {
		$readingDays[$i] = intval($parts[5][$i]);
	}
	$theGoal['readingDays'] = $readingDays;
	$rtnGoals[] = $theGoal;
}
echo json_encode($rtnGoals);
?>
