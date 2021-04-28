<?php 
	require_once('../include/config.php');
		
	$query = "SELECT 
				  `location_id`,
				  `location_name`,
				  `date_created` 
				FROM
				  `home_automation`.`locations` ";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$locations = $stmt->fetchAll();
	$stmt->closeCursor();
 ?>