<?php include "header.php" ?>
<html>
<head>
     <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Asterisk Dialer</title>
	<link rel="stylesheet" href="../css/style.css" type="text/css" media="all" />
	<script src='funciones.js'></script>
<script>
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'location=0,width=320,height=420,resizable=0,scrollbars=no');
return false;
}
</script>

</head>
<body>

<!-- Header -->
<div id="header">

	<div class="shell">
	
		<!-- Logo + Top Nav -->
		
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1>
			<div id="top-navigation">
			
				<a href="../index.php"> Home Page <</a>
				
				<span>|</span>
                                <a href="../logout.php">logout</a>

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>




<!-- End Header -->
</body>
</html>


        <h1> </h1>
                <center>
				  
				  
				
			    
				  <h1> </h1>
                   <form class="form-horizontal">
                      <div class="form-group">
                        <label for="old_password" class="col-sm-3 control-label"></label>
                        <div class="col-sm-6">
						
                          <input type="password" class="form-control" id="old_password" placeholder="Enter Old Password" required>
                        </div>
						
                      </div>
					  <h1> </h1>
                      <div class="form-group">
                        <label for="new_password" class="col-sm-3 control-label"></label>
                        <div class="col-sm-6">
                          <input type="password" class="form-control" id="new_password" placeholder="Enter New Password" required>
                        </div>
                      </div>
                      
					  <h1> </h1>
					  <div class="form-group">
						<label  class="col-sm-3 control-label" >  </label>
						<div class="col-sm-6" id="error_message_pass" style="display:none;color:red;font-size:14px;"> *Please Fill All Details </div>
					</div>
					  
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-6">
                          <input type="button" id="password_btn" class="btn btn-danger my2Button" value="Change Password">
						  
                        </div>
                      </div>
                    </form>
					</div>
				  	 
					  </div>
					  <h1> </h1>
				  </center>
					
					
					<body>

<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1>
			<div id="top-navigation">
				
				<span>|</span>
				<a href="https://t.me/AmirHosseinTLS">Powered By AmirHossein</a>
				<span>|</span>
                                

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>
<!-- End Header -->
</body>
					
					<script>
					var crm_url_on_load = <?php echo json_encode($profileDetails['crm_url']) ?>;
					//console.log(crm_url_on_load);
					crmNameFind(crm_url_on_load);
					
					function crmNameFind(url)
					{
						
						$.ajax({url: "../update_profile.php?action=find&url="+url, success: function(result){
							//console.log(result);
							if(result == "HubSpot"){
								$('#crm_type').val(result);
								$.ajax({url: "../update_profile.php?action=findHubSpotKey&url="+url, success: function(apikethubspot){
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
					  
					
    
	<?php
	

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



















<!DOCTYPE html>
<html>
<head>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300&family=Cairo:wght@200&family=Roboto:wght@300&family=Tajawal:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
body {
  font-family: 'Roboto', sans-serif;
   font-size: 16px;
  background: #FFFFFF;
background: -moz-linear-gradient(top, #FFFFFF 0%, #FFFFFF 68%, #1F95FF 100%);
background: -webkit-linear-gradient(top, #FFFFFF 0%, #FFFFFF 68%, #1F95FF 100%);
background: linear-gradient(to bottom, #FFFFFF 0%, #FFFFFF 68%, #1F95FF 100%);
  
  background-color: white;
  color: black;
  font-size: 16px;
}


#borderDemo {
border-bottom: 10px solid #134B70;
border-radius: 25px 18px 21px 23px;
}


#demoObject {
-webkit-box-shadow: 0px -2px 6px 0px #0A0A0A; 
box-shadow: 0px -2px 6px 0px #0A0A0A;
background: #FFFFFF;
}
body#tinymce {
background: #FAFAFA
}


a:link {
  color: green;
  background-color: transparent;
  text-decoration: none;
}

a:visited {
  color: green;
  background-color: transparent;
  text-decoration: none;
}

a:hover {
  color: red;
  background-color: transparent;
  text-decoration: underline;
}

a:active {
  color: red;
  background-color: transparent;
  text-decoration: underline;
}


.button {
  background-color: #4a4a4a; /* Green */
  border: none;
  color: white;
  padding: 10px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-white;
  font-size: 14px;
  margin: 2px 2px;
  cursor: pointer;
}

.button1 {width: 250px;}
.button2 {width: 50px;}
.button3 {width: 100%;}
.myButton {width: 250px;}
.my1Button {width: 250px;}
.my2Button {width: 250px;}

.myButton {
	box-shadow:inset 0px 1px 0px 0px #f5978e;
	background:linear-gradient(to bottom, #f24537 5%, #c62d1f 100%);
	background-color:#f24537;
	border-radius:6px;
	border:1px solid #d02718;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #810e05;
}
.myButton:hover {
	background:linear-gradient(to bottom, #c62d1f 5%, #f24537 100%);
	background-color:#c62d1f;
}
.myButton:active {
	position:relative;
	top:1px;
}



.my1Button {
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);
	background-color:#f9f9f9;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	cursor:pointer;
	color:#666666;
	
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #ffffff;
}
.my1Button:hover {
	background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);
	background-color:#e9e9e9;
}
.my1Button:active {
	position:relative;
	top:1px;
}

        


.my2Button {
	box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	background:linear-gradient(to bottom, #b8e356 5%, #a5cc52 100%);
	background-color:#b8e356;
	border-radius:6px;
	border:1px solid #83c41a;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #86ae47;
}
.my2Button:hover {
	background:linear-gradient(to bottom, #a5cc52 5%, #b8e356 100%);
	background-color:#a5cc52;
}
.my2Button:active {
	position:relative;
	top:1px;
}





.dark-mode {
  background-color: black;
  color: white;
}
</style>
</head>
<body>




<script>
function myFunction() {
   var element = document.body;
   element.classList.toggle("dark-mode");
}
</script>

</body>
</html>























<!DOCTYPE html>
<html>
<head>
<style>
#more {display: none;}
</style>
</head>
<body>


<script>
function moresFunction() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Show All Logs"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Hide All Logs"; 
    moreText.style.display = "inline";
  }
}
</script>

</body>
</html>








<!DOCTYPE html>
<html>
<style>

* {box-sizing: border-box;}

/* Set a style for all buttons */


/* Float cancel and delete buttons and add an equal width */
.cancelbtn, .deletebtn {
  float: left;
  width: 50%;
}

/* Add a color to the cancel button */
.cancelbtn {
  background-color: #ccc;
  color: black;
}

/* Add a color to the delete button */
.deletebtn {
  background-color: #f44336;
}

/* Add padding and center-align text to the container */
.container {
  padding: 16px;
  text-align: center;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: #474e5d;
  padding-top: 50px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* Style the horizontal ruler */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}
 
/* The Modal Close Button (x) */
.close {
  position: absolute;
  right: 35px;
  top: 15px;
  font-size: 40px;
  font-weight: bold;
  color: #f1f1f1;
}

.close:hover,
.close:focus {
  color: #f44336;
  cursor: pointer;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and delete button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .deletebtn {
     width: 100%;
  }
}
</style>
<body>





<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</body>
</html>

