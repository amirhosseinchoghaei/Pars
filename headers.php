<?php
include_once("controller/route.php");

if(!$_SESSION['tech_admin_id'])
{
	echo "<script>location.href='login.php'</script>";
}
$url=basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);

if($url == "live_report.php" || $url == "queue.php")
{
	removeAllQueue();
}
$current_user= $_SESSION['tech_admin_id'];
$responseData = getAdminProfile($current_user);
$profileDetails = $responseData['data'];
$asteriskData = getAllAsteriskInfo();
$asteriskDataCount = count($asteriskData['data']);
$company_name = getCompanyName();
$three_company_name = substr($company_name, 0, 3);
$portalURL=getPortalURL();
$ticketPriority=array("Low","Normal","High","Urgent");
?>


<?php
		 if(!$_SESSION['ActualIP'])
		 {
			$asteriskInfo = getAsteriskById($_SESSION['asteriskip']);
			$AsteriskIP =$asteriskInfo['data'][0]['ip'];
			$_SESSION['ActualIP'] = $AsteriskIP ;
		 }
		 
	  if($_SESSION['user_extension'])
	  {
	  ?>
	  <script>
	   var user_extension = <?php echo json_encode($_SESSION['user_extension']) ?>;
	   var asteriskip = <?php echo json_encode($_SESSION['ActualIP']) ?>;
	   var admin_channel = <?php echo json_encode($_SESSION['user_channel']) ?>;
	   var user_id = <?php echo json_encode($_SESSION['tech_admin_id']) ?>;
	   var VBCallLimit = <?php echo json_encode($VBCallLimit) ?>;
	   
	   // Start For Originate Call Dialer //
		var user_context = <?php echo json_encode($profileDetails['context']); ?>;
		var user_channel = <?php echo json_encode($profileDetails['channel']); ?>;
		var user_prefix = <?php echo json_encode($profileDetails['prefix']); ?>;
		// End For Originate Call Dialer //
	   
	   
	   var path = window.location.pathname;
var page = path.split("/").pop();
	   var admin_value="";
		if(page == "live_report.php")
		{
			 admin_value = "y";
		}else{
			 admin_value = "n";
		}		
	  </script>
			<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
			<script type="text/javascript" src="dist/js/jWebSocket.js"></script>
			<script type="text/javascript" src="dist/js/techextension.js"></script>
			
			
			
			
	  <?php 
	  }else
	  {
	  ?>
	  <script>
	  toastr["warning"]("Important : Please Enter your Extension in Profile First")
	  </script>

	  <?php  } 
	  
if($_SERVER['QUERY_STRING'] != "syncLicense")
{
	if($portalURL['status'] == "0" || $portalURL['mac'] =="" || $portalURL['user'] ==""|| $portalURL['no_of_crm'] ==""|| $portalURL['no_of_asterisk'] =="")
	{
		echo "<script>location.href='license.php?syncLicense'</script>";
	}
}
$techextensionModules = getAllLicenseModuleInfo();
$VBCallLimit = getVBCallLimit();
?>


<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $company_name; ?> | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "header_css.php"; ?>

	<link rel="icon" href="../image/favicon.ico" type="image/x-icon">
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
	 
      <header class="main-header">
        <!-- Logo -->
        <a href="index.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b><?php echo $three_company_name; ?></b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b><?php echo $company_name; ?></b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
		 
	 
          <div class="navbar-custom-menu" >
		 
            <ul class="nav navbar-nav">
        
            
            
              
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?php echo $profileDetails['profile_image']; ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo $profileDetails['name']; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo $profileDetails['profile_image']; ?>" class="img-circle" alt="User Image">
                    <p>
                      <?php echo $profileDetails['name']; ?> - <?php echo $profileDetails['designation']; ?>
                      <!--<small>Member since Nov. 2012</small>-->
                    </p>
                  </li>
                  <!-- Menu Body -->
                 
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="profile.php" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                      <a href="logout.php" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
           <!--
		   <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
           </li>
			-->  
			  
            </ul>
          </div>
		    <div class="navbar-custom-menu" id='loginhistory' onclick='removeInterval()'></div>
			
			<div class="navbar-custom-menu">
		
		<img src='image/dialer.ico' style='margin-top:12px;' data-toggle="modal" data-target="#abc" height='28px' width='28px' title='Dial Pad'>
		
		</div>
		<div class="navbar-custom-menu" id='' onclick=''></div>
		 <a href="https://techextension.freshdesk.com/support/solutions/folders/22000173126" target="_blank" class="btn btn-success" style='margin-top:12px;' title='Configuration Guide'><span class="ion ios-build"></span> Configuration Guide</a>
			
        </nav>

<link rel="stylesheet" type="text/css" href="dist/css/csshack.css">
		<script type="text/javascript" src="toastr/toastr.min.js"></script>
		<link rel="stylesheet" type="text/css" href="toastr/toastr.css">
		
      </header>

<script>


$(window).focus(function(e) {
	initConnectServer();
});

$.ajax({url: "getUserLogin.php", success: function(result){ document.getElementById("loginhistory").innerHTML = result; }}); 
function getlogindetails() { $.ajax({url: "getUserLogin.php", success: function(result){
//console.log(result);
	document.getElementById("loginhistory").innerHTML = result; }}); }
var interval = setInterval( getlogindetails , 5000)

function removeInterval()
{
	clearInterval(interval);
}
		 

		 </script>
		 
<div class="modal fade" id="abc" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
   
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Number To Call:</label>
            <input type="text" class="form-control" id="callto" autofocus>
          </div>
        </form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick='originateCalls()'>Dial</button>
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
function originateCalls()
{
	var callto = $('#callto').val();
	toastr["success"]("Dialing Number : "+callto);
	originateCall(callto,user_context,user_channel,user_extension,user_prefix);
}

function originateDirectCall(callto){
	toastr["success"]("Dialing Number : "+callto);
	originateCall(callto,user_context,user_channel,user_extension,user_prefix);
}


function sendSMS(){
	
	var tosend = $('#to_send').val();
	var text = $('#sms').val();
	
	if(typeof(contact_id) != "undefined" && contact_id !== null) {  
		$('#phone_tosend').html("Send SMS To : "+tosend+" Contact Name : "+contact_name)
	}else{
		contact_id = "";
		$('#phone_tosend').html("Send SMS To : "+tosend)
	}
	
	console.log("Contact id :"+ contact_id+" user id : "+user_id+" Phone To send : "+tosend+" SMS : "+text);
	
	$.ajax({
		type: "GET",
		url: 'send_sms.php',
		data: 'tech_mobile='+tosend+"&sms="+text+'&user_id='+user_id+'&contact_id='+contact_id,
		success: function(data){
			console.log(data);
			toastr["info"](data);
			$('#send_sms').modal('toggle');
		}
		});
}


</script>

<div class="modal fade" id="send_sms" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
   
      <div class="modal-body">
	  	  
	   <div class="form-group">
            <label for="recipient-name" class="col-form-label" id="phone_tosend"></label>
        </div>
	  
        <form>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Enter SMS:</label>
			<textarea class="form-control" id="sms" rows="5" cols="50" autofocus></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
		<button type="button" class="btn btn-primary" onclick='sendSMS()'>Send SMS</button>
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<style>
.opacity {
filter:alpha(opacity=20); 
-moz-opacity:0.2; 
opacity: 0.2; 
}
</style>
