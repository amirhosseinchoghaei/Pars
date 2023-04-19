<!DOCTYPE html>

  <?php include "header.php" ?>
  <?php include "left_sidebar.php" ?>

  <?php
  $userDetails = getAlluserInfo();
  if(isset($_GET['action']))
  {
	  echo "<script>toastr['success']('User Deleted Successfully')</script>";
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
            <li><a href="#">Users</a></li>
            <li class="active">View Users</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">User List</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Extension</th>
                        <th>Asterisk IP</th>
                        <th>Context</th>
						 <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
					<?php 
					for($i=0;$i<$userDetails['count'];$i++)
					{
						if($userDetails['data'][$i]['asterisk_ip'])
						{
							$asterisk_data = getAsteriskById($userDetails['data'][$i]['asterisk_ip']);
							$asterisk_ip = $asterisk_data['data'][0]['name']."/".$asterisk_data['data'][0]['ip'];
						}
					?>
                      <tr>
                        <td><?php echo $userDetails['data'][$i]['name']; ?></td>
                        <td><?php echo $userDetails['data'][$i]['designation']; ?></td>
                        <td><?php echo $userDetails['data'][$i]['extension']; ?></td>
                        <td><?php echo $asterisk_ip; ?></td>
                        <td><?php echo $userDetails['data'][$i]['context']; ?></td>
						  <td class="btn-group">
                                <a href="detail_user.php?action=View&id=<?php echo $userDetails['data'][$i]['user_id'];?>"><button class="btn btn-info btn-xs" style="float:left;margin-right:5px;">View & Edit</button></a>
                               <a href="detail_user.php?action=delete&id=<?php echo $userDetails['data'][$i]['user_id'];?>"><button class="btn btn-danger btn-xs" style="float:left;margin-left:5px;">Delete</button></a>
                            </td>
                      </tr>
					<?php
					}
					?>
					  
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

    
   <?php include "printDataTableJS.php";?>
   <?php
		include "footer.php";
		include "footer_script.php";
	?>
    <script>
      $(function () {
       var table = $('#example1').DataTable( {
		"order": [[ 0, "desc" ]],
		"autoWidth": true,
		"dom": '<"top"Blf>rt<"bottom"p><"clear">',
		buttons: [
		 {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
				 },
				{
				extend: 'pdf',
				download:'open',
				title: '',
					orientation:'landscape',
					pageSize:'LEGAL',
				exportOptions: {
					columns: ':visible'
				}
				},
				{
				extend: 'excel',
				title: '',
				exportOptions: {
					columns: ':visible'
				}
				},
				{
				extend: 'csv',
				title: '',
				exportOptions: {
					columns: ':visible'
				}
				},
			'copy',
			{
				extend: 'colvis',
				title: '',
				collectionLayout:'fixed two-column'
			}
		]
	});
      });
    </script>
  </body>
</html>
