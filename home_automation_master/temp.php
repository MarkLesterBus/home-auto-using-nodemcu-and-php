<?php
 
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

	send_request("192.168.1.8",true,"OUTLET");
?>