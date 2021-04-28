<?php
$total_watts = 0;
$total_current = 0;
$hrs_usage = array(0,0,0);
$total_kWh = 0;

session_start();
if (isset($_SESSION['auth']) && !empty($_SESSION['auth']) &&
    isset($_SESSION['uid']) && !empty($_SESSION['uid']) &&
    isset($_SESSION['email']) && !empty($_SESSION['email']) &&
    isset($_SESSION['fname']) && !empty($_SESSION['fname']) &&
    isset($_SESSION['uimage']) && !empty($_SESSION['uimage']) &&
    isset($_SESSION['utype']) && !empty($_SESSION['utype'])): 

    $auth_status = $_SESSION['auth'];
    $user_id = $_SESSION['uid'];
    $user_email = $_SESSION['email'];
    $user_fname = $_SESSION['fname'];
    $user_image = $_SESSION['uimage'];
    $user_type = $_SESSION['utype'];

?>

<?php include('../base/main-top.php'); ?>
<?php include('../base/main-header.php'); ?>
<?php include('../base/main-aside.php'); ?>
<?php include('show_consumption.php'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Consumption
      <small>it all starts here</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
    <?php foreach ($devices as $device): ?>
      <?php 
        require_once('../include/config.php');
        try {
          $query = "SELECT `device_consumption`.`consump_id`,
                      (
                        SUM(`device_consumption`.`consump_watts`) / 
                      (SELECT COUNT(`device_consumption`.`consump_id`) FROM `device_consumption` WHERE `consump_time` BETWEEN :uptime1 AND :downtime1)
                      ) AS `ave_watts`,
                      (SUM(`device_consumption`.`consump_current`) / 
                      (SELECT COUNT(`device_consumption`.`consump_id`) FROM `device_consumption` WHERE `consump_time` BETWEEN :uptime2 AND :downtime2)
                      ) AS `ave_current`,
                      
                      `devices`.`device_type`
                  FROM
                      `device_consumption` INNER JOIN
                      `devices` ON `devices`.`device_id` = `device_consumption`.`device_id`
                  WHERE `device_consumption`.`consump_time` BETWEEN :uptime AND :downtime";
        $uptime = $device['up_time'];
        $downtime = $device['down_time'];
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':uptime1', $uptime);
        $stmt->bindValue(':downtime1', $downtime);
        $stmt->bindValue(':uptime2', $uptime);
        $stmt->bindValue(':downtime2', $downtime);
        $stmt->bindValue(':uptime', $uptime);
        $stmt->bindValue(':downtime', $downtime);
        $stmt->execute();
        $consumptions = $stmt->fetchAll();
        $stmt->closeCursor();

        foreach ($consumptions as $consumption): 
          $total_watts += round($consumption['ave_watts'],3);
          $total_current += round($consumption['ave_current'],3);

          $temp_time = explode(':',$device['time_duration']);
          $hrs_usage[0] += $temp_time[0];
          $hrs_usage[1] += $temp_time[1];
          $hrs_usage[2] += $temp_time[2];

          $time_string = sprintf('%02d:%02d:%02d',  $hrs_usage[0],  $hrs_usage[1], $hrs_usage[2]);

          $watts = (round($consumption['ave_watts']) * round(to_decimal($device['time_duration']))); 
          $total_kWh += round($watts / 1000, 3);
        endforeach;

        } catch (Exception $e) {
          $total_watts = 0;
          $total_current = 0;
          $hrs_usage = array(0,0,0);
          $total_kWh = 0;
        }
        
        
      ?>
    <?php endforeach;?>
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_watts; ?></h3>
          <p>TOTAL WATTS</p>
        </div>
        <div class="icon">
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?php echo $total_current; ?></h3>

          <p>TOTAL AMPS</p>
        </div>
        <div class="icon">
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo to_time($time_string); ?></h3>

          <p>TOTAL HRS</p>
        </div>
        <div class="icon">
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?php echo $total_kWh; ?></h3>

          <p>TOTAL KWH</p>
        </div>
        <div class="icon">
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Consumption Report</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body no-padding">
            <table class="table table-striped">
              <tr>
                <th>Appliance Name</th>
                <th>Date</th>
                <th>Up Time</th>
                <th>Down Time</th>
                <th>Watts</th>
                <th>Current</th>
                <th>Duration</th>
                <th>kWh</th>
                <th>Type</th>
              </tr>
              
              <?php foreach ($devices as $device): ?>
                <tr>
                  <td><?php echo $device['device_name']; ?></td>
                  <td><?php echo $device['activ_date']; ?></td>
                  <td><?php echo $device['up_time']; ?></td>
                  <td><?php echo $device['down_time']; ?></td>
                  <?php 
                    require_once('../include/config.php');
                    
                    $query = "SELECT `device_consumption`.`consump_id`,
                                  (
                                    SUM(`device_consumption`.`consump_watts`) / 
                                  (SELECT COUNT(`device_consumption`.`consump_id`) FROM `device_consumption` WHERE `consump_time` BETWEEN :uptime1 AND :downtime1)
                                  ) AS `ave_watts`,
                                  (SUM(`device_consumption`.`consump_current`) / 
                                  (SELECT COUNT(`device_consumption`.`consump_id`) FROM `device_consumption` WHERE `consump_time` BETWEEN :uptime2 AND :downtime2)
                                  ) AS `ave_current`,
                                  
                                  `devices`.`device_type`
                              FROM
                                  `device_consumption` INNER JOIN
                                  `devices` ON `devices`.`device_id` = `device_consumption`.`device_id`
                              WHERE `device_consumption`.`consump_time` BETWEEN :uptime AND :downtime";
                    $uptime = $device['up_time'];
                    $downtime = $device['down_time'];
                    $stmt = $conn->prepare($query);
                    $stmt->bindValue(':uptime1', $uptime);
                    $stmt->bindValue(':downtime1', $downtime);
                    $stmt->bindValue(':uptime2', $uptime);
                    $stmt->bindValue(':downtime2', $downtime);
                    $stmt->bindValue(':uptime', $uptime);
                    $stmt->bindValue(':downtime', $downtime);
                    $stmt->execute();
                    $consumptions = $stmt->fetchAll();
                    $stmt->closeCursor();
                  ?>
                  <?php foreach ($consumptions as $consumption): ?>
                  <td><?php echo round($consumption['ave_watts'],2); ?></td>
                  <td><?php echo round($consumption['ave_current'],2); ?></td>
                  <td><?php echo $device['time_duration']; ?></td>
                  <td>
                    <?php
                    
                      $watts = (round($consumption['ave_watts']) * round(to_decimal($device['time_duration']))); 
                      echo round($watts / 1000, 3); 
                    ?>
                  </td>
                  <td><?php echo $consumption['device_type']; ?></td>
                  <?php endforeach; ?>
                </tr>  
              <?php endforeach; ?>
              
            </table>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
  

<?php include('../base/main-footer.php'); ?>
<?php include('../base/main-bottom.php'); ?>

<?php else: ?>
<?php header("../auth/index.php"); ?>
<?php 
  $auth_status = $_SESSION['auth'];
  $user_id = $_SESSION['uid'];
  $user_email = $_SESSION['email'];
  $user_fname = $_SESSION['fname'];
  $user_image = $_SESSION['uimage'];
  $user_type = $_SESSION['utype'];

  echo $auth_status. "<br/>";
  echo $user_id. "<br/>";
  echo $user_email. "<br/>";
  echo $user_fname. "<br/>";
  echo $user_image. "<br/>";
  echo $user_type. "<br/>";
?>
<?php endif ?>