<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$user_fname = filter_input(INPUT_POST, 'user_fname');
		$user_email = filter_input(INPUT_POST, 'user_email',FILTER_VALIDATE_EMAIL);
		$user_pass = filter_input(INPUT_POST, 'user_pass');
		$user_type = filter_input(INPUT_POST, 'user_type');
		$user_id = filter_input(INPUT_POST, 'edit_user');
		
		try {
			

			$query = "UPDATE 
					  `home_automation`.`users` 
					SET
					  `user_email` = :user_email,
					  `user_pass` = :user_pass,
					  `user_fname` = :user_fname,
					  `user_type` = :user_type,
					  `user_image`= :user_image
					WHERE `user_id` = :user_id";

			if (basename($_FILES["updateImageFile"]["name"]) == "") {
				echo "Errot";
			}else{
				$stmt = $conn->prepare($query);
				$stmt->bindValue(":user_email",$user_email);
				$stmt->bindValue(":user_pass",$user_pass);
				$stmt->bindValue(":user_fname",$user_fname);
				$stmt->bindValue(":user_type",$user_type);
			
				$stmt->bindValue(":user_image", basename($_FILES["updateImageFile"]["name"]));
				$stmt->bindValue(":user_id",$user_id);
				
				$stmt->execute();
				$stmt->closeCursor();

			header('Location: index.php');
			
			} 
		}catch (Exception $e) {
			echo $e;
		}
	}

 ?>