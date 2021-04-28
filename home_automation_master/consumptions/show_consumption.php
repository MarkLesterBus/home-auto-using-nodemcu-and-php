<?php
require_once('../include/config.php');

$query = "SELECT
              devices.device_id,
              devices.device_name,
              device_activity.activ_date,
              device_activity.up_time,
              device_activity.down_time,
              TIMEDIFF(device_activity.down_time, device_activity.up_time) AS time_duration
          FROM
              device_activity INNER JOIN
              devices ON devices.device_id = device_activity.device_id ";

$stmt = $conn->prepare($query);
$stmt->execute();
$devices = $stmt->fetchAll();
$stmt->closeCursor();

function to_decimal($time) {
  $timeArr = explode(':', $time);
  $decTime = ($timeArr[0]*3600) + ($timeArr[1]*60) + ($timeArr[2]);
  return $decTime;
}
function to_time($time){
  $all_seconds = 0;
        list($hour, $minute, $second) = explode(":",$time);
        $all_seconds += $hour * 3600;
        $all_seconds += $minute * 60;
        $all_seconds += $second;

    

  $total_minutes = floor($all_seconds/60);
  $seconds = $all_seconds % 60;
  $hours = floor($total_minutes / 60); 
  $minutes = $total_minutes % 60;

  echo sprintf('%02d:%02d:%02d', $hours, $minutes,$seconds);
}

?>