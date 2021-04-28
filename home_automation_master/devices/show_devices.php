<?php 
	require_once('../include/config.php');
		
	$query = "SELECT `devices`.*,`locations`.`location_name`
	FROM `devices` INNER JOIN `locations` On `locations`.`location_id` = `devices`.`location_id`";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$devices = $stmt->fetchAll();
	$stmt->closeCursor();
?>