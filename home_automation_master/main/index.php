
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
      
      <?php foreach ($locations as $location): ?>
        <div class="col-md-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo $location['location_name']; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <?php 
                require_once('../include/config.php');

                $query = "SELECT `devices`.*
                FROM `devices` WHERE `location_id` = :location_id ";

                $stmt = $conn->prepare($query);
                $stmt->bindValue(':location_id', $location['location_id']);
                $stmt->execute();
                $devices = $stmt->fetchAll();
                $stmt->closeCursor();
              ?>
              <table class="table table-striped">
                <tr>
                  <th style="width: 30px">#</th>
                  <th>Appliance Name</th>
                  <th>IP Address</th>
                  <th style="width: 100px">Action</th>
                </tr>
                
                <?php foreach ($devices as $device): ?>
                  <tr>
                    <td>
                      <?php if ($device['device_type'] == "OUTLET"): ?>
                        <img class="img-circle" style="width: 30px" src="../public/dist/img/plug.jpg" alt="Product Image">
                      <?php endif ?>
                      <?php if ($device['device_type'] == "LIGHT"): ?>
                        <img class="img-circle" style="width: 30px" src="../public/dist/img/bulb.jpg" alt="Product Image">
                      <?php endif ?>
                    </td>
                    <td><?php echo $device['device_name']; ?></td>
                    <td><?php echo $device['device_ip']; ?></td>
                    <td>
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
                    </td>
                </tr>  
                <?php endforeach; ?>
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      <?php endforeach; ?>
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






