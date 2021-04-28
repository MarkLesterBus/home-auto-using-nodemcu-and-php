<?php 
	require_once('../include/config.php');

	if ($_SERVER['REQUEST_METHOD'] =="POST") {

		$location_name = filter_input(INPUT_POST, 'location_name');
		$location_id = filter_input(INPUT_POST, 'edit_location');

		$target_dir = "../public/uploads/";
		$target_file = $target_dir . basename($_FILES["location_image"]["name"]);
		$uploadOk = 1;
		$upload_error="";
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

		// Check if image file is a actual image or fake image
		if(isset($_POST["edit_location"])) {
		    $check = getimagesize($_FILES["location_image"]["tmp_name"]);
		    if($check !== false) {
		        $upload_error= "File is an image - " . $check["mime"] . ".";
		        $uploadOk = 1;

		    } else {
		        $upload_error= "File is not an image.";
		        $uploadOk = 0;
		        echo "1";
		    }
		}

		// Check if file already exists
		if (file_exists($target_file)) {
		    $upload_error= "Sorry, file already exists.";
		    $uploadOk = 0;
		    echo "2";
		}

		// Check file size
		if ($_FILES["location_image"]["size"] > 50000000000000000) {
		    $upload_error= "Sorry, your file is too large.";
		    $uploadOk = 0;
		    echo "3";
		}

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $upload_error= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		    $uploadOk = 0;
		    echo "4";
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    $upload_error= "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["location_image"]["tmp_name"], $target_file)) {
		        $upload_error= "The file ". basename( $_FILES["location_image"]["name"]). " has been uploaded.";
		    } else {
		    	echo "5";
		        $upload_error= "Sorry, there was an error uploading your file.";
		    }
		}
		
		
		try {
			


			$query = "UPDATE 
					  `home_automation`.`locations` 
					SET
					  `location_name` = :location_name, `location_image` = :location_image
					WHERE `location_id` = :location_id";

			$stmt = $conn->prepare($query);
			
			if ($uploadOk == 0) {
				$stmt->bindValue(":location_image","");
			}else{
				$stmt->bindValue(":location_name",$location_name);
				$stmt->bindValue(":location_image", basename($_FILES["location_image"]["name"]));
				$stmt->bindValue(":location_id",$location_id);
				$stmt->bindValue(":location_name",$location_name);
			
				$stmt->execute();
				$stmt->closeCursor();

				header('Location: index.php');
			}
			
		} catch (Exception $e) {
			echo $e;
		}
	}
 ?>
