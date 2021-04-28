<?php 
require_once("../include/config.php");
$user_email = "marklesterbuss@gmail.com";

			$query = "SELECT `devices`.*,`locations`.`location_name`
			FROM `devices` INNER JOIN `locations` On `locations`.`location_id` = `devices`.`location_id`";
			$stmt = $conn->prepare($query);
			$stmt->execute();
			$devices = $stmt->fetchAll();
			$stmt->closeCursor();

			send_email($user_email,$devices);

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
					<p class="font-weight-bold mb-4">Client Information</p>
					<p class="mb-1">John Doe, Mrs Emma Downson</p>
					<p>Acme Inc</p>
					<p class="mb-1">Berlin, Germany</p>
					<p class="mb-1">6781 45P</p>
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

					<div class="text-light mt-5 mb-5 text-center small">by : <a class="text-light" target="_blank" href="http://totoprayogo.com">totoprayogo.com</a></div>
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

 ?>