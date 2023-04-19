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
			<script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
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


