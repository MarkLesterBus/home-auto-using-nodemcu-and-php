
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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Device Manager
      <small>it all starts here</small>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header">
              <h3 class="box-title">List of Devices</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <?php include('show_devices.php'); ?>
              <table class="table table-striped">
                <tbody>
                  <tr>
                  <th>Device Name</th>
                  <th>IP</th>
                  <th>Location</th>
                  <th>Status</th>
                  <th style="width: 150px">Option</th>
                </tr>
                <?php foreach ($devices as $device): ?>
                    <tr>
                      <td><?php echo $device['device_name']; ?></td>
                      <td><?php echo $device['device_ip']; ?></td>
                      <td><?php echo $device['location_name']; ?></td>
                      <?php if ($device['device_status'] == 1): ?>
                        <td><?php echo "ON"; ?></td>
                      <?php else: ?>
                        <td><?php echo "OFF"; ?></td>
                      <?php endif ?>
                      <td>
                        <div class="btn-group">
                        <button type="submit" data-toggle="modal" data-target="#modal-edit-<?php echo $device['device_id']; ?>"  class="btn btn-sm btn-warning">Edit</button>
                        <button type="submit" data-toggle="modal" data-target="#modal-delete-<?php echo $device['device_id']; ?>"  class="btn btn-sm btn-danger">Delete</button>
                      </div>
                      </td>
                    </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            </div>
            <!-- /.box-body -->
          <div class="box-footer">
              <button class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Create Device</button>
            </div>
          </div>
      </div>
    </div>

    <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="create_devices.php" method="post">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Create Device</h4>
            </div>
            <div class="modal-body">

              <div class="form-group">
                <label for="exampleInputEmail1">Device Name</label>
                <input type="text" class="form-control" id="exampleInputDeviceName" name="device_name" placeholder="Enter device name">
              </div>

              <div class="form-group">
                <label>Status</label>
                <select class="form-control select2" name="device_type" style="width: 100%;">
                  <!--<option selected="selected">Alabama</option>-->
                  <option>OUTLET</option>
                  <option>LIGHT</option>
                </select>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">IP Address</label>
                <input type="text" class="form-control" id="exampleInputDeviceName" name="device_ip" placeholder="Enter ip address">
              </div>
              
              <div class="form-group">
                <label>Status</label>
                <select class="form-control select2" name="device_status" style="width: 100%;">
                  <!--<option selected="selected">Alabama</option>-->
                  <option>ON</option>
                  <option>OF</option>
                </select>
              </div>
                <?php  
                  require_once('../include/config.php');
                
                  $query = "SELECT * 
                        FROM `home_automation`.`locations` 
                        LIMIT 0, 1000 ";
                  $stmt = $conn->prepare($query);
                  $stmt->execute();
                  $locations = $stmt->fetchAll();
                  $stmt->closeCursor();
                ?>
              <div class="form-group">
                <label>Location</label>
                <select class="form-control select2" name="device_location" style="width: 100%;">
                  <?php foreach ($locations as $location): ?>
                    <option name="device_location" value="<?php echo $location['location_id']; ?>"><?php echo $location['location_name']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Device</button>
            </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <?php  
      require_once('../include/config.php');
    
      $query = "SELECT 
                `devices`.`device_id`,
                `devices`.`device_name`,
                `devices`.`device_type`,
                `devices`.`device_ip`,
                `devices`.`device_status`,
                `devices`.`date_created`,
                `devices`.`location_id`,
                `locations`.`location_name`
                FROM `devices` INNER JOIN `locations` On `locations`.`location_id` = `devices`.`location_id`";

      $stmt = $conn->prepare($query);
      $stmt->execute();
      $devices = $stmt->fetchAll();
      $stmt->closeCursor();

    ?>

    <?php foreach ($devices as $device): ?>
      <div class="modal fade" id="modal-delete-<?php echo $device['device_id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="delete_devices.php" method="post">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Delete device</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delete this device?</p>
            </div>
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">No</button>
              <button type="submit" value="<?php echo $device['device_id']; ?>" name="delete_device" class="btn btn-primary">Yes</button>
            </div>
              
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
        </div>
    <?php endforeach; ?>
    

    <?php foreach ($devices as $device): ?>
    <div class="modal fade" id="modal-edit-<?php echo $device['device_id']; ?>">
      <div class="modal-dialog">
        <div class="modal-content">
              <form action="edit_devices.php" method="post">
            <div class="modal-header">

              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit device</h4>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Device Name</label>
                <input type="text" class="form-control" value="<?php echo $device['device_name']; ?>" id="exampleInputDeviceName" name="device_name" placeholder="Enter device name">
              </div>
              <div class="form-group">
              <label>Status</label>
              <select class="form-control select2" value="<?php echo $device['device_type']; ?>" name="device_type" style="width: 100%;">
              <!--<option selected="selected">Alabama</option>-->
              <option>OUTLET</option>
              <option>LIGHT</option>
              </select>
            </div>

            <div class="form-group">
            <label for="exampleInputEmail1">IP Address</label>
            <input type="text" class="form-control" id="exampleInputDeviceName" value="<?php echo $device['device_ip']; ?>" name="device_ip" placeholder="Enter ip address">
            </div>

            <div class="form-group">
              <label>Status</label>
              <select class="form-control select2" value="<?php echo $device['device_status']; ?>" name="device_status" style="width: 100%;">
              <!--<option selected="selected">Alabama</option>-->
              <option>ON</option>
              <option>OFF</option>
            </select>
            </div>

            <?php  
              require_once('../include/config.php');

              $query = "SELECT * 
              FROM `home_automation`.`locations` 
              LIMIT 0, 1000 ";

              $stmt = $conn->prepare($query);
              $stmt->execute();
              $locations = $stmt->fetchAll();
              $stmt->closeCursor();
            ?>

            <div class="form-group">
              <label>Location</label>
              <select class="form-control select2" name="location_id" value="<?php echo $location['location_id']; ?>" style="width: 100%;">
                <?php foreach ($locations as $location): ?>
                <option name="location_id" value="<?php echo $location['location_id']; ?>"><?php echo $location['location_name']; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            </div>

            
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="submit" value="<?php echo $device['device_id']; ?>" name ="edit_device" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
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
<?php endif; ?>