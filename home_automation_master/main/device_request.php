<?php 
	require_once("../include/config.php");
	if ($_SERVER["REQUEST_METHOD"] =="POST") {

		$device_status = filter_input(INPUT_POST, "device_status");
		$device_id = filter_input(INPUT_POST, "device_id");
		$device_ip = filter_input(INPUT_POST, "device_ip");
		$user_email = filter_input(INPUT_POST, "user_email");
		$device_type = filter_input(INPUT_POST, "device_type");
		
		try {
				
			$query = "UPDATE `home_automation`.`devices` 
								SET `device_status` = :device_status
								WHERE `device_id` = :device_id 
								AND `device_type` = :device_type";
			$result = update_device($query,$conn,$device_id,$device_status,$device_type);
			if ($result == true){
				$query = "SELECT `devices`.*,`locations`.`location_name` 
									FROM `devices` 
									INNER JOIN `locations` 
									On `locations`.`location_id` = `devices`.`location_id`";
				send_request($device_ip,$device_status,$device_type);

				if ($device_status == true){
					device_uptime($conn,$device_status,$device_id);
				}else{
					device_downtime($conn,$device_status,$device_id);
				}

				$devices = select_device($query,$conn);
				header("Location: index.php");
			} 
		}catch (Exception $e) {
		}
	}

	function device_uptime($conn,$device_status,$device_id){
		date_default_timezone_set('Asia/Manila');
		$query = "INSERT INTO `home_automation`.`device_activity` (
			`activ_date`,
			`up_time`,
			`device_id`,
			`flag`
		) 
		VALUES
			(
				:activ_date,
				:up_time,
				:device_id,
				:flag
			)";
		try {
			$stmt = $conn->prepare($query);
			$stmt->bindValue(":activ_date",date('Y-m-d'));
			$stmt->bindValue(":up_time",date('H:i:s'));
			$stmt->bindValue(":device_id",$device_id);
			$stmt->bindValue(":flag",false);
			$stmt->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	function device_downtime($conn,$device_status,$device_id){
		date_default_timezone_set('Asia/Manila');
		$query = "UPDATE 
								`home_automation`.`device_activity` 
							SET
								`down_time` = :down_time,
								`flag` = :new_flag
							WHERE  `activ_date` = :activ_date AND `device_id` = :device_id AND `flag` = :flag";
		try {
			$stmt = $conn->prepare($query);
			$stmt->bindValue(":activ_date",date('Y-m-d'));
			$stmt->bindValue(":down_time",date('H:i:s'));
			$stmt->bindValue(":device_id",$device_id);
			$stmt->bindValue(":flag",false);
			$stmt->bindValue(":new_flag",true);
			$stmt->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	function update_device ($query,$conn,$device_id,$device_status,$device_type){
		try {
			$stmt = $conn->prepare($query);
			$stmt->bindValue(":device_id",$device_id);
			$stmt->bindValue(":device_status",$device_status);
			$stmt->bindValue(":device_type",$device_type);
			$stmt->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}
			
	}
	function notify($device_id){
		try {
			$img = "";
			$query = "";
			$stmt = $conn->prepare($query);
			$stmt->bindValue(":device_id",$device_id);
			$stmt->execute();
			return true;
		} catch (Exception $e) {
			return false;
		}

	}

	function select_device($query,$conn){
		try {
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$devices = $stmt->fetchAll();
			$stmt->closeCursor();
			return $devices;
		} catch (Exception $e) {
			return null;	
		}
		
	}

	function send_request($device_ip,$device_status,$device_type){
	
		$url = "";
		$cURLConnection = curl_init();

		if ($device_status == 0 && $device_type == "OUTLET"){
			$url = "http://".$device_ip."/?OUTLET=OFF";

		}elseif($device_status == 1 && $device_type == "OUTLET"){
			$url = "http://".$device_ip."/?OUTLET=ON";

		}elseif($device_status == 0 && $device_type == "LIGHT"){
			$url = "http://".$device_ip."/?LIGHT=OFF";

		}elseif($device_status == 1 && $device_type == "LIGHT"){
			$url = "http://".$device_ip."/?LIGHT=ON";

		}else{
			$url = "";
		}

			
		curl_setopt($cURLConnection, CURLOPT_URL, $url);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

		$jsonArrayResponse = curl_exec($cURLConnection);
		return true;
		curl_close($cURLConnection);
	}

	function send_email($send_to,$devices){
			
		$subject = "HOME AUTOMATION";	

		$message = '<!DOCTYPE html>
					<html lang="en">
					<head>
					<meta charset="utf-8">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
					<title>Bootstrap 101 Template</title>

					<!-- Bootstrap -->
					<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
					<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
					<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

					<!------ Include the above in your HEAD tag ---------->
					<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
					<!-- WARNING: Respond.js doesnt work if you view the page via file:// -->
					<!--[if lt IE 9]>
					<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
					<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
					<![endif]-->

					</head>
					<body>';

		$message .= '<div class="container">
					<div class="row">
					<div class="col-12">
					<div class="card">
					<div class="card-body p-0">
					<div class="row pb-5 p-5">
					<div class="col-md-6">
					<p class="font-weight-bold mb-4">HOME AUTOMATION</p>
					<p class="mb-1">List of Devices</p>
					</div>
					</div>

					<div class="row p-5">
					<div class="col-md-12">
					<table class="table">
					<thead>
					<tr>
					<th class="border-0 text-uppercase small font-weight-bold">ID</th>
					<th class="border-0 text-uppercase small font-weight-bold">Device Name</th>
					<th class="border-0 text-uppercase small font-weight-bold">IP Address</th>
					<th class="border-0 text-uppercase small font-weight-bold">Status</th>
					</tr>
					</thead>
					<tbody>';
	    foreach ($devices as $device) {
			$message .="<tr>";
			$message .="<td>".$device['device_id']."</td>";
			$message .="<td>".$device['device_name']."</td>";
			$message .="<td>".$device['device_ip']."</td>";
			if ($device['device_status'] == 0) {
				 $message .="<td>OFF</td>";
			}else {
				 $message .="<td>ON</td>";
			}
			$message .= "</tr>";
	    }

		$message .='</tbody>
					</table>
					</div>
					</div>
					</div>
					</div>
					</div>
					</div>
					<br/>
					<br/>
					<div class="text-light mt-5 mb-5 text-center small">by : <a class="text-light" target="_blank" href="#">Home Automation System</a></div>
					</div>
					<!-- jQuery (necessary for Bootstraps JavaScript plugins) -->
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
					<!-- Include all compiled plugins (below), or include individual files as needed -->
					<script src="js/bootstrap.min.js"></script>
					</body>
					</html>';

		$from = 'homeautomationsystem2019@gmail.com';
		 
		// To send HTML mail, the Content-type header must be set
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		 
		// Create email headers
		$headers .= 'From: '.$from."\r\n".
		    'Reply-To: '.$from."\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		 
		// Compose a simple HTML email message
		 
		// Sending email
		if(mail($send_to, $subject, $message, $headers)){
		    echo 'Your mail has been sent successfully.';
		} else{
		    echo 'Unable to send email. Please try again.';
		}

	}
	
	function device_consumption($conn,$device_id,$device_status){
		try {
			if($device_status == 1){
				$query = "INSERT INTO `home_automation`.`device_consumption`(`consump_watts`,`consump_current`,`device_id`) 
									VALUES(:consump_watts,:consump_current,:device_id)";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':consump_watts', '00.0');
				$stmt->bindValue(':consump_current', '00.0');
				$stmt->bindValue(':device_id', $device_id);
			}else{
				$query = "UPDATE `home_automation`.`device_consumption` 
									SET `consump_end` = NOW()
									WHERE `device_id` = :device_id AND `consump_started` <= NOW()";
				$stmt = $conn->prepare($query);
				$stmt->bindValue(':device_id', $device_id);
			}
			$stmt->execute();
			$stmt->closeCursor();
		} catch (Exception $e) {
		echo $e;
		}
	}
?>

