<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$device_name = filter_input(INPUT_POST, 'device_name');
		$device_ip = filter_input(INPUT_POST, 'device_ip');
		$device_status = filter_input(INPUT_POST, 'device_status');
		$device_location = filter_input(INPUT_POST, 'device_location');
		$device_type = filter_input(INPUT_POST, 'device_type');

		
		try {

			$query = "INSERT INTO `home_automation`.`devices` (
					 
					  `device_name`,
					  `device_type`,
					  `device_ip`,
					  `device_status`,
					  `location_id`,
					  `date_created`
					) 
					VALUES
					  (
					   
					    :device_name,
					    :device_type,
					    :device_ip,
					    :device_status,
					    :location_id,
					    NOW())";


			$stmt = $conn->prepare($query);
			$stmt->bindValue(":device_name",$device_name);

			if ($device_status=="ON") {
				$stmt->bindValue(":device_status",1);
			}else {
				$stmt->bindValue(":device_status",0);
			}
			$stmt->bindValue(":device_type",$device_type);
			$stmt->bindValue(":device_ip",$device_ip);
			
			$stmt->bindValue(":location_id",$device_location);
			
			$stmt->execute();
			$stmt->closeCursor();
			header('Location: index.php');
			
		} catch (Exception $e) {
			echo $e;
		}
	}
 ?>