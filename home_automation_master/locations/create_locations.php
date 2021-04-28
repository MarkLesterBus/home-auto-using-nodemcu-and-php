<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$location_name = filter_input(INPUT_POST, 'location_name');
		$target_dir = "../public/uploads/";
		$target_file = $target_dir . basename($_FILES["location_image"]["name"]);
		$uploadOk = 1;
		$upload_error="";
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["location_image"]["tmp_name"]);
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
		if ($_FILES["location_image"]["size"] > 500000) {
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
		    if (move_uploaded_file($_FILES["location_image"]["tmp_name"], $target_file)) {
		        $upload_error= "The file ". basename( $_FILES["location_image"]["name"]). " has been uploaded.";
		    } else {
		        $upload_error= "Sorry, there was an error uploading your file.";
		    }
		}
		
		try {
			

			$query = "INSERT INTO `home_automation`.`locations` (`location_name`,`location_image`,`date_created`) 
					  VALUES(:location_name,:location_image,NOW())";

			$stmt = $conn->prepare($query);
			$stmt->bindValue(":location_name",$location_name);
			$stmt->bindValue(":location_image", $_FILES["location_image"]["name"]);
			$stmt->execute();
			$stmt->closeCursor();
			header('Location: index.php');
			
		} catch (Exception $e) {
			
		}
	}
 ?>