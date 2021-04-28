<?php 
	require_once('../include/config.php');
		
	$query = "SELECT 
				  `location_id`,
				  `location_name`,`location_image`,
				  `date_created` 
				FROM
				  `home_automation`.`locations` ";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$locations = $stmt->fetchAll();
	$stmt->closeCursor();


	$query = "SELECT `device_type`, `location_id` FROM `home_automation`.`devices` GROUP BY `device_type`";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$device_types = $stmt->fetchAll();
	$stmt->closeCursor();

	
 ?>