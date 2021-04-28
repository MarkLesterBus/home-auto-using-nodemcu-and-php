<?php
require_once("../include/config.php");
  $consump_watts = filter_input(INPUT_GET,"W",FILTER_SANITIZE_SPECIAL_CHARS);//105.0;
  $consump_current = filter_input(INPUT_GET,"A",FILTER_SANITIZE_SPECIAL_CHARS);//0.45;
  $device_id = filter_input(INPUT_GET,"device_id",FILTER_SANITIZE_SPECIAL_CHARS);//6;
  date_default_timezone_set('Asia/Manila');
  if ($_SERVER['REQUEST_METHOD'] =="GET"){
    
    if (date('H:i:s a') == "11:59:00 PM"){
      device_downtime($conn,$device_id);
    }elseif(date('H:i:s a') == "12:00:00 AM"){
      device_uptime($conn,$device_id);
    }

    try {
      $query = "INSERT INTO `home_automation`.`device_consumption` (
        `consump_watts`,
        `consump_current`,
        `consump_date`,
        `consump_time`,
        `device_id`
      ) 
      VALUES
        (
          :consump_watts,
          :consump_current,
          :consump_date,
          :consump_time,
          :device_id
        ) ;
      ";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':consump_watts', $consump_watts);
        $stmt->bindValue(':consump_current', $consump_current);
        $stmt->bindValue(':consump_date', date('Y-m-d'));
        $stmt->bindValue(':consump_time', date('H:i:s'));
        $stmt->bindValue(':device_id', $device_id);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (Exception $e) {
        echo $e;
    }
  }
	function device_uptime($conn,$device_id){
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
	function device_downtime($conn,$device_id){
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
?>
  