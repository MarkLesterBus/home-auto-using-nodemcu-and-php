<?php
  require_once("../include/config.php");

  $device_id = 5;//filter_input(INPUT_GET,"device_id",FILTER_SANITIZE_SPECIAL_CHARS);

  $device_Amps = 0.100;//filter_input(INPUT_GET,"A",FILTER_SANITIZE_SPECIAL_CHARS);
  $device_Watts = 11; //filter_input(INPUT_GET,"W",FILTER_SANITIZE_SPECIAL_CHARS);
  $device_kWh = 0.002;//filter_input(INPUT_GET,"kWh",FILTER_SANITIZE_SPECIAL_CHARS);
  $device_WP = 11;//filter_input(INPUT_GET,"WP",FILTER_SANITIZE_SPECIAL_CHARS);

  if ($_SERVER['REQUEST_METHOD'] =="GET"){
    $query = "INSERT INTO `home_automation`.`consumption` (`consum_amps`,`consum_watts`,`consum_kwatts`,`consum_wpeak`,`device_id`) 
              VALUES(:consum_amps,:consum_watts,:consum_kwatts,:consum_wpeak,:device_id) ";
    try {
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':consum_amps', $device_Amps);
        $stmt->bindValue(':consum_watts', $device_Watts);
        $stmt->bindValue(':consum_kwatts', $device_kWh);
        $stmt->bindValue(':consum_wpeak', $device_WP);
        $stmt->bindValue(':device_id', $device_id);

        $stmt->execute();
        $stmt->closeCursor();
        echo "Location Saved!";
      } catch (Exception $e) {
        echo $e;
      }
  }

  /*if(isset($location_name) && !empty($location_name) && isset($location_name) && !empty($location_name)) {

    try {
      $query = 'INSERT INTO `mec_controlsystem`.`locations` ( `location_name`, `location_ip`)
                VALUES(:location_name, :location_ip)';
      $stmt = $conn->prepare($query);

      $stmt->bindValue(':location_name', $location_name);
      $stmt->bindValue(':location_ip', $location_ip);

      $stmt->execute();
      echo "Location Saved!";
    } catch (Exception $e) {
      echo $e.getMessage();
    }
  }*/


?>
