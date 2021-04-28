<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$user_fname = filter_input(INPUT_POST, 'user_fname');
		$user_email = filter_input(INPUT_POST, 'user_email',FILTER_VALIDATE_EMAIL);
		$user_pass = filter_input(INPUT_POST, 'user_pass');
		$user_type = filter_input(INPUT_POST, 'user_type');

		$target_dir = "../public/uploads/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$upload_error="";
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        $upload_error= "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;
		    } else {
		        $upload_error= "File is not an image.";
		        $uploadOk = 0;
		    }
		}

		// Check if file already exists
		if (file_exists($target_file)) {
		    $upload_error= "Sorry, file already exists.";
		    $uploadOk = 0;
		}

		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
		    $upload_error= "Sorry, your file is too large.";
		    $uploadOk = 0;
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $upload_error= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    $upload_error= "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		        $upload_error= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		    } else {
		        $upload_error= "Sorry, there was an error uploading your file.";
		    }
		}
		
		try {
			

			$query = "INSERT INTO `home_automation`.`users` (`user_email`,`user_pass`,`user_fname`,`user_type`,`user_image`,`date_created`) 
					 VALUES(:user_email,:user_pass,:user_fname,:user_type,:user_image,NOW())";
			if (basename($_FILES["fileToUpload"]["name"]) == "") {
				echo "Errot";
			}
			else{
					$stmt = $conn->prepare($query);
			$stmt->bindValue(":user_email",$user_email);
			$stmt->bindValue(":user_pass",$user_pass);
			$stmt->bindValue(":user_fname",$user_fname);
			$stmt->bindValue(":user_type",$user_type);
			
			$stmt->bindValue(":user_image", basename($_FILES["fileToUpload"]["name"]));

			$stmt->execute();
			$stmt->closeCursor();
			header('Location: index.php');
			}
		
			
		} catch (Exception $e) {
			
		}
	}
 ?>