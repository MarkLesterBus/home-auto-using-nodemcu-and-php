<?php
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
  <?php include('show_locations.php'); ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>it all starts here</small>
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <?php  

          require_once("../include/config.php");

          $query="SELECT 
                `consum_id`,
                SUM(`consum_amps`) AS amps,
                SUM(`consum_watts`) AS watts,
                SUM(`consum_kwatts`) AS kWh,
                SUM(`consum_wpeak`) AS wpeak,
                `consum_date`,
                `device_id` 
              FROM
                `home_automation`.`consumption` ";

            try {
              $stmt = $conn->prepare($query);
              
              $stmt->execute();
              $consumptions = $stmt->fetchAll();
              $stmt->closeCursor();

            } catch (Exception $e) {
              return null;  
            }

        ?>
        <?php foreach ($consumptions as $consumption):?>
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $consumption['amps']; ?></h3>
                <p>AMPS</p>
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
                <h3><?php echo $consumption['watts']; ?></h3>

                <p>WATTS</p>
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
                <h3><?php echo $consumption['kWh']; ?></h3>

                <p>KWH</p>
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
                <h3><?php echo $consumption['wpeak']; ?></h3>

                <p>PEAK (Watts)</p>
              </div>
              <div class="icon">
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        <?php  endforeach; ?>
      </div>
      <div class="row">
        
        <?php foreach ($locations as $location): ?>
          <div class="col-md-6">
            <div class="box box-solid">

              <div class="box-header with-border">
                <h3 class="box-title"><?php echo $location['location_name']; ?></h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body" style="">
                  <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">

                    <ul class="nav nav-tabs">
                      <li class="active">
                          <a href="#location_img"  data-toggle="tab">LOCATION
                          </a>
                      </li>
                      <?php foreach ($device_types as $device_type): ?>
                        <li>
                          <a href="#<?php echo $location['location_id'] . '-' .$device_type['device_type']; ?>" data-toggle="tab">
                            <?php echo $device_type['device_type']; ?>
                          </a>
                        </li>
                      <?php endforeach; ?>
                    </ul>

                    <div class="tab-content">
                      <div class="tab-pane active" id="location_img">
                        <img class="img-responsive" src="../public/uploads/<?php echo $location['location_image']; ?>" alt="Photo">
                      </div>
                      <?php foreach ($device_types as $device_type): ?>
                        
                        <div class="tab-pane" id="<?php echo $location['location_id'] . '-' .$device_type['device_type']; ?>">
                          <?php 
                            require_once('../include/config.php');

                            $query = "SELECT `devices`.*
                            FROM `devices` WHERE `location_id` = :location_id AND `device_type` = :device_type";

                            $stmt = $conn->prepare($query);
                            $stmt->bindValue(':location_id', $location['location_id']);
                            $stmt->bindValue(':device_type', $device_type['device_type']);
                            $stmt->execute();
                            $devices = $stmt->fetchAll();
                            $stmt->closeCursor();
                          ?>
                          <ul class="products-list product-list-in-box">
                          <?php foreach ($devices as $device): ?>
                            <li class="item">
                              <div class="product-img">
                                <?php if ($device['device_type'] == "OUTLET"): ?>
                                  <img class="img-circle" src="../public/dist/img/plug.jpg" alt="Product Image">
                                <?php endif ?>
                                <?php if ($device['device_type'] == "LIGHT"): ?>
                                  <img class="img-circle" src="../public/dist/img/bulb.jpg" alt="Product Image">
                                <?php endif ?>
                              </div>
                              <div class="product-info">
                                <a href="#" class="product-title"><?php echo $device['device_name']; ?>
                                  
                                  <form action="device_request.php" method="post">
                                    <?php if ($device['device_status'] == 0): ?>
                                        <button type="submit" name="device_status" value="1" class="btn btn-xs btn-primary pull-right">TURN ON</button>
                                        <input type="hidden" name="user_email" value="<?php echo $user_email; ?>">
                                        <input type="hidden" name="device_ip" value="<?php echo $device['device_ip']; ?>">
                                        <input type="hidden" name="device_id" value="<?php echo $device['device_id']; ?>">
                                        <input type="hidden" name="device_type" value="<?php echo $device['device_type']; ?>">
                                    <?php else: ?>
                                        <button type="submit" name="device_status" value="0" class="btn btn-xs btn-warning pull-right">TURN OFF</button>
                                        <input type="hidden" name="user_email" value="<?php echo $user_email; ?>">
                                        <input type="hidden" name="device_ip" value="<?php echo $device['device_ip']; ?>">
                                        <input type="hidden" name="device_id" value="<?php echo $device['device_id']; ?>">
                                        <input type="hidden" name="device_type" value="<?php echo $device['device_type']; ?>">
                                    <?php endif; ?>
                                  </form>
                                  <span class="product-description">
                                        IP - <?php echo $device['device_ip']; ?>
                                  </span>
                                </a>
                              </div>

                            </li>
                          <?php endforeach; ?>

                          </ul>
                        </div>
                      <?php endforeach; ?>
                      <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                  </div>
                  <!-- nav-tabs-custom -->
              </div>
            </div> 
          </div>
        <?php endforeach; ?>
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






