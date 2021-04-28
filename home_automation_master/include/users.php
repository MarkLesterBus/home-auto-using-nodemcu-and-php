<?php 
/**
  * 
  */
require_once 'config.php';  
session_start();  
class Users 
{
	
	private $userStatus="";

	function getUserStatus(){
		return $this->userStatus;
	}
	function setUserStatus($status){
		$this->userStatus = $status;
	}

	function userRegistration($email,$pass,$fname,$utype,$uimage){
		try {
			$password = md5($pass);

			$query = "INSERT INTO `home_automation`.`users` (`user_email`,`user_pass`,`user_fname`,`user_type`,`user_image`,`date_created`) 
					 VALUES(:user_email,:user_pass,:user_fname,:user_type,:user_image,NOW())";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(":user_email",$email);
			$stmt->bindValue(":user_pass",$password);
			$stmt->bindValue(":user_fname",$fname);
			$stmt->bindValue(":user_type",$utype);
			$stmt->bindValue(":user_image", $uimage);

			$stmt->execute();
			$stmt->closeCursor();
			$this->setUserStatus("A new user is created!");
		} catch (Exception $e) {
			$this->setUserStatus("Unable to create new user!");
		}
		

	}
	function userLogin($email,$pass){
		$password = md5($pass);
		$query = "SELECT *
				  FROM `home_automation`.`users` 
				  WHERE `user_email` = :user_email AND `user_pass` = :user_pass";
		$stmt = $conn->prepare($query);
		$stmt->bindValue(':user_email',$email);
		$stmt->bindValue(':user_pass',$password);
		$stmt->execute();

		$rs = $stmt->fetchAll();
		if ($rs->row_count() == 1) {
			foreach ($rs as $result) {
				$_SESSION['login'] = true;  
	            $_SESSION['uid'] = $result['user_id'];  
	            $_SESSION['email'] = $result['user_email'];  
	            $_SESSION['fname'] = $result['user_fname']; 
	            $_SESSION['uimage'] = $result['user_image']; 
	            $this->isUser($result['user_type']);
			}
			 $this->setUserStatus('Login Successful! Welcome User');
            
        }  
        else  
        {  
        	$this->setUserStatus('Login Failure! Invalid Username or Password');
              
        }  
               
	}

	function isUser($type){
		if ($type == "Administrator") {
			$_SESSION['utype'] = "Administrator";
		}
		if ($type == "Family Member") {
			$_SESSION['utype'] = "Family Member";
		}
		if ($utype == "Guest") {
			$_SESSION['utype'] = "Guest";
		}
	}


} 
 ?>