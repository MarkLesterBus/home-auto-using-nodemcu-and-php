
<?php

// USERS - index.php

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
<?php include('create_users.php'); ?>
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
      <div class="col-md-12">
        <div class="box box-solid">
            <div class="box-header">
              <h3 class="box-title">List of Users</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <?php include('show_users.php'); ?>
              <table class="table table-striped">
                <tbody>
                  <tr>
                  <th>Full Name</th>
                  <th>Email</th>
                  <th>User Type</th>

                  <th style="width: 150px">Option</th>
                </tr>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?php echo $user['user_fname']; ?></td>
                    <td><?php echo $user['user_email']; ?></td>
                    <td><?php echo $user['user_type']; ?></td>
                    <td>
                      <div class="btn-group">
                        <button type="submit" data-toggle="modal" data-target="#modal-edit-<?php echo $user['user_id']; ?>"  class="btn btn-sm btn-warning">Edit</button>
                        <button type="submit" data-toggle="modal" data-target="#modal-delete-<?php echo $user['user_id']; ?>"  class="btn btn-sm  btn-danger">Delete</button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
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
          <form action="create_users.php" method="post" enctype="multipart/form-data">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Create User</h4>
          </div>
          <div class="modal-body">
            <center>
              <div class="widget-user-image">
                <img class="img-circle img-responsive" id="user_image" width="200" height="200" src="../public/dist/img/avatar04.png" alt="User Avatar">
              </div>
              <div class="form-group">
                <label for="exampleInputFile">File input</label>
                <input type="file" id="demo" name="fileToUpload">

                <p class="help-block">Example block-level help text here.</p>
              </div>
            </center> 
            <div class="form-group">
              <label for="exampleInputEmail1">User Full Name</label>
              <input type="text" class="form-control" id="exampleInputDeviceName" name="user_fname" placeholder="Enter full name">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Email</label>
              <input type="email" class="form-control" id="exampleInputDeviceName" name="user_email" placeholder="Enter email">
            </div>
           
            <div class="form-group">
              <label for="exampleInputEmail1">Password</label>
              <input type="password" class="form-control" id="exampleInputDeviceName" name="user_pass" placeholder="Enter password">
            </div>
             <div class="form-group">
              <label>User Type</label>
              <select class="form-control select2" name="user_type" style="width: 100%;">
                
                <option>Administrator</option>
                <option>Family Member</option>
                <option>Guest</option>
                
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
    
      $query = "SELECT `user_id`,`user_email`,`user_pass`,`user_fname`,`user_type`,`user_image`,`date_created` 
            FROM `home_automation`.`users` 
            LIMIT 0, 1000 ";

      $stmt = $conn->prepare($query);
      $stmt->execute();
      $users = $stmt->fetchAll();
      $stmt->closeCursor();

    ?>
    <?php foreach ($users as $user): ?>
       <div class="modal fade" id="modal-delete-<?php echo $user['user_id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="delete_users.php" method="post">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Delete User</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delete this user?</p>
            </div>
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">No</button>
              <button type="submit" value="<?php echo $user['user_id']; ?>" name="delete_user" class="btn btn-primary">Yes</button>
            </div>
              
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
    <?php endforeach; ?>
    

    <?php foreach ($users as $user): ?>
      <div class="modal fade" id="modal-edit-<?php echo $user['user_id']; ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="edit_users.php" method="post" enctype="multipart/form-data">
              <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit User</h4>
            </div>
            <div class="modal-body">
              <center>
                <div class="widget-user-image">
                  <img class="img-circle img-responsive" id="user_image" width="200" height="200" src="../public/uploads/<?php echo $user['user_image']; ?>" alt="User Avatar">
                </div>
                <div class="form-group">
                  <label for="exampleInputFile">File input</label>
                  <input type="file" id="demo" name="updateImageFile">

                  <p class="help-block">Example block-level help text here.</p>
                </div>
              </center> 
              <div class="form-group">
                <label for="exampleInputEmail1">User Full Name</label>
                <input type="text" class="form-control" id="exampleInputDeviceName" value="<?php echo $user['user_fname']; ?>" name="user_fname" placeholder="Enter full name">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="exampleInputDeviceName" value="<?php echo $user['user_email']; ?>" name="user_email" placeholder="Enter email">
              </div>
             
              <div class="form-group">
                <label for="exampleInputEmail1">Password</label>
                <input type="password" class="form-control" id="exampleInputDeviceName" value="<?php echo $user['user_pass']; ?>" name="user_pass" placeholder="Enter password">
              </div>
               <div class="form-group">
                <label>User Type</label>
                <select class="form-control select2" value="<?php echo $user['user_type']; ?>" name="user_type" style="width: 100%;">
                  <option>Administrator</option>
                  <option>Family Member</option>
                  <option>Guest</option>
                  
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button"  class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="submit" value="<?php echo $user['user_id']; ?>" name ="edit_user" class="btn btn-primary">Save Changes</button>
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
  <?php header("../auth/index.php"); ?>
<?php endif; ?>

