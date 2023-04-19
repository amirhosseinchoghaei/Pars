<?php
include "../controller/route.php";
$action = trim($_GET['action']);
if($action == "basic")
{
	$name = $_GET['name'];
	$email = $_GET['email'];
	$designation = $_GET['designation'];
	$phone = $_GET['phone'];
	$popup_type = $_GET['popup_type'];
	$dataToSend = $_SESSION['tech_admin_id']."*".$name."*".$email."*".$designation."*".$phone."*".$popup_type;
	$status = updateAdminBasicProfile($dataToSend);
	//print_r($status);
	if($status['status'] = 1)
	{
		echo "success";
	}else
	{
		echo "failed";
	}
}

if($action == "portalurl")
{
	$url = $_GET['url'];
	createPortalURL($url);
	echo "success";
}
if($action == "password")
{
	$old_password = $_GET['old_password'];
	$new_password = $_GET['new_password'];
	
	$status = changeAdminPassword(array($_SESSION['tech_admin_id'],$old_password,$new_password));
	//print_r($status);
	  if($status['status'] == 1)
	{
		echo "success";
	}else
	{
		echo "failed";
	}
}


if($action == "techextension")
{
//var dataToSend = "extension="+extension+"&asterisk_ip="+asterisk_ip+"&channel="+channel+"&context="+context+"&prefix="+prefix;
	$extension = $_GET['extension'];
	$asterisk_ip = $_GET['asterisk_ip'];
	$channel = $_GET['channel'];
	$context = $_GET['context'];
	$prefix = $_GET['prefix'];
	
	
	$asteriskInfo = getAsteriskById($asterisk_ip);
	
	$AsteriskIP =$asteriskInfo['data'][0]['ip'];
	$_SESSION['ActualIP'] = $AsteriskIP ;
	
	
	$dataToSend = $_SESSION['tech_admin_id']."*".$extension."*".$asterisk_ip."*".$channel."*".$context."*".$prefix;
	$status = updateAdminTechextensionProfile($dataToSend);
	if($status['status'] = 1)
	{
		$_SESSION['user_extension'] = $extension;
		$_SESSION['user_channel'] = $context;
		echo "success";
	}else
	{
		echo "failed";
	}
}




if($action == "crm_conf_admin")
{
	$crm_url = $_GET['crm_url'];
	$crm_username = $_GET['crm_username'];
	$crm_password = $_GET['crm_password'];
	$secret = $_GET['secret'];
	$crm_type = $_GET['crm_type'];
	$crm_user_id = $_GET['crm_user_id'];
	$client_id = $_GET['client_id'];
	$call_log = $_GET['call_log'];
	$dataToSend = $_SESSION['tech_admin_id']."*".$crm_url."*".$crm_username."*".$crm_password."*".$secret."*".$crm_type."*".$crm_user_id."*".$client_id."*".$call_log;
	$status = updateAdminProfileCRM($dataToSend);
	//print_r($dataToSend);
	 if($status['status'] = 1)
	{
		echo "success";
	}else
	{
		echo "failed";
	} 
}






?>