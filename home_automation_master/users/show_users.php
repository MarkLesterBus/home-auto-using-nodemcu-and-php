<?php 
	require_once('../include/config.php');
		
	$query = "SELECT `user_id`,`user_email`,`user_pass`,`user_fname`,`user_type`,`user_image`,`date_created` 
			  FROM `home_automation`.`users` 
			  LIMIT 0, 1000 ";

	$stmt = $conn->prepare($query);
	$stmt->execute();
	$users = $stmt->fetchAll();
	$stmt->closeCursor();
 ?>