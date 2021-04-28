<?php
$auth_error = "";
require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$email = filter_input(INPUT_POST, 'email');
		$pass = filter_input(INPUT_POST, 'password');
		
		try {

			$query = "SELECT 
			  `user_id`,
			  `user_email`,
			  `user_pass`,
			  `user_fname`,
			  `user_type`,
			  `user_image`,
			  `date_created` 
			FROM
			  `home_automation`.`users` 
			WHERE `user_email` = :user_email AND `user_pass` = :user_pass";


			$stmt = $conn->prepare($query);
			$stmt->bindValue(":user_email",$email);
			$stmt->bindValue(":user_pass",$pass);
			$stmt->execute();
			$rs_count = $stmt->rowCount();
			$rs = $stmt->fetchAll();
			$stmt->closeCursor();

			if ($rs_count > 0) {
				foreach ($rs as $result) {

					$_SESSION['auth'] = true;  
		            $_SESSION['uid'] = $result['user_id'];  
		            $_SESSION['email'] = $result['user_email'];  
		            $_SESSION['fname'] = $result['user_fname']; 
		            $_SESSION['uimage'] = $result['user_image'];
		            $_SESSION['utype'] = $result['user_type']; 
				}
				header("Location: ../main/index.php");
	        }  
	        else  
	        {  
	        	$auth_error = "Invalid Username or Password";
	        }  
			
		} catch (Exception $e) {
			echo $e;
		}
	}
?>