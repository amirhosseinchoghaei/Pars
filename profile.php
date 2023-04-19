<?php include "headers.php" ?>

      <!-- Content Wrapper. Contains page content -->
<link rel="stylesheet" href="plugins/iCheck/all.css">
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Administrator Profile
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Administrator profile</li>
          </ol>
        </section>
		
		
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-3">

              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  
				  
			<form method='post' enctype="multipart/form-data" id='uploadform'>
				<div id="btnfile" style='cursor: pointer'><img class="profile-user-img img-responsive img-circle" src="<?php echo $profileDetails[		'profile_image']; ?>" alt="User profile picture" /></div>
				<div style='display:none'> 
					<input type="file" name="image" accept="image/*" id="uploadfile" onchange='abc();'/>
				</div>
			</form>
				  
				  <!--<img class="profile-user-img img-responsive img-circle" src="../dist/img/user2-160x160.jpg" alt="User profile picture">-->
				  
				  
				  
                  <h3 class="profile-username text-center"><span id="p_name"><?php echo $profileDetails['name']; ?></span></h3>
                  <p class="text-muted text-center" id="p_designation"><?php echo $profileDetails['designation']; ?></p>

                  <ul class="list-group list-group-unbordered">
                     <li class="list-group-item">
                      <img src="image/company.png" alt="Company Name" title='Company Name'><a class="pull-right" id="c_name"><?php echo $profileDetails['company_name']; ?></a>
                    </li>
					
					<li class="list-group-item">
                      <b><img src="image/email.png" alt="Email" title='Email Address'></b> <a class="pull-right" id="p_email"><?php echo $profileDetails['email']; ?></a>
                    </li>
                    <li class="list-group-item">
                      <b><img src="image/phone.png" alt="Phone" title='Phone Number'></b> <a class="pull-right" id="p_phone"><?php echo $profileDetails['contact']; ?></a>
                    </li>
                    <li class="list-group-item">
                      <b><img src="image/extension.png" alt="Extension" title='Extension Number'></b> <a class="pull-right" id="p_extension"><?php echo $profileDetails['extension']; ?></a>
                    </li>
					
					
					
					
                  </ul>

                  <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->

			  
			  
           
            </div>
		   
		    <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  
				<li class='active'><a href="#settings" data-toggle="tab">Settings</a></li>
				<li><a href="#password" data-toggle="tab">Change Password</a></li>
				<li><a href="#asterisk" data-toggle="tab">Techextension Addon Configuration</a></li>
				<li><a href="#crm" data-toggle="tab">CRM Configuration</a></li>

                </ul>
                <div class="tab-content">
                
                  <div class="active tab-pane" id="settings">
                    <form class="form-horizontal" method="POST" action="profile.php">
                      <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="name" value="<?php echo $profileDetails['name']; ?>" placeholder="Name" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="designation" class="col-sm-3 control-label">Designation</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="designation" value="<?php echo $profileDetails['designation']; ?>" placeholder="Designation" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-6">
                          <input type="email" class="form-control" id="email" value="<?php echo $profileDetails['email']; ?>" placeholder="Email" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="phone" class="col-sm-3 control-label">Phone Number</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="phone" value="<?php echo $profileDetails['contact']; ?>" placeholder="Phone Number" required>
                        </div>
                      </div>
					  
					   <div class="form-group">
                        <label for="phone" class="col-sm-3 control-label">Popup Type (For Chrome Only)</label>
                        <div class="col-sm-6">
						<?php
						if($profileDetails['popup_type'] == "0")
						{
							$new = "checked";
						}else{
							$inside = "checked";
						}
						?>
						<label>
								New Window  <input type="radio" value="0" name="popup_type" class="flat-red" <?php echo $new; ?>>
							</label>
							<label>
								&emsp;&emsp;Inside Window  <input type="radio" value="1" name="popup_type" class="flat-red" <?php echo $inside; ?>>
							</label>
							
							<div>
							<span style="color:red"><b>Default Popup:</b>  New Window for Inside Window you have to install<b> <a style='text-decoration:none;' href='https://chrome.google.com/webstore/detail/astercti-callsms-techexte/knonhljeipkkgjfnakhgcglccgfegank'>Chrome Extension</a></b>)</span>
							</div>
                        </div>
                      </div>
					  
					<div class="form-group">
						<label  class="col-sm-3 control-label" >  </label>
						<div class="col-sm-6" id="error_message" style="display:none;color:red;font-size:14px;"> *Please Fill All Details </div>
					</div>
					  
					
					  
					  
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <input type="button" id="basic" class="btn btn-danger" value="Submit">
                        </div>
                      </div>
                    </form>
                  </div><!-- /.tab-pane -->
				  
				  
				  
				  
				  
				  <div class="tab-pane" id="password">
                   <form class="form-horizontal">
                      <div class="form-group">
                        <label for="old_password" class="col-sm-3 control-label">Old Password</label>
                        <div class="col-sm-6">
                          <input type="password" class="form-control" id="old_password" placeholder="Enter Old Password" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="new_password" class="col-sm-3 control-label">New Password</label>
                        <div class="col-sm-6">
                          <input type="password" class="form-control" id="new_password" placeholder="Enter New Password" required>
                        </div>
                      </div>
                      
					  
					  <div class="form-group">
						<label  class="col-sm-3 control-label" >  </label>
						<div class="col-sm-6" id="error_message_pass" style="display:none;color:red;font-size:14px;"> *Please Fill All Details </div>
					</div>
					  
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <input type="button" id="password_btn" class="btn btn-danger" value="Change Password">
                        </div>
                      </div>
                    </form>
					</div>
				  	  
				  <div class="tab-pane" id="asterisk">
                   <form class="form-horizontal">
				    <div class="box-body">
                        <div class="form-group">
                        <label for="extension" class="col-sm-3 control-label">Extension</label>
                        <div class="col-sm-6">
                          <input type="text" value="<?php echo $profileDetails['extension']; ?>" class="form-control" id="extension" placeholder="Extension" required>
                        </div>
                      </div>
					  
					  
					  <div class="form-group">
                        <label for="asterisk_ip"  class="col-sm-3 control-label">Asterisk IP</label>
                        <div class="col-sm-6">
                        
					<select class="form-control select2" id="asterisk_ip" style="width: 100%;" required>
						<option value="">Select Asterisk Server</option>
						  <?php
						  for($i=0;$i<$asteriskData['count'];$i++)
						  {
						  if($asteriskData['data'][$i]['id'] == $profileDetails['asterisk_ip'])
						  {
							$selected="selected=selected";
						  }
						  else
						  {
							$selected="";
						  }
						  ?>
						<option <?php echo $selected; ?> value="<?php echo $asteriskData['data'][$i]['id']; ?>"><?php echo $asteriskData['data'][$i]['name']; ?></option>
                     <?php }  ?>
                    </select>
					
                        </div>
                      </div>
                      
					  <div class="form-group">
                        <label for="cahnnel" class="col-sm-3 control-label">Channel</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="cahnnel" value="<?php echo $profileDetails['channel']; ?>" placeholder="Enter Channel" required>
                        </div>
                      </div>
					  
					  
					  <div class="form-group">
                        <label for="context" class="col-sm-3 control-label">Context</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="context" value="<?php echo $profileDetails['context']; ?>" placeholder="Enter Extension Context" required>
                        </div>
                      </div>
					  
					    <div class="form-group">
                        <label for="prefix" class="col-sm-3 control-label">Prefix (Optional)</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="prefix" value="<?php echo $profileDetails['prefix']; ?>" placeholder="Enter Prefix">
                        </div>
                      </div>
                      
                      <div class="form-group">
						<label  class="col-sm-3 control-label" >  </label>
						<div class="col-sm-6" id="error_message_tech" style="display:none;color:red;font-size:14px;"> *Please Fill All Details </div>
					</div>
					  
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <input type="button" id="techextension" class="btn btn-danger" value="Submit">
                        </div>
                      </div>
					   </div>
                    </form>
                    
                  </div>
			  
<?php
$crmInfo = getAllCRMInfo();$crmListCount = $crmInfo['count'];
?>

			  
			<div class="tab-pane" id="crm">
                   <form class="form-horizontal">
					  <div class="form-group">
                        <label for="crm_url"  class="col-sm-3 control-label">CRM URL</label>
                        <div class="col-sm-6">
					<select class="form-control select2" id="crm_url" style="width: 100%;" onchange="crmNameFind(this.value)" required>
						<option value="" >Select CRM URL</option>
						  <?php
						  for($i=0;$i<$crmListCount;$i++)
						  {
							  
							  if($crmInfo['data'][$i]['crm_url'] == $profileDetails['crm_url'])
							  {
								$selected="selected=selected";
							  }
							  else
							  {
								$selected="";
							  }
						  ?>
                        <option <?php echo $selected; ?> value="<?php echo $crmInfo['data'][$i]['crm_url']; ?>"><?php echo $crmInfo['data'][$i]['crm_url']; ?></option>
                     <?php }  ?>
                    </select>
					 </select>
					
					<script>
					var crm_url_on_load = <?php echo json_encode($profileDetails['crm_url']) ?>;
					//console.log(crm_url_on_load);
					crmNameFind(crm_url_on_load);
					
					function crmNameFind(url)
					{
						
						$.ajax({url: "update_profile.php?action=find&url="+url, success: function(result){
							//console.log(result);
							if(result == "HubSpot"){
								$('#crm_type').val(result);
								$.ajax({url: "update_profile.php?action=findHubSpotKey&url="+url, success: function(apikethubspot){
								console.log(apikethubspot);
								var obj = JSON.parse(apikethubspot);
								
								console.log(apikethubspot);
								$('#secret').val(obj.secret);
								$('#client_id').val(obj.ms_client_id);
								$("#crm_user_id").prop("placeholder", "Enter UserID of CRM Manually");
								$('#secret').attr('readonly', true);
								$('#client_id').attr('readonly', true);
								$('#crm_user_id').attr('readonly', false); 
								$("#crm_user_id").prop('required',true);
								}});
								$('#client_id_div').show();
								$('#secretdiv').show();
								$('#username_div').hide();
								$('#password_div').hide();
								
							}else{
								$('#crm_type').val(result);
								$('#crm_user_id').val("");
								$('#username_div').show();
								$('#password_div').show();

						if(result == "Microsoft Dynamics CRM")
						{
							$('#client_id_div').show();
							$('#secretdiv').hide();
							
						}else {
							$('#secretdiv').show();
							$('#secret').attr('readonly', false);
							$('#client_id_div').hide();
							}
							if(result == "ZOHO CRM")
							{
								$('#username_div').hide();
								$('#password_div').hide();
								$('#crm_username').val("");
								$('#crm_password').val("");
								$('#crm_user_id').attr('readonly', false); 
								$("#crm_user_id").prop("placeholder", "Enter UserID of CRM Manually");
								$('#secret').val("");
								$('#secretdiv').hide();
							}
						
						
							}
						
							
						}}); 
					}
					</script>
                        </div>
                      </div>
					  
					  
					  <div class="form-group">
                        <label for="crm_type" class="col-sm-3 control-label">CRM Type</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="crm_type" value="<?php echo $profileDetails['crm_type']; ?>" readonly>
                        </div>
                      </div>
                      
					  <div class="form-group" id="username_div">
                        <label for="username" class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-6">
                          <input type="text" autocomplete="off"  class="form-control" id="crm_username" value="<?php echo $profileDetails['crm_username']; ?>" placeholder="Enter CRM Username" required>
                        </div>
                      </div>
					  
					  
					  <div class="form-group" id="password_div">
                        <label for="password" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-6">
                          <input type="password" autocomplete="off" class="form-control" id="crm_password" value="<?php echo $profileDetails['crm_password']; ?>" placeholder="Enter CRM Passwoerd" required>
                        </div>
                      </div>
					  
					  
					  
					   <div class="form-group">
                        <label for="crm_user_id" class="col-sm-3 control-label">CRM USER ID</label>
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="crm_user_id" value="<?php echo $profileDetails['crm_user_id']; ?>" placeholder="Fetch Automatically" readonly>
                        </div>
                      </div>
					  
					  <?php if($profileDetails['call_log_in_crm'] == '1')
					  {
						  $value = "checked";
					  }else{
						  $value = "";
					  }  ?>
					  <div class="form-group">
					   <label for="call_log" class="col-sm-3 control-label">Call Log In CRM</label>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" id='call_log' <?php echo $value; ?>>
                        </label>
                      </div>
                    </div>
					  
					  
					    <div class="form-group" id='secretdiv'>
                        <label for="secret" class="col-sm-3 control-label">Secret (Optional)/DB Name/ API KEY</label>
						
                        <div class="col-sm-6">
                          <input type="text" class="form-control" id="secret" value="<?php echo $profileDetails['secret']; ?>" placeholder="Enter Secret/Key if any">
                        </div>
                      </div>
					  <div class="form-group" id="client_id_div" style="display:block;">
                      <label for="client_id" class="col-sm-3 control-label">Client ID / Company ID<span style="color:red"></span></label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="client_id" value="<?php echo $profileDetails['ms_client_id']; ?>" name='client_id' placeholder="Client ID /Company ID">
                      </div>
                    </div>
					  
					    
                      <div class="form-group">
						<label  class="col-sm-3 control-label" >  </label>
						<div class="col-sm-6" id="error_message_crm" style="display:none;color:red;font-size:14px;"> *Please Fill All Details </div>
					</div>
					  
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <input type="button" id="crm_conf" class="btn btn-danger" value="Submit">
                        </div>
                      </div>
                    </form>
                    
                  </div>
				  
				 
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div>
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div>
    
	<?php
	
	  include "footer.php";
	  //include "control_sidebar.php";
	  include "footer_script.php";
	  ?>
	 
	  <script>
	
        //Red color scheme for iCheck
         $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-red',
          radioClass: 'iradio_flat-red'
        });
     
	 
	function abc()
	{
		var form = document.getElementById('uploadform');
		form.submit();
		
	}
	$("#btnfile").click(function () {
	$("#uploadfile").click();
	});
	  
		   $(".select2").select2();
			$("#basic").click(function(){
				var name = $('#name').val();
				var email = $('#email').val();
				var designation = $('#designation').val();
				var phone = $('#phone').val();
				
				
				 var popup_type = $("input[name='popup_type']:checked").val();
           
				
				if(!name || !email || !designation || !phone)
				{
				// $( "#name" ).focus();
				 $('#error_message').show();
					return;
				}
				$('#error_message').hide();
				var dataToSend = "name="+name+"&email="+email+"&designation="+designation+"&phone="+phone+"&popup_type="+popup_type;
				 $('#p_name').html(name);
				 $('#p_email').html(email);
				 $('#p_designation').html(designation);
				 $('#p_phone').html(phone);
				 
				 	 $.ajax({url: "update_profile.php?action=basic&"+dataToSend, success: function(result){
						//console.log(result);
						if(result =='success')
						{
							toastr["success"]("Success : Profile Updated Successfully")
						}else
						{
							toastr["error"]("Failed : Profile Not Updated")
						}
					}}); 

			});
		
		
		
		$("#password_btn").click(function(){
				var old_password = $('#old_password').val();
				var new_password = $('#new_password').val();
			
				if(!old_password || !new_password)
				{
				 $( "#old_password" ).focus();
				 $('#error_message_pass').show();
					return false;
				}
				$('#error_message_pass').hide();
				var dataToSend = "old_password="+old_password+"&new_password="+new_password;
				 	 $.ajax({url: "update_profile.php?action=password&"+dataToSend, success: function(result){
						//console.log(result);
						 if(result =='success')
						{
							toastr["success"]("Success : Password Changed Successfully")
						}else
						{
							toastr["error"]("Failed : Password Not Changed Successfully")
						}
					}}); 

			});
		
		$("#techextension").click(function(){
				var extension = $('#extension').val();
				var asterisk_ip = $('#asterisk_ip').val();
				var channel = $('#cahnnel').val();
				var context = $('#context').val();
				var prefix = $('#prefix').val();
				
				if(!extension || !asterisk_ip || !channel || !context)
				{
				 $( "#extension" ).focus();
				 $('#error_message_tech').show();
					return;
				}
				$('#error_message_tech').hide();
				var dataToSend = "extension="+extension+"&asterisk_ip="+asterisk_ip+"&channel="+channel+"&context="+context+"&prefix="+prefix;
				 $('#p_extension').html(extension);
				
				 
				 	 $.ajax({url: "update_profile.php?action=techextension&"+dataToSend, success: function(result){
						//console.log(result);
						if(result =='success')
						{
							toastr["success"]("Success : Profile Updated Successfully")
						}else
						{
							toastr["error"]("Failed : Profile Not Updated")
						}
					}}); 

			});
		
		
			$("#crm_conf").click(function(){
				var crm_url = $('#crm_url').val();
				var crm_username = $('#crm_username').val();
				var crm_password = $('#crm_password').val();
				var secret = $('#secret').val();
				var crm_type = $('#crm_type').val();
				var crm_user_id = $('#crm_user_id').val();
				var client_id = $('#client_id').val();
				
		if(crm_type=="SalesForce Partner" || crm_type == "SalesForce Enterprise")
		{
			if(!secret)
			{
				toastr["error"]("Info :  Please Enter Valid Security Token");
				return;
			}
		}
		call_log = $('#call_log').prop('checked');
				if(call_log)
				{
					call_log = "1";
				}else{
					call_log = "0";
				}
				
				if(crm_type == "HubSpot" || crm_type == "ZOHO CRM"){}else{
				if(!crm_url || !crm_username || !crm_password || !crm_type)
				{
				 $( "#crm_url" ).focus();
				 $('#error_message_crm').show();
					return;
				}
				}
				
				$('#error_message_crm').hide();
				crm_password = encodeURIComponent(crm_password);
				if(!crm_user_id)
				 {
					var dataToSend = "crm_url="+crm_url+"&crm_username="+crm_username+"&crm_password="+crm_password+"&secret="+secret+"&crm_type="+crm_type;
					$.ajax({url: "getUserID.php?"+dataToSend, success: function(result){
						//console.log(result);
						
					if(crm_type=="Pipedrive CRM" || crm_type=="ZOHO CRM")
					{
						var resultArray = result.split("*");
						if(resultArray[0] != "no")
						{
						 $('#crm_user_id').val(resultArray[0]);
						  $('#secret').val(resultArray[1]);
						  crm_user_id = resultArray[0];
						  secret = resultArray[1];
						  saveConf();
						}else
						{
							toastr["error"]("Info :  Invalid Credentials For Pipedrive CRM");
							saveConf();
						}
					}else
					{
						console.log("RESULTTTT : "+result)
						if(result != "no")
						{
						 $('#crm_user_id').val(result);
						 crm_user_id= result;
						 saveConf();
						}else
						{
							if(crm_type=="SalesForce Partner" || crm_type == "SalesForce Enterprise")
							{
								toastr["error"]("Info :  Invalid Credentials OR Security Token Expired");
							}else{
								toastr["error"]("Info :  Invalid Credentials");
							}
							toastr["warning"]("Note : Your Call Logs Will Assigned to Admin User");	
							crm_user_id = "";
							saveConf();
						}
					}
						
						
						
					}}); 
				 }else
				 {
					 saveConf();
				 }
			function saveConf()
			{
				var dataToSend = "crm_url="+crm_url+"&crm_username="+crm_username+"&crm_password="+crm_password+"&secret="+secret+"&crm_type="+crm_type+"&crm_user_id="+crm_user_id+"&client_id="+client_id+"&call_log="+call_log;
				console.log(dataToSend);
				 $.ajax({url: "update_profile.php?action=crm_conf_admin&"+dataToSend, success: function(result){
						//console.log(result);
						if(result =='success')
						{
							toastr["success"]("Success : Profile Updated Successfully")
						}else
						{
							toastr["error"]("Failed : Profile Not Updated")
						}
					}});
			}
				/* var dataToSend = "crm_url="+crm_url+"&crm_username="+crm_username+"&crm_password="+crm_password+"&secret="+secret+"&crm_type="+crm_type;
				$.ajax({url: "update_profile.php?action=crm_conf_admin&"+dataToSend, success: function(result){
						console.log(result);
						if(result =='success')
						{
							toastr["success"]("Success : Profile Updated Successfully")
						}else
						{
							toastr["error"]("Failed : Profile Not Updated")
						}
					}});  */

			});
		</script>
		<?php
				
$max_file_size = 1024*200; // 200kb
$valid_exts = array('jpeg', 'jpg', 'png', 'gif');
// thumbnail sizes
$sizes = array(160 => 160,25 => 25);
$i=0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' AND isset($_FILES['image'])) {
	if( $_FILES['image']['size'] < $max_file_size ){
		$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
		if (in_array($ext, $valid_exts)) {
			foreach ($sizes as $w => $h) {
				$i++;
				$files[] = resize($w, $h, $i);
			}
			?>
		<script>
		notify('success','Profile Image Changed Successfully. Please Reload to See Changes');
		</script>
		<?php
			
		} else {
		?>
		<script>
		notify('warning','Unsupported File');
		</script>
		<?php
		}
		
	} else{
		?>
		<script>
		notify('warning','Please Upload Image Smaller than 200KB');
		</script>
		<?php
	}
}

function resize($width, $height, $i){

	/* Get original image x y*/
	list($w, $h) = getimagesize($_FILES['image']['tmp_name']);
	/* calculate new image size with ratio */
	$ratio = max($width/$w, $height/$h);
	$h = ceil($height / $ratio);
	$x = ($w - $width / $ratio) / 2;
	$w = ceil($width / $ratio);
	/* new file name */
	mkdir("upload/profile/us_".$_SESSION['tech_admin_id']."/", 0777, true);
	$path = "upload/profile/us_".$_SESSION['tech_admin_id']."/"."profile".$i.".jpg";
	/* read binary data from image file */
	$imgString = file_get_contents($_FILES['image']['tmp_name']);
	/* create image from string */
	$image = imagecreatefromstring($imgString);
	$tmp = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp, $image,
  	0, 0,
  	$x, 0,
  	$width, $height,
  	$w, $h);
	/* Save image */
	switch ($_FILES['image']['type']) {
		case 'image/jpeg':
			imagejpeg($tmp, $path, 100);
			break;
		case 'image/png':
			imagepng($tmp, $path, 0);
			break;
		case 'image/gif':
			imagegif($tmp, $path);
			break;
		default:
			exit;
			break;
	}
	if($i=="1")
	{
	$responce = updateProfilePicture($_SESSION['tech_admin_id'],$path,"admin");
	}
	return $path;
	/* cleanup memory */
	imagedestroy($image);
	imagedestroy($tmp);
	
}
		?>
  </body>
</html>
