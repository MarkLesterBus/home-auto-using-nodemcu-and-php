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
<?php include('create_locations.php'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      User Manager
      <small>it all starts here</small>
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header">
              <h3 class="box-title">List of Users</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <?php include('show_locations.php'); ?>
              <table class="table table-striped">
                <tbody>
                  <tr>
                  <th style="width:10px"></td>
                  <th>Location Name</th>
                  <th>Date Created</th>                  
                  <th style="width: 150px">Option</th>
                </tr>
                <?php foreach ($locations as $location): ?>
                  <tr>
                    <td></td>
                    <td><?php echo $location['location_name']; ?></td>
                    <td><?php echo $location['date_created']; ?></td>                    
                    <td>
                      <div class="btn-group">
                        <button type="submit" data-toggle="modal" data-target="#modal-edit-<?php echo $location['location_id']; ?>"  class="btn btn-warning">Edit</button>
                        <button type="submit" data-toggle="modal" data-target="#modal-delete-<?php echo $location['location_id']; ?>"  class="btn btn-danger">Delete</button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
             
            </table>
            </div>
            <!-- /.box-body -->
           <div class="box-footer">
              <button class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Create Location</button>
            </div>
          </div>
      </div>
    </div>
    

     <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="create_locations.php" method="post" enctype="multipart/form-data">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Create Location</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="exampleInputEmail1">Locations Name</label>
              <input type="text" class="form-control" id="exampleInputDeviceName" name="location_name" placeholder="Enter location name">
            </div>
            <div class="form-group">
              <label for="exampleInputFile">Location image input</label>
              <input type="file" id="exampleInputFile" name="location_image">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save Location</button>
          </div>
            
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <?php  
      require_once('../include/config.php');
    
      $query = "SELECT `location_id`,`location_name`,`date_created` 
                FROM `home_automation`.`locations` ";

      $stmt = $conn->prepare($query);
      $stmt->execute();
      $locations = $stmt->fetchAll();
      $stmt->closeCursor();

    ?>
    <?php foreach ($locations as $location): ?>
       <div class="modal fade" id="modal-delete-<?php echo $user['location_id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="delete_users.php" method="post">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Delete Location</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delete this location?</p>
            </div>
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">No</button>
              <button type="submit" value="<?php echo $user['location_id']; ?>" name="delete_location" class="btn btn-primary">Yes</button>
            </div>
              
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <?php endforeach; ?>
    

    <?php foreach ($locations as $location): ?>
      <div class="modal fade" id="modal-edit-<?php echo $location['location_id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="edit_locations.php" method="post" enctype="multipart/form-data">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Location</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Locations Name</label>
                <input type="text" class="form-control" id="exampleInputDeviceName" value="<?php echo $location['location_name']; ?>" name="location_name" placeholder="Enter location name">
              </div>
              <div class="form-group">
                <label for="exampleInputFile">Location image input</label>
                <input type="file" id="exampleInputFile" name="location_image">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="submit" value="<?php echo $location['location_id']; ?>" name ="edit_location" class="btn btn-primary">Save Changes</button>
            </div>
              
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <?php endforeach; ?>
   

  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include('../base/main-footer.php'); ?>
<?php include('../base/main-bottom.php'); ?>
<?php else: ?>
  <?php header("../auth/index.php") ?>
<?php endif; ?>

