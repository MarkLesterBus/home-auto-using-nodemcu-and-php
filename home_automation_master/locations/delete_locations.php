<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$location_id = filter_input(INPUT_POST, 'delete_location');
		
		try {

			$query = "DELETE FROM `home_automation`.`locations` 
					  WHERE `location_id` = :location_id ";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(":location_id",$location_id);
			$stmt->execute();
			$stmt->closeCursor();

			header('Location: index.php');
			
		} catch (Exception $e) {
			echo $e;
		}
	}
 ?>