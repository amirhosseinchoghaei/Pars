<!DOCTYPE html>

  <?php include "header.php" ?>
  <?php include "left_sidebar.php" ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php
if(!isset($_GET['id']))
{
	exit;
}else
{
	$user_id = $_GET['id'];
	$userData = getProfile($user_id);
	$userProfile = $userData['data'];
}
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Users
            <small></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Tables</a></li>
            <li class="active">Data tables</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Create User</h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" method='post' >
                  <div class="box-body">
				  
				     <div class="form-group">
                      <label for="name" class="col-sm-2 control-label">Name<span style="color:red">*</span></label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name ='name' value='<?php echo $userProfile['name']; ?>' placeholder="Name" required>
                      </div>
                    </div>
				  
				  <div class="form-group">
                      <label for="email" class="col-sm-2 control-label">Email<span style="color:red">*</span></label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" value='<?php echo $userProfile['email']; ?>' name ='email' placeholder="Email" required>
                      </div>
                    </div>
					
					
					 <div class="form-group">
                      <label for="password" class="col-sm-2 control-label">Password<span style="color:red">*</span></label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" value='<?php echo $userProfile['original_password']; ?>' name ='password' placeholder="Password" required>
                      </div>
                    </div>
				  
				  <div class="form-group">
                      <label for="phone" class="col-sm-2 control-label">Mobile Number</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="phone" value='<?php echo $userProfile['contact']; ?>' name ='phone' placeholder="Mobile Number" >
                      </div>
                    </div>
				  
				  
                    <div class="form-group">
                      <label for="extension" class="col-sm-2 control-label">Extension<span style="color:red">*</span></label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="extension" value='<?php echo $userProfile['extension']; ?>' name = 'extension' placeholder="Extension Numbre" required>
                      </div>
                    </div>
					
					 <div class="form-group">
                      <label for="designation" class="col-sm-2 control-label">Designation</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="designation" value='<?php echo $userProfile['designation']; ?>' name = 'designation' placeholder="Designation" >
                      </div>
                    </div>
					
<!--					<div class="form-group">
                      <label for="asterisk" class="col-sm-2 control-label">Asterisk IP</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="asterisk" name = 'asterisk' placeholder="asterisk" >
                      </div>
                    </div>
					-->
					
					
					<div class="form-group">
                        <label for="asterisk"  class="col-sm-2 control-label">Asterisk IP</label>
                        <div class="col-sm-6">
						<select class="form-control select2" id="asterisk" name="asterisk" style="width: 100%;" required>
							<option value="">Select Asterisk Server</option>
							<?php
							for($i=0;$i<=count($asteriskDataCount);$i++)
							{
								if($asteriskData['data'][$i]['id'] == $userProfile['asterisk_ip'])
								{
									$selected="selected=selected";
								}
								else
								{
									$selected="";
								}
							//	print_r($userProfile);
							?>
							<option <?php echo $selected; ?> value="<?php echo $asteriskData['data'][$i]['id']; ?>"><?php echo $asteriskData['data'][$i]['name']; ?></option>
							<?php } ?>
						</select>
                        </div>
                      </div>
					
                    <div class="form-group">
                      <label for="context" class="col-sm-2 control-label">Context</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="context" value='<?php echo $userProfile['context']; ?>' name='context' placeholder="Context" >
                      </div>
                    </div>
					
					 <div class="form-group">
                      <label for="channel" class="col-sm-2 control-label">Channel</label>
                      <div class="col-sm-10">
                        <input type="channel" class="form-control" id="channel" value='<?php echo $userProfile['channel']; ?>' name='channel' placeholder="Channel" >
                      </div>
                    </div>
					
					 <div class="form-group">
                      <label for="prefix" class="col-sm-2 control-label">Prefix (Optional)</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="prefix" value='<?php echo $userProfile['prefix']; ?>' name ='prefix' placeholder="Prefix" >
                      </div>
                    </div>
					
					  <div class="form-group has-feedback">
           
		    <span style="color:red;display:none;" id="email_error">*Failed : Email Already Used By Some Other User</span>
			<span style="color:red;display:none;" id="diff_error">*Failed : Something Went Wrong</span>
			
          </div>
                    
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                  
                    <button type="submit" name='submit' class="btn btn-info pull-center">Update User</button>
                  </div><!-- /.box-footer -->
                </form>
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

	  
	  
	  <?php
		if(isset($_POST['submit']))
		{
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$phone = $_POST['phone'];
			$extension = $_POST['extension'];
			$designation = $_POST['designation'];
			$asterisk = $_POST['asterisk'];
			$context = $_POST['context'];
			$channel = $_POST['channel'];
			$prefix = $_POST['prefix'];
						
			$dataToSend = $user_id."***".$name."***".$email."***".$password."***".$phone."***".$extension."***".$designation."***".$asterisk."***".$context."***".$channel."***".$prefix;
			$res = updateProfile($dataToSend);
			if($res['status']=="1")
			{
				echo "<script>toastr['success']('Success : Profile Updated Successfully')</script>";
			}
			else
			{
				echo "<script>toastr['error']('Failed : Something Went Wrong')</script>";
			}
			
		}
	  ?>
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
     <?php
	
	  include "footer.php";
	  include "control_sidebar.php";
	 // include "footer_script.php";
	  ?>
	
	
	
	
	
	
	
    <!-- jQuery 2.1.4 -->
    <script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../dist/js/demo.js"></script>
    <!-- page script -->
    <script>
      $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });
      });
    </script>
  </body>
</html>
