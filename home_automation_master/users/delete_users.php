<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$user_id = filter_input(INPUT_POST, 'delete_user');
		
		try {

			$query = "DELETE FROM `home_automation`.`users` 
					  WHERE `user_id` = :user_id ";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(":user_id",$user_id);
			$stmt->execute();
			$stmt->closeCursor();

			header('Location: index.php');
			
		} catch (Exception $e) {
			echo $e;
		}
	}
 ?>