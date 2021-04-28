<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$device_name = filter_input(INPUT_POST, 'device_name');
		$device_ip = filter_input(INPUT_POST, 'device_ip');
		$device_status = filter_input(INPUT_POST, 'device_status');
		$location_id = filter_input(INPUT_POST, 'location_id');
		$device_type = filter_input(INPUT_POST, 'device_type');
		$device_id = filter_input(INPUT_POST, 'edit_device');
		
		try {

			$query = "UPDATE 
						  `home_automation`.`devices` 
						SET
						  
						  `device_name` = :device_name,
						  `device_type` = :device_type,
						  `device_ip` = :device_ip,
						  `device_status` = :device_status,
						  `location_id` = :location_id,
						  `date_created` = NOW()
						WHERE `device_id` = :device_id";


			$stmt = $conn->prepare($query);
			$stmt->bindValue(":device_id",$device_id);
			$stmt->bindValue(":device_name",$device_name);

			if ($device_status=="ON") {
				$stmt->bindValue(":device_status",1);
			}else {
				$stmt->bindValue(":device_status",0);
			}

			$stmt->bindValue(":device_type",$device_type);
			$stmt->bindValue(":device_ip",$device_ip);
			$stmt->bindValue(":location_id",$location_id);
			
			$stmt->execute();
			$stmt->closeCursor();

			header('Location: index.php');
			
		} catch (Exception $e) {
			echo $e;;
		}
	}
 ?>