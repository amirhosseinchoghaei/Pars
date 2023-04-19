<?php
include("../controller/route.php");
Session_start();

if(isset($_SESSION['tech_admin_id']))
{
	$crm_url = $_GET['crm_url'];
	$crm_username = trim($_GET['crm_username']);
	$crm_password = trim($_GET['crm_password']);
	$secret = trim($_GET['secret']);
	$crm_type = $_GET['crm_type'];

	
if(trim($crm_type) == "SugarCRM" || trim($crm_type) == "SuiteCRM")
{	
	$url = $crm_url."/service/v4_1/rest.php";
	$login_parameters = array(
	"user_auth" => array(
	"user_name" => $crm_username,
	"password" => md5($crm_password),
	"version" => "1"
),
	"application_name" => "RestTest",
	"name_value_list" => array(),
);
$login_result = call("login", $login_parameters, $url);
$userID = $login_result->name_value_list->user_id->value;
if(!$userID)
{
	$userID = "no";
}
echo $userID;

}
if(trim($crm_type) == "SalesForce Partner" || trim($crm_type) == "SalesForce Enterprise" )
{
	
	if(trim($crm_type) == "SalesForce Partner")
	{
		
		require_once ('../controller/salesforce/soapclient/SforcePartnerClient.php');
		$mySforceConnection = new SforcePartnerClient();
		
		$mySforceConnection->createConnection("../controller/salesforce/soapclient/partner.wsdl.xml");
	}else{
		
		require_once ('../controller/salesforce/soapclient/SforceEnterpriseClient.php');
		$mySforceConnection = new SforceEnterpriseClient();
		
		$mySforceConnection->createConnection("../controller/salesforce/soapclient/enterprise.wsdl.xml");
		//$mySforceConnection->createConnection("../controller/salesforce/soapclient/sandbox_enterprise_wsdl.xml");
		//$mySforceConnection->setEndPoint("https://anitiger--test2.my.salesforce.com/services/Soap/c/48.0/00D0k0000001FWR/0DF0k00000003BH");
		
	}
	
	$mySforceConnection->login($crm_username, $crm_password.$secret);
	//print_r($mySforceConnection);
	$userID = $mySforceConnection->getUserInfo()->userId;
	if(!$userID)
	{
		$userID = "no";
	}
	echo $userID;
}
if(trim($crm_type) == "VtigerCRM")
{
	$endpointUrl = $crm_url."/webservice.php";
	$sessionData = call_vtiger($endpointUrl, array("operation" => "getchallenge", "username" => $crm_username));
	$challengeToken = $sessionData['result']['token'];
	$generatedKey = md5($challengeToken . $secret);
	$dataDetails = call_vtiger($endpointUrl, array("operation" => "login", "username" => $crm_username, "accessKey" => $generatedKey), "POST");
	$userID = $dataDetails['result']['userId'];
	if(!$userID)
	{
		$userID = "no";
	}
	echo $userID;
}

if(trim($crm_type) == "Pipedrive CRM")
{
	$data = array(
	'email' => $crm_username,
	'password' => $crm_password
	);
$url = $crm_url.'/v1/authorizations';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


$output = curl_exec($ch);
curl_close($ch);

$result = json_decode($output, true);

if (!empty($result['data'][0]['api_token'])) {
 $accessToken  =  $result['data'][0]['api_token'];
  $userID  =  $result['data'][0]['user_id'];
}
else{
	$userID = "no";
	$accessToken  =  "no";
}

	echo $userID."*".$accessToken;
}
if(trim($crm_type) == "ZOHO CRM")
{
	include('../controller/zohocrm/Requests.php');
	Requests::register_autoloader();
	
	$request = Requests::get("https://accounts.zoho.com/apiauthtoken/nb/create?SCOPE=ZohoCRM/crmapi&EMAIL_ID=$crm_username&PASSWORD=$crm_password&DISPLAY_NAME=zohoasteriskapi", array('Accept' => 'application/json'));	
	list($a, $b) = explode('AUTHTOKEN=', $request->body);
	list($c, $d) = explode('RESULT=', $b);
	
$request = Requests::get("https://crm.zoho.com/crm/private/json/Users/getUsers?authtoken=$authtoken&scope=crmapi&type=AllUsers", array('Accept' => 'application/json')); 
$jsonArray = json_decode($request->body);

 if(count($jsonArray->users->user) >= 1)
	{
			for ($x = 0; $x <=count($jsonArray->users->user) ; $x++)
				{
					if($jsonArray->users->user[$x]->phone==$Extension)
					{
						$UserID=$jsonArray->users->user[$x]->id;
						break;
					}
				} 
	}
	if(!$UserID)
	{
		$UserID="1";
	} 
	
	
 if ($c) {
 $accessToken  =  $c;
  $userID  =  $UserID;
}
else{
	$userID = "no";
	$accessToken  =  "no";
}

	echo $UserID."*".$c; 
}
if(trim($crm_type) == "Odoo")
{

		require_once('../controller/odoocrm/library/ripcord.php');
		$common = ripcord::client("$crm_url/xmlrpc/2/common");
		$uid = $common->authenticate($secret, $crm_username, $crm_password, array());
		Echo $uid;
}
if(trim($crm_type) == "AgileCRM")
{
	
	 require_once('../controller/agilecrm/CurlLib/curlwrap_v2.php');
	// echo $secret;
	
$currentUser = curl_wrap("users/current-user", null, "GET", NULL,$crm_url,$crm_username,$secret);
//print_r($currentUser);
 $currentUser = json_decode($currentUser, false, 512);

if(!$currentUser->id)
{
	$userID = "no";
}else
{
	$userID = $currentUser->id;
}
echo $userID;
}
	
}else{
	echo "Not Authorized";
}

/* 
	$responseData = getProfile($_SESSION['tech_user_id']);
	$profileDetails = $responseData['data'];
	$extension = $profileDetails['extension']; */
?>
