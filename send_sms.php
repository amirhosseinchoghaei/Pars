<?php
/* 	ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('display_errors', 'On'); */
//http://localhost/techextension/te-admin/send_sms.php?tech_mobile=8733874123&sms=Heymahavir&user_id=1
	include('../controller/route.php');
	require __DIR__ . '/Techextension/Services/Twilio/autoload.php';
	use Twilio\Rest\Client;
	$smsConfiguration = getSMSConfiguration();
	if($smsConfiguration['status'] == "0"){
		echo "Please Configure SMS API to Send SMS using AsterCTI";
		exit;
	}
	$username_label=$smsConfiguration['data']['username_label'];
	$username_value=$smsConfiguration['data']['username_value'];
	$password_label=$smsConfiguration['data']['password_label'];
	$password_value=$smsConfiguration['data']['password_value'];
	$param1_label=$smsConfiguration['data']['param1_label'];
	$param1_value=$smsConfiguration['data']['param1_value'];
	$param2_label=$smsConfiguration['data']['param2_label'];
	$param2_value=$smsConfiguration['data']['param2_value'];
	$type=$smsConfiguration['data']['type'];
	$type = trim($type);
	$url=$smsConfiguration['data']['url'];
	$sms_label=$smsConfiguration['data']['sms_label'];
	$to_send_label=$smsConfiguration['data']['to_send_label'];
	
	$mobile_no = trim($_GET['tech_mobile']);
	//remove + compulsory
	$mobile_no = ltrim($mobile_no, '+'); 
	
	if(isset($_GET['user_id']))
	{
		$user_id = trim($_GET['user_id']);
	}else
	{
		//search user id from email and pass.
		$email = trim($_GET['username']);
		if(isset($_GET['action']))
		{
			$password = trim($_GET['password']);
			$password = base64_decode($password);
		}
		else
		{
			$password = trim($_GET['password']);
		}

		$response = CheckLogin($email."*".$password);
		$responseData = $response['data'];
		if($response['status'] != "0"){
			$user_id = trim($responseData['user_id']);
		}else{
			$response = CheckAdminLogin($email."*".$password);
			
			$responseData = $response['data'];
			$user_id = trim($responseData['user_id']);
			if(!$user_id)
			{
				echo "NOT VALID USER";
				Exit;
			}
		}
	}
	if(isset($_GET['testing']))
	{
		$smsContent = urlencode("This is Test SMS From AsterCTI. Developed by Techextension.");
	}else
	{
		$smsContent = urlencode($_GET['sms']);
	}
	
	//add + compulsory
	$mobile_no = "+".$mobile_no;
	
	$techCommonURL = $username_label."=".$username_value."&".$password_label."=".$password_value."&".$param1_label."=".$param1_value."&".$param2_label."=".$param2_value."&".$sms_label."=".$smsContent."&".$to_send_label."=".$mobile_no;
	
	if(strpos($url, "?") !== false){
		$techReadyToSendURL = $url."&".$techCommonURL;
	} else{
		$techReadyToSendURL = $url."?".$techCommonURL;
	}
	
	
	$errorMessage="";
	if($type == "twillio")
	{
	
		$twilio_from_number=trim($smsConfiguration['data']['twillio_from']);
		$twilio_acc_sid=trim($smsConfiguration['data']['twillio_sid']);
		$twilio_auth_token=trim($smsConfiguration['data']['twillio_token']);
		//print_r($twilio_from_number);
		try {
				$client = new Client($twilio_acc_sid, $twilio_auth_token);
			
				$output = $client->messages->create(
					$mobile_no,
					array(
					'from' => $twilio_from_number,
					'body' => $smsContent
					)
				);
				//print_r($output);
				
				$output =$output->sid;
		} catch (Exception $e) {
			//print_r($e);
			//echo $e->getMessage();
			$errorMessage = $e->getMessage();
		}
	}else if($type == "http")
	{
		$output = file_get_contents($techReadyToSendURL);
		$xml = simplexml_load_string($output);
		if ($xml === false) {
			echo "Failed loading XML: ";
			foreach(libxml_get_errors() as $error) {
				$errorMessage = $error->message;
			}
		} else {
			if(empty($xml->data->errorcode)){
				//echo "no error";
				$output = $xml->data->acceptreport->messageid;
				$errorMessage="";
			}else{
				$errorMessage = $xml->data->errormessage[0];
			}
		}
	}else if($type == "curl")
	{
		//$post_body = eight_bit_sms( $username, $password, $eight_bit_msg, $msisdn );
		
		$URL = str_replace(' ', '%20', $url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS,$URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec ($ch);
		curl_close ($ch);
	}
	
	if(isset($_GET['contact_id']))
	{
		$contact_id = trim($_GET['contact_id']);
	}else
	{
		$contact_id="";
	}

if(!$errorMessage){
	echo "SMS Sent Successfully";
	$smsstatus = "Sent";
}else{
	echo $errorMessage;
	$smsstatus = "Failed";
}
	
createSMSLog($mobile_no,$smsContent,$user_id,$smsstatus,$output,'OutGoing','read',$contact_id);
$sms_id = getSMSID($mobile_no,$smsContent,$user_id);
$sms_id = $sms_id['data'];


$techextensionModules = getAllLicenseModuleInfo();
$smsFlag = true;
for($i=0;$i<count($techextensionModules['data']);$i++)
		{
			if($techextensionModules['data'][$i]['name'] == "SMS")
			{
				if($techextensionModules['data'][$i]['status'] != "1")
				{
					$smsFlag=false;
					exit;
				}
			}
		}
		
if($smsFlag)
{
	$userInfo = getUserInfoFromId($user_id);
	
if($userInfo['count'] == "0")
{
	$userInfo = getAdminInfoFromId($user_id);
}
	if($userInfo['count'] != '0')
	{
		$username = trim($userInfo['data'][0]['crm_username']);
		$password = trim($userInfo['data'][0]['crm_password']);
		$api_token = trim($userInfo['data'][0]['secret']);
	}else{
		echo "NO USER ID FOUND FOR ASTERCTI PORTAL";
		exit;
	}
	$extension = trim($userInfo['data'][0]['extension']);
	$crm_user_id = trim($userInfo['data'][0]['crm_user_id']);
	
	if(trim($userInfo['data'][0]['crm_type']) == "AgileCRM")
	{
		$crmurl = trim($userInfo['data'][0]['crm_url']);
		if($api_token)
		{
			include_once('../controller/agilecrm/smslog.php');
			$data = create_sms_in_agile($mobile_no,$api_token,$crmurl,$extension,$username,$smsContent,$crm_user_id,"out");
			if($data)
			{
				updateSMSLogAfterSearch($data,$sms_id);
			}

		}else{
				echo "No Acees Token Found...Please goto Profile and save Valid Access Token";
		}
	}
	
	if(trim($userInfo['data'][0]['crm_type']) == "Odoo")
	{
		$crmurl = trim($userInfo['data'][0]['crm_url']);
		if($api_token)
		{
			include_once('../controller/agilecrm/smslog.php');
			$data = create_sms_in_odoo($mobile_no,$db,$crmurl,$extension,$username,$password,$smsContent,$crm_user_id,"out");
			if($data)
			{
				updateSMSLogAfterSearch($data,$sms_id);
			}

		}else{
				echo "No Acees Token Found...Please goto Profile and save Valid Access Token";
		}
	}
}
?>
