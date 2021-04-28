<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$device_id = filter_input(INPUT_POST, 'delete_device');
		
		try {

			$query = "DELETE FROM `home_automation`.`devices` 
					  WHERE `device_id` = :device_id ";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(":device_id",$device_id);
			$stmt->execute();
			$stmt->closeCursor();

			header('Location: index.php');
			
		} catch (Exception $e) {
			echo $e;
		}
	}
 ?>