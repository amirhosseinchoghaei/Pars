<?php
	error_reporting(0);
	//date_default_timezone_set('Asia/Kolkata');
	require 'Database_Class.php';
	ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	ini_set('display_errors', 'On');
	session_start();
	
function load_csv($current_user,$map,$filename,$flag,$campaign_id) {
   $rows = array_map('str_getcsv', file($filename));
	$header = array_shift($rows);
	$csv = array();
	foreach ($rows as $row) {
		$csv[] = array_combine($header, $row);
	}
	for($j=0;$j<count($map);$j++)
	{
		$first_name_index = $map['first_name'];
		$last_name_index = $map['last_name'];
		$email_index = $map['email'];
		$phone_index = $map['phone'];
		$phone2_index = $map['phone2'];
		$address_index = $map['address'];
		$prefix_index = $map['prefix'];
		$city_index = $map['city'];
		$postal_code_index = $map['postal_code'];
		$country_index = $map['country'];
	}
	 for($j=0;$j<count($csv);$j++)
	 {
		 $contact_data = createContact($current_user,$csv[$j][$first_name_index],$csv[$j][$last_name_index],$csv[$j][$email_index],$csv[$j][$phone_index],$csv[$j][$address_index],$csv[$j][$phone2_index],"","",$csv[$j][$prefix_index],$csv[$j][$city_index],$csv[$j][$postal_code_index],$csv[$j][$country_index]);
		 $contact_id = $contact_data['id'];
		 if($flag == "1")
		 {
			 addMemberToCampaign($contact_id,$campaign_id,$current_user);
		 }else if($flag == "2")
		 {
			 addMemberToVoiceBroadcast($contact_id,$campaign_id,$current_user);
		 }
		 else if($flag == "3")
		 {
			 addContactsToDialer($contact_id,$campaign_id,$current_user);
		 }
	 }
	 return "success";
  }
function callNewSugar($search_url,$oauth_token)
{
$search_request = curl_init($search_url);
curl_setopt($search_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($search_request, CURLOPT_HEADER, false);
curl_setopt($search_request, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($search_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($search_request, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($search_request, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "oauth-token: {$oauth_token}"
));

//execute request
$search_response = curl_exec($search_request);

//decode json
$search_response_obj = json_decode($search_response);
return $search_response_obj;
}

function createContact($current_user,$first_name,$last_name,$email,$phone,$address,$phone2,$note,$status,$prefix,$city,$postal_code,$country)
{
	$result_Arr=array();
	$to_fetch="*";
	if($email){
		$where_cond = "phone='$phone' or email='$email' ";
	}else{
		$where_cond = "phone='$phone'";
	}
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
		//if(true)
		if($count == 0)
		{
			$user="";
			$tablename='contacts';
			$fields='`first_name`, `last_name`,`email` ,`phone`, `address`, `date_created`, `date_modified`, `phone2`, `user_id`, `note`, `status`, `assigned_user`,`prefix`,`city`,`postal_code`,`country`';
			$value="'$first_name','$last_name','$email','$phone','$address',NOW(),NOW(),'$phone2','$current_user','$note','$status','$current_user','$prefix','$city','$postal_code','$country'";
			$result=insert_record($fields,$value,$tablename);
			//$resultsss=insert_record_max($fields,$value,$tablename);
			//$result_Arr['insert']=$value;
		  if($result){
			 $to_fetch="*";
			$tablename='contacts';
			$where_cond="first_name='$first_name' and last_name='$last_name' and date_created=NOW()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($results)){
				$id=$row['id'];
			} 
			 $result_Arr['id']=$id;
			 $result_Arr['status']="1";
		  }
		}else{
			 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
			} 
				$result_Arr['id']=$id;
				$result_Arr['status']="0";
		}
    return $result_Arr;
}



function createCustomer($current_user,$first_name,$last_name,$email,$phone,$address,$phone2,$note,$status,$prefix,$city,$postal_code,$country,$password,$company_name)
{
	$result_Arr=array();
	$to_fetch="*";
	if($email){
		$where_cond = "phone='$phone' or email='$email' ";
	}else{
		$where_cond = "phone='$phone'";
	}
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
		//if(true)
		if($count == 0)
		{
			$user="";
			$tablename='contacts';
			$fields='`first_name`, `last_name`,`email` ,`phone`, `address`, `date_created`, `date_modified`, `phone2`, `user_id`, `note`, `status`, `assigned_user`,`prefix`,`city`,`postal_code`,`country`,`secret`,`company`';
			$value="'$first_name','$last_name','$email','$phone','$address',NOW(),NOW(),'$phone2','$current_user','$note','$status','$current_user','$prefix','$city','$postal_code','$country','$password','$company_name'";
			$result=insert_record($fields,$value,$tablename);
			//$resultsss=insert_record_max($fields,$value,$tablename);
			//$result_Arr['insert']=$value;
		  if($result){
			 $to_fetch="*";
			$tablename='contacts';
			$where_cond="first_name='$first_name' and last_name='$last_name' and date_created=NOW()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($results)){
				$id=$row['id'];
			} 
			 $result_Arr['id']=$id;
			 $result_Arr['status']="1";
		  }
		}else{
			 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
			} 
				$result_Arr['id']=$id;
				$result_Arr['status']="0";
		}
    return $result_Arr;
}

function getTotalBreakTaken($user_id){
	$to_fetch="sum(`duration`) as duration";
	$tablename='user_break_calculator';
	$where_cond="user_id='$user_id' and DATE(date_time)=CURDATE()";
	$results=select_record($to_fetch,$where_cond,$tablename);
		while($row=mysqli_fetch_array($results)){
			$duration=$row['duration'];
		}
		if(!$duration || $duration == null){
			$duration=0;
		}
	return $duration;
}

function updateExtensionQueueStatus($extension,$status,$queue)
{
	$id="";
	$to_fetch="*";
	$tablename='extension_queue_status';
	$where_cond="extension='$extension' and DATE(date_time)=CURDATE()";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	
	if(!$id)
	{
		$condition="extension='$extension'";
		$result=delete_record($condition,$tablename);
		
		$fields='`extension`, `status`, `queue`, `date_time`';
		$value="'$extension','$status','',NOW()";
		$result=insert_record($fields,$value,$tablename);
	}
	else{
		$condition="extension='$extension'";
		$fields="status='$status',date_time=NOW()";
		$result=update_record($fields,$condition,$tablename);
	}
	return $result_Arr;
}

function getAgentsInCampaign($campaign_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAllAgentInAllCampaign()
{
	 
	$result_Arr=array();
	$to_fetch="DISTINCT agent_id";
	$where_cond = "1";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row['agent_id'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAgentsRealtimeDialer($campaign_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	if($campaign_id == "all"){
		$where_cond = "campaign_id is not null order by status desc";
	}else{
		$where_cond = "campaign_id='$campaign_id' order by status desc";
	}
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$status[]=$row['status'];
				$agent_id[]=$row['agent_id'];
				$campaign_ids[]=$row['campaign_id'];
				$user_info = getUserInfoFromId($row['agent_id']);
				$name = $user_info['data'][0]['name'];
				$agent_name[]=$name;
				if($campaign_id == "all"){
					$conferenceInfo = getAgentConferenceStatusInCampaign($row['agent_id'],$row['campaign_id']);
				}else{
					$conferenceInfo = getAgentConferenceStatus($row['agent_id']);
				}
				//$conferenceInfo = getAgentConferenceStatus($row['agent_id']);
				$agent_conference[] = $conferenceInfo['conference'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['agent_id']=$agent_id;
			$result_Arr['agent_name']=$agent_name;
			$result_Arr['campaign_id']=$campaign_ids;
			$result_Arr['status']=$status;
			$result_Arr['agent_conference']=$agent_conference;
			
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['agent_id']="";
			$result_Arr['agent_name']="";
			$result_Arr['campaign_id']="";
			$result_Arr['status']="";
			$result_Arr['agent_conference']="";
	  }
	  return $result_Arr;
}


function getAgentConferenceStatus($agent_id){
	$to_fetch="*";
	$where_cond = "agent='$agent_id' and channel !=''";
	$tablename="dialer_conference";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$conference=$row['conference'];
				  }
				  $result_Arr['conference']=$conference;
				}else{
					$result_Arr['conference']="0";
				}
return $result_Arr;
}

function getAgentConferenceStatusInCampaign($agent_id,$campaign_id){
	$to_fetch="*";
	$where_cond = "agent='$agent_id' and campaign_id='$campaign_id' and channel !=''";
	$tablename="dialer_conference";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$conference=$row['conference'];
				$channel=$row['channel'];
				  }
				  $result_Arr['count']=$count;
				  $result_Arr['conference']=$conference;
				  $result_Arr['channel']=$channel;
				}else{
					$result_Arr['count']=$count;
					$result_Arr['conference']="0";
					$result_Arr['channel']='';
				}
return $result_Arr;
}


function getUserActiveDialerCampaign($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "agent_id='$user_id'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$campaign_id=$row['campaign_id'];
				$campaignInfo = getDialerCampaignFromId($campaign_id);
				$campaign_name = $campaignInfo['data'][0]['name'];
				$campaign_status = $campaignInfo['data'][0]['status'];
				$result_Arr['campaign_id'][] = $campaign_id;
				$result_Arr['campaign_name'][] = $campaign_name;
				$result_Arr['campaign_status'][] = $campaign_status;
				
			} 
			$result_Arr['count']=$count;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['campaign_name'] = '';
			$result_Arr['campaign_id'] = '';
			$result_Arr['campaign_status'] = '';
				
	  }
	  return $result_Arr;
}


function getUserStatusInCampaign($user_id,$campaign_id,$status)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "agent_id='$user_id' and campaign_id='$campaign_id' and status='$status'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$campaign_id=$row['campaign_id'];
				$campaignInfo = getDialerCampaignFromId($campaign_id);
				$campaign_name = $campaignInfo['data'][0]['name'];
				$campaign_status = $campaignInfo['data'][0]['status'];
				$result_Arr['campaign_id'][] = $campaign_id;
				$result_Arr['campaign_name'][] = $campaign_name;
				$result_Arr['campaign_status'][] = $campaign_status;
				
			} 
			$result_Arr['count']=$count;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['campaign_name'] = '';
			$result_Arr['campaign_id'] = '';
			$result_Arr['campaign_status'] = '';
				
	  }
	  return $result_Arr;
}




function findLoggedAgentInAnyCampaign($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "agent_id='$user_id' and status='1'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$campaign_id = $row['campaign_id'];
				$login_status = $row['login_status'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['campaign_id']=$campaign_id;
			$result_Arr['login_status']=$login_status;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['campaign_id']='';
			$result_Arr['login_status']='';
	  }
	  return $result_Arr;
}



function checkDialerTimeWithCurrentTime($campaign_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$campaign_id'";
	$tablename="dialer";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
		   while($row=mysqli_fetch_array($result)){
				$start_time = $row['start_time'];
				$status = $row['status'];
				$end_time = $row['end_time'];
			}
	$current_time = date('H:i:s',time()); 
	$current_time = DateTime::createFromFormat('H:i:s', $current_time);
	$start_time = DateTime::createFromFormat('H:i:s', $start_time);
	$end_time = DateTime::createFromFormat('H:i:s', $end_time);
	
	if ($current_time > $start_time && $current_time < $end_time)
	{
		//valid time between server time and campaigns start and end time
		$result_Arr['status']="1";
		$result_Arr['current_server_time']=$current_time;
		$result_Arr['campaign_start_time']=$start_time;
		$result_Arr['campaign_end_time']=$end_time;
		$result_Arr['camp_status']=$status;
	}else{
		$result_Arr['status']="0";
		$result_Arr['current_server_time']=$current_time;
		$result_Arr['campaign_start_time']=$start_time;
		$result_Arr['campaign_end_time']=$end_time;
		$result_Arr['camp_status']=$status;
	}
	  }else{
		  $result_Arr['status']="0";
	  }
	  return $result_Arr;
}

function checkAdminAllowDialerLogin($campaign_id,$user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "agent_id='$user_id' and campaign_id='$campaign_id'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$login_status = $row['login_status'];
				$status = $row['status'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['login_status']=$login_status;
			$result_Arr['status']=$status;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['login_status']='';
			$result_Arr['status']='';
	  }
	  return $result_Arr;
}



function getContactList()
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id is not null";
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function removeLiveQueueGarbage($ip){
	
	trunk_table("queue_live_call");
}

function trucateManualCalls(){
	trunk_table("call_information");
}

function trucateQueueCalls(){
	trunk_table("all_time_queue_summary");
}



function getTicketingCounts($type,$id)
{
	$result_Arr=array();
	$to_fetch="*";
	if($type == "customer"){
		$where_cond = "contact_id='$id'";
	}else{
		$where_cond = "assigned_to='$id'";
	}
	$tablename="ticket";
	
	$open=0;
	$in_progress=0;
	$wait_for_resp=0;
	$closed=0;
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				if($row['status'] == "Open"){
					$open = $open + 1;
				}
				if($row['status'] == "Wait For Response"){
					$wait_for_resp = $wait_for_resp + 1;
				}
				if($row['status'] == "In Progress"){
					$in_progress = $in_progress + 1;
				}
				if($row['status'] == "Closed"){
					$closed = $closed + 1;
				}
			} 
			$result_Arr['count']=$count;
			$result_Arr['open']=$open;
			$result_Arr['wait_for_resp']=$wait_for_resp;
			$result_Arr['in_progress']=$in_progress;
			$result_Arr['closed']=$closed;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['open']=$open;
			$result_Arr['wait_for_resp']=$wait_for_resp;
			$result_Arr['in_progress']=$in_progress;
			$result_Arr['closed']=$closed;
			$result_Arr['data']='';
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}

function getTodayMeeting($agent_id)
{
	$result_Arr=array();
	$to_fetch="*";
	if($agent_id == "admin"){
		$where_cond = "assigned_user is not null and DATE(start_date) = CURDATE() order by start_time ASC";
	}else{
		$where_cond = "assigned_user='$agent_id' and DATE(start_date) = CURDATE() order by start_time ASC";
	}
	$tablename="contact_schedule";
	
	$held_meeting=0;
	$notheld_meeting=0;
	$planned_meeting=0;
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				if($row['status'] == "Held"){
					$held_meeting = $held_meeting + 1;
				}
				if($row['status'] == "Not Held"){
					$notheld_meeting = $notheld_meeting + 1;
				}
				if($row['status'] == "Planned"){
					$planned_meeting = $planned_meeting + 1;
				}
			} 
			$result_Arr['count']=$count;
			$result_Arr['held_meeting']=$held_meeting;
			$result_Arr['notheld_meeting']=$notheld_meeting;
			$result_Arr['planned_meeting']=$planned_meeting;
			$result_Arr['data']=$data;
	  }else{
		  
			$result_Arr['count']="0";
			$result_Arr['data']='';
			$result_Arr['held_meeting']=$held_meeting;
			$result_Arr['notheld_meeting']=$notheld_meeting;
			$result_Arr['planned_meeting']=$planned_meeting;
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}

function getMeeting($fromDate,$toDate,$contact_id,$status,$agent_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$dateBetween="";
	
	if($fromDate)
			{
				$from=date_create($fromDate);
				$from= date_format($from,"Y/m/d H:i:s");
				
				$to=date_create($toDate);
				$to= date_format($to,"Y/m/d H:i:s");
				$dateBetween = " and (start_date BETWEEN '$from' AND '$to')";
			}else{
				$dateBetween = " and DATE(start_date) = CURDATE()";
			}
			
			if($contact_id)
			{
				$campaignSql = " and contact_id='$contact_id'";
			}else{
				$campaignSql = "";
			}
			
			if($status)
			{
				$agentDispoSql = "status='$status'";
			}else{
				$agentDispoSql = "status IS NOT NULL";
			}
			
			if($agent_id)
			{
				$agentSql = " and assigned_user='$agent_id'";
			}else{
				$agentSql = "";
			}
	
	
	$where_cond = "$agentDispoSql$agentSql$campaignSql$dateBetween order by created_date DESC";
	$tablename="contact_schedule";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}


function searchTicket($fromDate,$toDate,$contact_id,$status,$agent_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$dateBetween="";
	
	if($fromDate)
			{
				$from=date_create($fromDate);
				$from= date_format($from,"Y/m/d H:i:s");
				
				$to=date_create($toDate);
				$to= date_format($to,"Y/m/d H:i:s");
				$dateBetween = " and (date_created BETWEEN '$from' AND '$to')";
			}else{
				//$dateBetween = " and DATE(date_created) = CURDATE()";
			}
			
			if($contact_id)
			{
				$contactSql = " and contact_id='$contact_id'";
			}else{
				$contactSql = "";
			}
			
			if($status)
			{
				$ticketStatusSql = "status='$status'";
			}else{
				$ticketStatusSql = "status IS NOT NULL";
			}
			
			if($agent_id)
			{
				$agentSql = " and assigned_to='$agent_id'";
			}else{
				$agentSql = "";
			}
	
	
	$where_cond = "$ticketStatusSql$agentSql$contactSql$dateBetween order by date_created DESC";
	$tablename="ticket";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}

function getMeetingInfo($meeting_id)
{
	$result_Arr=array();
	$to_fetch="*";
	
	$where_cond = "id ='$meeting_id'";
	
	$tablename="contact_schedule";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getTicket($id)
{
	$result_Arr=array();
	$to_fetch="*";
	
	$where_cond = "ticket_no ='$id'";
	
	$tablename="ticket";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function sheduleMeeting($created_by,$subject,$start_date,$start_time,$end_date,$end_time,$description,$status,$contact_id,$assigned_user,$contact_name)
{
	$start_date=date("Y-m-d",strtotime($start_date));
	$start_time=date("H:i:s",strtotime($start_time));
	
	
	$end_date=date("Y-m-d",strtotime($end_date));
	$end_time=date("H:i:s",strtotime($end_time));
	
	$result_Arr=array();
		$tablename='contact_schedule';
		$fields='`contact_id`, `subject`, `description`, `assigned_user`, `created_by`, `start_date`, `start_time`, `end_date`, `end_time`, `status`, `created_date`, `modified_user`, `contact_name`';
		$value="'$contact_id','$subject','$description','$assigned_user',$created_by,'$start_date','$start_time','$end_date','$end_time','$status',NOW(),'$created_by','$contact_name'";
		$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$where_cond="contact_id='$contact_id' and subject='$subject' and created_by=$created_by";
			$results=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($results)){
				$id=$row['id'];
			} 
			 $result_Arr['id']=$id;
			 $result_Arr['status']="1";
		  }
	return $result_Arr;
}


function createTicket($created_by,$title,$solution,$priority,$description,$status,$contact_id,$user_id,$type)
{
	$result_Arr=array();
		$tablename='ticket';
		$fields='`title`, `contact_id`, `status`, `assigned_to`, `priority`, `description`, `solution`, `comment_id`, `date_created`, `date_modified_agent`, `date_modified_customer`, `secret`, `type`';
		$value="'$title','$contact_id','$status','$user_id','$priority','$description','$solution','',NOW(),NOW(),NOW(),'','$type'";
		$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$where_cond="contact_id='$contact_id' and title='$title'";
			$results=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($results)){
				$id=$row['ticket_no'];
			} 
			 $result_Arr['ticket_id']=$id;
			 $result_Arr['status']="1";
		  }
	return $result_Arr;
}

function postcomment($ticket_id,$message,$type,$type_id){
		$result_Arr=array();
		$tablename='ticket_comments';
		$fields='`ticket_id`, `comment`, `type`, `type_id`, `date_time`';
		$value="'$ticket_id','$message','$type','$type_id',NOW()";
		$result=insert_record($fields,$value,$tablename);
		  if($result){
			$result_Arr = getTicketComments($ticket_id);
		  }
	return $result_Arr;
}
function getTicketComments($ticket_id){
	$to_fetch="*";
	$where_cond = "ticket_id = $ticket_id order by date_time DESC";
	$tablename="ticket_comments";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getLatestTicketComment($ticket_id,$type){
	$to_fetch="*";
	$where_cond = "ticket_id = '$ticket_id' and type='$type' order by date_time DESC limit 1";
	$tablename="ticket_comments";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function updateMeeting($meeting_id,$subject,$start_date,$start_time,$end_date,$end_time,$description,$status,$contact_id,$user_id,$assigned_user,$contact_name)
{
	
	$start_date=date("Y-m-d",strtotime($start_date));
	$start_time=date("H:i:s",strtotime($start_time));


	$end_date=date("Y-m-d",strtotime($end_date));
	$end_time=date("H:i:s",strtotime($end_time));
	
	$result_Arr=array();	
	$tablename="contact_schedule";
	$condition="id='$meeting_id'";
	$fields="subject='$subject',start_date='$start_date',start_time='$start_time',end_date='$end_date',end_time='$end_time',description='$description',status='$status',contact_id='$contact_id',modified_user='$user_id',assigned_user='$assigned_user',contact_name='$contact_name'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['id']=$meeting_id;
	$result_Arr['status']="1";
	return $result_Arr;
}


function updateTicket($current_user,$ticket_id,$title,$solution,$priority,$description,$status,$contact_id,$user_id,$type)
{
	$result_Arr=array();	
	$tablename="ticket";
	$condition="ticket_no='$ticket_id'";
	if($type == "admin"){
			$fields="title='$title',contact_id='$contact_id',status='$status',assigned_to='$user_id',priority='$priority',description='$description',solution='$solution',date_modified_agent=NOW()";
	}else{
		$fields="title='$title',contact_id='$contact_id',status='$status',assigned_to='$user_id',priority='$priority',description='$description',solution='$solution',date_modified_customer=NOW()";
	}
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['id']=$ticket_id;
	$result_Arr['status']="1";
	return $result_Arr;
}




function getAgentAllContacts($agent_id)
{
	$result_Arr=array();
	$to_fetch="*";
	if($agent_id == "admin"){
		$where_cond = "1";
	}else{
		$where_cond = "assigned_user = '$agent_id'";
	}
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getSMSCampaignFromId($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$id'";
	$tablename="sms_campaign";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getVBCallLimit()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "name='TEVB'";
	$tablename="modules";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$value=$row['value'];
			} 
			
	  }else{
			$value=0;
	  }
	  return $value;
}


function getVoiceCampaignFromId($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$id'";
	$tablename="voice_broadcasting";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getDialerFromCampaignId($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$id'";
	$tablename="dialer";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getDialerCampaignFromId($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$id'";
	$tablename="dialer";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getContact($contact_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$contact_id'";
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getContactFromNumber($phone)
{
	$phoneLength = strlen($phone);
	if($phoneLength < 7){
		$phoneToSearch = $phone;
	}else{
		$phoneToSearch = substr($phone, -7);
	}
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "phone like '%$phoneToSearch' or phone2 like '%$phoneToSearch' limit 1";
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}



function searchCasesContact($contact_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "contact_id='$contact_id' order by FIELD(status, 'Open') DESC limit 5";
	$tablename="ticket";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}


function getContactFromEmail($email)
{
	$email = trim($email);
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "email = '$email'";
	$tablename="contacts";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}



function removeContact($id)
{
	$result_Arr=array();
	$tablename="contacts";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;	
}

function deleteMeeting($id)
{
	$result_Arr=array();
	$tablename="contact_schedule";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;	
}

function deleteTicket($id)
{
	$result_Arr=array();
	$tablename="ticket";
	$condition="ticket_no='$id'";
	$result=delete_record($condition,$tablename);
	return $result;	
}

function updateContact($contact_id,$first_name,$last_name,$email,$phone,$address,$phone2,$status,$assigned_user,$note,$prefix,$city,$postal_code,$country)
{
	$result_Arr=array();	
	$tablename="contacts";
	$condition="id='$contact_id'";
	$fields="first_name='$first_name',last_name='$last_name',email='$email',phone='$phone',address='$address',date_modified=NOW(),phone2='$phone2',status='$status',assigned_user='$assigned_user',note='$note',prefix='$prefix',city='$city',postal_code='$postal_code',country='$country' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}
function updateCustomerProfile($contact_id,$company_name,$phone,$password)
{
	$result_Arr=array();	
	$tablename="contacts";
	$condition="id='$contact_id'";
	if(!$password){
		$fields="company='$company_name',phone='$phone'";
	}else{
		$fields="secret='$password'";
	}
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function setContactPassword($contact_id,$password)
{
	$result_Arr=array();	
	$tablename="contacts";
	$password = generateRandomPassword('5');
	$condition="id='$contact_id'";
	$fields="secret='$password'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function generateRandomPassword($length){
  $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $base = strlen($charset);
  $result = '';

  $now = explode(' ', microtime())[1];
  while ($now >= $base){
    $i = $now % $base;
    $result = $charset[$i] . $result;
    $now /= $base;
  }
  return substr($result, -5);
}


function setZohoToken($crm_config_id,$grant_token,$refresh_token)
{
	$result_Arr=array();	
	$tablename="crm_configuration";
	$condition="id='$crm_config_id'";
	$fields="grant_token='$grant_token',refresh_token='$refresh_token'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function updateContactByAgent($contact_id,$first_name,$last_name,$email,$phone,$address,$phone2,$status,$notes,$agent_id,$city,$postal_code,$country)
{
	$result_Arr=array();	
	$tablename="contacts";
	$condition="id='$contact_id'";
	$fields="first_name='$first_name',last_name='$last_name',email='$email',phone='$phone',address='$address',date_modified=NOW(),phone2='$phone2',status='$status',note='$notes',assigned_user='$agent_id',city='$city',postal_code='$postal_code',country='$country' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}


function updateContactWithCRMInfo($contact_id,$crm_id,$ModuleName)
{
	$result_Arr=array();	
	$tablename="contacts";
	$condition="id='$contact_id'";
	$fields="crm_id='$crm_id',modulename='$ModuleName'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function secondsToWords($seconds)
{
    $ret = "";

    /*** get the days ***/
    $days = intval(intval($seconds) / (3600*24));
    if($days> 0)
    {
        $ret .= "$days days ";
    }

    /*** get the hours ***/
    $hours = (intval($seconds) / 3600) % 24;
    if($hours > 0)
    {
        $ret .= "$hours hours ";
    }

    /*** get the minutes ***/
    $minutes = (intval($seconds) / 60) % 60;
    if($minutes > 0)
    {
        $ret .= "$minutes minutes ";
    }

    /*** get the seconds ***/
    $seconds = intval($seconds) % 60;
    if ($seconds >= 0) {
        $ret .= "$seconds seconds";
    }

    return $ret;
}




function getEmailConfiguration($user_id)
{
	$to_fetch='*';
	$tablename='email_configuration';
	$where_cond="user_id=$user_id";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		$result_Arr['count']=$count;
		$result_Arr['data']=$datas;
	}else{
		$result_Arr['count']="0";
		$result_Arr['data']='';
	}
	return $result_Arr;
}


function getAllContactStatus()
{
	$to_fetch='*';
	$tablename='contact_status';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getAllMeetingStatus(){
	$to_fetch='*';
	$tablename='contact_meeting_status';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getTicketStatus(){
	$to_fetch='*';
	$tablename='ticket_status';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getTotalUsersInAsterCTI()
{
	$to_fetch='sum(rows) as total_users';
	$tablename='(select count(*) as rows from user union all select count(*) as rows from admin) as u';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
					 while($row=mysqli_fetch_array($result)){
						$total_users=$row['total_users'];
					} 
					$result_Arr['total_users']=$total_users;
			  }else{
				  $result_Arr['total_users']=0;
			  }
			  return $result_Arr;
}

function getPortalURL()
{
	
	$to_fetch='*';
	$tablename='license';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		
		$result_Arr['status']="1";
		$result_Arr['url']=$datas['url'];
		$result_Arr['mac']=$datas['mac'];
		$result_Arr['user']=$datas['user'];
		$result_Arr['no_of_crm']=$datas['no_of_crm'];
		$result_Arr['no_of_asterisk']=$datas['no_of_asterisk'];
	}else{
		$result_Arr['status']="0";
		$result_Arr['url']='';
		$result_Arr['mac']='';
		$result_Arr['user']='';
		$result_Arr['no_of_crm']='';
		$result_Arr['no_of_asterisk']='';
	}
	return $result_Arr;
}

function getSMSConfiguration()
{
	
	$to_fetch='*';
	$tablename='sms_configuration';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		$result_Arr['status']="1";
		$result_Arr['data']=$datas;
	}else{
		$result_Arr['status']="0";
		$result_Arr['data']='';
	}
	return $result_Arr;
}


function createEmailConfiguration($user_id,$email,$password,$host,$port,$secure)
{
	 
	$result_Arr=array();
	$to_fetch='*';
	$tablename='email_configuration';
	$where_cond="user_id=$user_id";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);

	if($count>0){
		$result_Arr['status']="present";
	}else{
		$fields='`email`, `password`, `host`, `port`, `security_type`, `date_time`, `user_id`';
		$value="'$email','$password','$host','$port','$secure',NOW(),'$user_id'";
		$result=insert_record($fields,$value,$tablename);
		$result_Arr['status']="created";
	} 
	return $result_Arr;
}


function resetEmailConfiguration($user_id)
{
	$result_Arr=array();
	$tablename="email_configuration";
	$condition="user_id='$user_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function updateEmailConfiguration($user_id,$email,$password,$host,$port,$secure)
{
	$result_Arr=array();
	$to_fetch='*';
	$tablename='email_configuration';
	$condition="user_id='$user_id'";
	$fields="email='$email',password='$password',host='$host',port='$port',security_type='$secure'";
	$result=update_record($fields,$condition,$tablename);
	return "updated";
}


function updateEmailTestResult($user_id,$result)
{
	$result_Arr=array();
	$to_fetch='*';
	$tablename='email_configuration';
	$condition="user_id='$user_id'";
	$fields="result='$result'";
	$result=update_record($fields,$condition,$tablename);
	return $result_Arr;
}


function shortText($string)
{
	$string = strip_tags($string);
	if (strlen($string) > 50) {

		// truncate string
		$stringCut = substr($string, 0, 50);
		$endPoint = strrpos($stringCut, ' ');

		//if the string doesn't contain any space then it will cut without word basis.
		$string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
		$string .= ' ...';
	}
	return $string;
}

function createSMSConfiguration($username_label,$username_value,$password_label,$password_value,$param1_label,$param1_value,$param2_label,$param2_value,$url,$api_type,$sms_label,$twillio_sid,$twillio_token,$twillio_from,$to_send_label)
{
	 
	$result_Arr=array();
	$to_fetch='*';
	$tablename='sms_configuration';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);

	if($count>0){
		$result_Arr['status']="present";
	}else{
		
		//$password = techEncrypt($password,"techguru");
		$tablename='sms_configuration';
		$fields='`username_label`, `username_value`, `password_label`, `password_value`, `param1_label`, `param1_value`, `param2_label`, `param2_value`, `type`, `url`, `sms_label`, `twillio_from`, `twillio_sid`, `twillio_token`, `to_send_label`';
		$value="'$username_label','$username_value','$password_label','$password_value','$param1_label','$param1_value','$param2_label','$param2_value','$api_type','$url','$sms_label','$twillio_from','$twillio_sid','$twillio_token','$to_send_label'";
		$result=insert_record($fields,$value,$tablename);
		$result_Arr['status']="created";
	} 
	return $result_Arr;
}


function updateSMSConfiguration($username_label,$username_value,$password_label,$password_value,$param1_label,$param1_value,$param2_label,$param2_value,$url,$api_type,$sms_label,$twillio_sid,$twillio_token,$twillio_from,$to_send_label)
{
	 
	$result_Arr=array();
	$to_fetch='*';
	$tablename='sms_configuration';
	$condition="1";
	$fields="username_label='$username_label',username_value='$username_value',password_label='$password_label',password_value='$password_value',param1_label='$param1_label',param1_value='$param1_value',param2_label='$param2_label',param2_value='$param2_value',url='$url',type='$api_type',sms_label='$sms_label',twillio_sid='$twillio_sid',twillio_token='$twillio_token',twillio_from='$twillio_from',to_send_label='$to_send_label'";
	$result=update_record($fields,$condition,$tablename);
	return $result;
}

function createCampaignSMSLog($contact_name,$contact_id,$campaign_id,$response_id,$contact_phone,$smsContent,$user_id,$status,$direction)
{
	 
	$result_Arr=array();
	$sms_log_id="";
		$tablename='sms_campaign_log';
		
		$to_fetch="*";
			$where_cond = "contact_name='$contact_name' and contact_id='$contact_id' and campaign_id='$campaign_id' and number='$contact_phone' and user_id='$user_id'";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$count=get_num_rows($result);
			  if($count>0){
				$condition="contact_name='$contact_name' and contact_id='$contact_id' and campaign_id='$campaign_id' and number='$contact_phone' and user_id='$user_id'";
				$fields="status='$response_id', sms_response='$response_id'";
				$result=update_record($fields,$condition,$tablename);
				$result_max=update_record_max($fields,$condition,$tablename);
				$result=1;
			  }else
			  {
				$fields='`contact_name`, `contact_id`, `campaign_id`, `sms_response`, `number`, `sms`, `user_id`, `status`, `direction`, `date_time`';
				$value="'$contact_name','$contact_id','$campaign_id','$response_id','$contact_phone','$smsContent','$user_id','$status','$direction',NOW()";
				$result=insert_record($fields,$value,$tablename);
			  }
		if($result){
			$to_fetch="*";
			$where_cond = "contact_name='$contact_name' and contact_id='$contact_id' and campaign_id='$campaign_id' and number='$contact_phone' and user_id='$user_id'";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$count=get_num_rows($result);
			  if($count>0){
					 while($row=mysqli_fetch_array($result)){
						$sms_log_id=$row['id'];
					} 
					$result_Arr['sms_log_id']=$sms_log_id;
			  }
			  $result_Arr['where_cond']=$result_max;
		}
	return $result_Arr;
}

function createSMSLog($number,$sms,$user_id,$status,$output,$direction,$incoming_status,$contact_id)
{
	$result_Arr=array();
		$tablename='sms_log';
		$fields='`number`, `sms`, `user_id`, `status`, `date_time`, `sms_out_id`, `direction`, `incoming_status`, `crm_sms_id`, `contact_id`';
		$value="'$number','$sms','$user_id','$status',NOW(),'$output','$direction','$incoming_status','','$contact_id'";
		$result=insert_record($fields,$value,$tablename);
		$result_Arr['status']="success";
	return $result_Arr;
}

function createDialerDispoURLResult($call_id,$result,$CURL_URL,$user_id,$campaign_id,$agent_disposition){
	$result_Arr=array();
		$tablename='dialer_dispo_log';
		$fields='`call_id`, `url`, `result`, `agent_id`, `date`, `campaign_id`, `agent_disposition`';
		$value="'$call_id','$CURL_URL','$result','$user_id',NOW(),'$campaign_id','$agent_disposition'";
		$result=insert_record($fields,$value,$tablename);
		$result_Arr['status']="success";
	return $result_Arr;
}


function updateSMSLogAfterSearch($crm_task_id,$sms_id)
{
	
	$result_Arr=array();
	$tablename="sms_log";
	$condition="id='$sms_id'";
	$fields="crm_sms_id='$crm_task_id'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = "1"; 
	return $result_Arr;
}


	function CheckLogin($value_arr){
	  $result_Arr=array();	
	  $value_arr=explode('*',$value_arr);
	  $email=$value_arr[0];
	  $password=md5($value_arr[1]);
	  $to_fetch='*';
	  $tablename='user';
	  $where_cond="email='$email' and password='$password'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	  $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			$status = $datas['status'];
			if($status == "1"){
			$user_id = $datas['user_id'];
			$condition="email='$email' and password='$password'";
			$fields="loggedin='1',last_login_date=NOW()";
			$result=update_record($fields,$condition,$tablename);
			createPortalLoginHistory($user_id,'1');
			}else{
			$result_Arr['status']="2";
			$result_Arr['data']='';
			}
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  } 
	  return $result_Arr;
	}
	function logout($user_id)
	{
		
		$result_Arr=array();
		 $tablename='user';
		$condition="user_id='$user_id'";
		$fields="loggedin='0',last_logout_date=NOW()";
		$result=update_record($fields,$condition,$tablename);
		createPortalLoginHistory($user_id,'0');
	}
	
	function createPortalLoginHistory($user_id,$login)
	{
		
		$result_Arr=array();	
		
		$tablename='portal_login_history';
		$fields='`user_id`, `login`, `date_time`';
		$value="'$user_id','$login',NOW()";
		$result=insert_record($fields,$value,$tablename);

		return $result_Arr;
	}

	function updateLoginSession($user_id)
	{
		
		$result_Arr=array();
		 $tablename='user';
		$condition="user_id='$user_id'";
		$fields="loggedin='1',last_login_date=NOW()";
		$result=update_record($fields,$condition,$tablename);
	}
	
	
	
	
	
	
	function createAsteriskConfiguration($value_arr)
	{
	
	  $result_Arr=array();	
	  $value_arr=explode('***',$value_arr);
	$tablename='asterisk_ip';
	//$dataToSend = $name."***".$asterisk_ip."***".$ami_username."***".$ami_password."***".$port;
	
			$fields='`name`, `ip`, `ami_username`,`ami_password` ,`port`,`recording_url`, `date`';
			$value="'$value_arr[0]','$value_arr[1]','$value_arr[2]','$value_arr[3]','$value_arr[4]','$value_arr[5]',NOW()";
			$result=insert_record($fields,$value,$tablename);
			 return $result;
	}
	
	function updateAsteriskConfiguration($value_arr)
	{
		
		$result_Arr=array();	
		$value_arr=explode('***',$value_arr);
		$tablename='asterisk_ip';
		//$dataToSend = $name."***".$asterisk_ip."***".$ami_username."***".$ami_password."***".$port;

		$condition="id='$value_arr[0]'";
		$fields="name='$value_arr[1]',ip='$value_arr[2]',ami_username='$value_arr[3]',ami_password='$value_arr[4]',port='$value_arr[5]',recording_url='$value_arr[6]' ";
		$result=update_record($fields,$condition,$tablename);
		$result_Arr['status'] = $result;
		return $result_Arr;
	}
	
	
	function setSession($value_arr)
	{
		$result_Arr=array();
		$sessionID = $value_arr['sessionID'];
		$serverUrl = $value_arr['serverUrl'];
		$user = $value_arr['user'];
		if($user > "15"){
			$tablename='user';
		}else{
			$tablename='admin';
		}
		$condition="user_id='$user'";
		$fields="api_session_id='$sessionID',api_session_url='$serverUrl'";
		$result_max=update_record_max($fields,$condition,$tablename);
		$result=update_record($fields,$condition,$tablename);
		$result_Arr['status'] = $result;
		$result_Arr['result_max'] = $result_max;
		return $result_Arr;
	}
	
	function getSession($user_id){
	  
	  $result_Arr=array();	
	 
	  $to_fetch='*';
	  $tablename='user';
	  $where_cond="user_id='$user_id'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['sessionUrl']=$datas['api_session_url'];
			$result_Arr['sessionID']=$datas['api_session_id'];
			
	  }else{
			$result_Arr['sessionUrl']="";
			$result_Arr['sessionID']='';
	  } 
	  return $result_Arr;
	}
	
	
	
	function updateCRMConfiguration($value_arr)
	{
		
		$result_Arr=array();	
		$value_arr=explode('***',$value_arr);
		$tablename='crm_configuration';
		//$id."***".$crm_name."***".$crm_url."***".$secret."***".$admin_username."***".$admin_password."***".$for_all;

		$condition="id='$value_arr[0]'";
		$fields="crm_name='$value_arr[1]',crm_url='$value_arr[2]',secret='$value_arr[3]',admin_username='$value_arr[4]',admin_password='$value_arr[5]',for_all='$value_arr[6]',ms_client_id='$value_arr[7]' ";
		$result=update_record($fields,$condition,$tablename);
		$result_Arr['status'] = $result;
		return $result_Arr;
	}
	
	
	function saveNote($value_arr)
	{
		
		$result_Arr=array();	
		$value_arr=explode('*',$value_arr);
		$tablename='call_information';
		//$dataToSend = $name."***".$asterisk_ip."***".$ami_username."***".$ami_password."***".$port;

		$condition="id='$value_arr[0]'";
		$fields="note='$value_arr[1]'";
		$result=update_record($fields,$condition,$tablename);
		$result_Arr['status'] = $result;
		return '1';
	}
	

	function CheckAdminLogin($value_arr){
	  
	  $result_Arr=array();	
	  $value_arr=explode('*',$value_arr);
	  $email=$value_arr[0];
	  $password=md5($value_arr[1]);
	  $to_fetch='*';
	  $tablename='admin';
	  $where_cond="email='$email' and password='$password'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  } 
	  return $result_Arr;
	}
	
	function checkCustomerLogin($email,$password){
	  $to_fetch='*';
	  $tablename='contacts';
	  $where_cond="email='$email' and secret='$password'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	  $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['count']=$count;
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['data']='';
	  } 
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
	}
	
	
	function checkEmailPresentInAdmin($email){
	  
	  $result_Arr=array();	
	 
	  $to_fetch='*';
	  $tablename='admin';
	  $where_cond="email='$email'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  } 
	  return $result_Arr;
	}
	
	

	function checkAdminSearchOnOrOff($crm_url){
	  
	  $result_Arr=array();	
	 
	$to_fetch="*";
	$where_cond = "crm_url='$crm_url'";
	$tablename="crm_configuration";
	$result=select_record($to_fetch,$where_cond,$tablename);
	  while($row=mysqli_fetch_array($result)){
				$for_all=$row['for_all'];
				$admin_username=$row['admin_username'];
				$admin_password=$row['admin_password'];
				$secret=$row['secret'];
				$data[]=$row;
			}
	if(!$for_all)
	{
		$result_Arr['status']= "not_found";
		$result_Arr['admin_username']= '';
		$result_Arr['admin_password']= '';
		$result_Arr['secret']= '';
		$result_Arr['data']= '';
	}
	else
	{
		if($for_all == "yes")
		{
			$result_Arr['status']= "admin_search";
			$result_Arr['admin_username']= $admin_username;
			$result_Arr['admin_password']= $admin_password;
			$result_Arr['secret']= $secret;
			$result_Arr['data']= $data;
		}
		else
		{
			$result_Arr['status']= "user_search";
			$result_Arr['admin_username']= '';
			$result_Arr['admin_password']= '';
			$result_Arr['secret']= '';
			$result_Arr['data']= '';
		}
	}
	  return $result_Arr;
	}
	
function getProfile($user_id)
{
 $to_fetch='*';
	  $tablename='user';
	  $where_cond="user_id='$user_id' and deleted='0'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  } 
	  return $result_Arr;
}


function getCRMNameFromURL($url)
{
	$url = trim($url);
 
 $to_fetch='*';
	  $tablename='crm_configuration';
	  $where_cond="crm_url='$url'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	    $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  }  
	//  $result_Arr['status']=$result;
	  return $result_Arr;
}

function getRecentLogs($user_id)
{
	$to_fetch='*';
	  $tablename='call_information';
	  $where_cond="user_id='$user_id' and DATE(date_start) = CURDATE() order by date_start DESC limit 5";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	    $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count; 
			
			$result_Arr['data']=$data; 
	  }else{
			$result_Arr['count']=0; 
			$result_Arr['data']='';
	  }
	//  $result_Arr['status']=$result;
	  return $result_Arr;
}	

function getRecentDialerLogs($user_id)
{
	$to_fetch='*';
	  $tablename='dialer_log';
	  $where_cond="user_id='$user_id' and DATE(cdr_start_time) = CURDATE() order by cdr_start_time DESC limit 5";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	    $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count; 
			$result_Arr['data']=$data; 
	  }else{
			$result_Arr['count']=0; 
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAgentDialerLog($user_id)
{
	$to_fetch='*';
	  $tablename='dialer_log';
	  $where_cond="user_id='$user_id' and DATE(cdr_start_time) = CURDATE() order by cdr_start_time DESC";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	    $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count; 
			$result_Arr['data']=$data; 
	  }else{
			$result_Arr['count']=0; 
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getAdminProfile($user_id)
{
 
 $to_fetch='*';
	  $tablename='admin';
	  $where_cond="user_id='$user_id'";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  } 
	  return $result_Arr;
}

function checkAnyAdminPresent()
{
 
 $to_fetch='*';
 $user_id='';
	  $tablename='admin';
	  $where_cond="1";
	 $result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$user_id=$row['user_id'];
			} 
	  }else{
	  }
	  return $user_id;
}

function registerAdmin($name,$email,$company_name,$designation,$extension,$password,$contact,$asterisk_ip,$channel,$context,$prefix,$call_log_in_crm)
{
	
	$original_password=$password;
	$password=md5($password);
	$tablename='admin';
	$fields='`name`, `company_name`, `password`,`original_password` ,`email`, `designation`, `extension`, `date`, `contact`, `asterisk_ip`, `channel`, `context`, `prefix`, `call_log_in_crm`, `profile_image`';
	$value="'$name','$company_name','$password','$original_password','$email','$designation','$extension',NOW(),'$contact','$asterisk_ip','$channel','$context','$prefix','$call_log_in_crm','../image/avtar.png'";
	$result=insert_record($fields,$value,$tablename);
	if($result){
		$to_fetch="*";
		$where_cond="email='$email' and date=NOW() and designation='$designation'";
		 $result=select_record($to_fetch,$where_cond,$tablename);
		 $count=get_num_rows($result);
	  if($count>0){
			$datas=get_sgn_rows($result);
			$result_Arr['status']="1";
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  }
	   
	}
	return $result_Arr;
}
function getCompanyName()
{
 
 $to_fetch='*';
	  $tablename='admin';
	  $where_cond="user_id='1' ";
	 $result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$name=$row['company_name'];
			} 
	  }else{
	  }
	  return $name;
}

function getAgentDetails()
{
	
	$to_fetch='*';
	$tablename='user';
	$where_cond="loggedin = '1' and last_login_date > date_sub(now(), interval 5 second) ";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){

					$loggedIn['profile_image'][] = $row['profile_image'];
					$loggedIn['user_id'][] = $row['user_id'];
					$loggedIn['last_login_date'][] = $row['last_login_date'];
					$loggedIn['name'][] = $row['name'];
					$loggedIn['extension'][] = $row['extension'];
				
			}
	  }else{
			$result_Arr['loggedIn']=''; 
			$result_Arr['loggedOut']='';
	  }
	$where_cond="last_login_date < date_sub(now(), interval 5 second)";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
			
					$loggedOut['profile_image'][] = $row['profile_image'];
					$loggedOut['user_id'][] = $row['user_id'];
					$loggedOut['last_login_date'][] = $row['last_login_date'];
					$loggedOut['name'][] = $row['name'];
					$loggedOut['extension'][] = $row['extension'];
			}
			$result_Arr['loggedIn']=$loggedIn; 
			$result_Arr['loggedOut']=$loggedOut; 
	  }else{
			$result_Arr['loggedOut']=''; 
	  }
	  return $result_Arr;

}

function updateBasicProfile($value_arr)
{
//$user_id."*".$name."*".$email."*".$designation."*".$phone;
	
	$result_Arr=array();
	$value_arr=explode('*',$value_arr); 
	$tablename="user";
	$condition="user_id='$value_arr[0]'";
	$fields="name='$value_arr[1]',email='$value_arr[2]',designation='$value_arr[3]',contact='$value_arr[4]',popup_type='$value_arr[6]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}


function getExtensionQueueStatus($extension)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "extension='$extension' and DATE(date_time) = CURDATE()";
	$tablename="extension_queue_status";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function updateAgentStatusToPickCall($status,$reason,$user_id)
{
	$result_Arr=array(); 
	$tablename="user";
	$condition="user_id='$user_id'";
	$fields="readytopickcall='$status' ";
	$result=update_record($fields,$condition,$tablename);
	
	$tablename="user_pause_queue";
	$fields='`user_id`, `action`, `date_time`';
	$value="'$user_id','$status',NOW()";
	$result=insert_record($fields,$value,$tablename);
	
	$tablename="user_manage_break";
	$fields='`user_id`, `status`, `date_time`';
	$value="'$user_id','$status',NOW()";
	$result=insert_record($fields,$value,$tablename);
	
	if($status == "0"){
		//calculate and delete
		$to_fetch="*";
		$where_cond = "user_id='$user_id' limit 2";
		$tablename="user_manage_break";
		$result_new=select_record($to_fetch,$where_cond,$tablename);
		$result_new_count=get_num_rows($result_new);
		
			while($row=mysqli_fetch_array($result_new)){
				$date_time[]=$row['date_time'];
			}
			$diff_sec = strtotime($date_time[1]) - strtotime($date_time[0]);
			
			$tablename="user_break_calculator";
			$fields='`user_id`, `reason`,`duration`, `date_time`';
			$value="'$user_id','break','$diff_sec',NOW()";
			$result=insert_record($fields,$value,$tablename);
			
			$tablename="user_manage_break";
			$condition="user_id='$user_id'";
			$result=delete_record($condition,$tablename);
		
	}
	$result_Arr['status'] = $result; 
	return $result_Arr;
}



function updateProfile($value_arr)
{
//$user_id."***".$name."***".$email."***".$password."***".$phone."***".$extension."***".$designation."***".$asterisk."***".$context."***".$channel."***".$prefix;
	
	$result_Arr=array();
	$value_arr=explode('***',$value_arr); 
	$tablename="user";
	$original_pass = $value_arr[3];
	$value_arr[3] = md5($value_arr[3]);
	$condition="user_id='$value_arr[0]'";
	$fields="name='$value_arr[1]',email='$value_arr[2]',password='$value_arr[3]',original_password='$original_pass',contact='$value_arr[4]',extension='$value_arr[5]',designation='$value_arr[6]',asterisk_ip='$value_arr[7]',context='$value_arr[8]',channel='$value_arr[9]',prefix='$value_arr[10]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function updateProfilePicture($id,$path,$type)
{
	
	$tablename="";
	if($type == "user")
	{
		$tablename="user";
	}else{
		$tablename="admin";
	}
	$condition="user_id='$id'";
	$fields="profile_image='$path'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result;
}


function updateAdminBasicProfile($value_arr)
{
//$user_id."*".$name."*".$email."*".$designation."*".$phone;
	
	$result_Arr=array();
	$value_arr=explode('*',$value_arr); 
	$tablename="admin";
	$condition="user_id='$value_arr[0]'";
	$fields="name='$value_arr[1]',email='$value_arr[2]',designation='$value_arr[3]',contact='$value_arr[4]',popup_type='$value_arr[5]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}
function changePassword($dataToRecieve)
{
	$user_id = $dataToRecieve[0];
	$new_password = $dataToRecieve[1];
	$new_password_encrypt = md5($new_password);
	$tablename="user";
		$tablename="user";
		$condition="user_id='$user_id'";
		$fields="password='$new_password_encrypt',original_password='$new_password'";
		$result=update_record($fields,$condition,$tablename);
		$result_Arr['status'] = "1"; 
	return $result_Arr;
}



function changeAdminPassword($dataToRecieve)
{

	$user_id = $dataToRecieve[0];
	$old_password = $dataToRecieve[1];
	$old_pass_encrypt=md5($old_password);
	$new_password = $dataToRecieve[2];
	$new_password_encrypt = md5($new_password) ;
	$user_id_db="";
	
	$to_fetch="*";
	$where_cond = "password='$old_pass_encrypt' and user_id='$user_id' ";
	$tablename="admin";
	$result=select_record($to_fetch,$where_cond,$tablename);
	  while($row=mysqli_fetch_array($result)){
				$user_id_db=$row['user_id'];
			}  
	
	
 	 if($user_id_db)
	{
		$tablename="admin";
		$condition="user_id='$user_id_db'";
		$fields="password='$new_password_encrypt',original_password='$new_password'";
		$result=update_record($fields,$condition,$tablename);
		$result_Arr['status'] = "1"; 
	}else
	{	
		$result_Arr['status'] = "-1"; 
	}
	
	
	return $result_Arr;
}

function updateTechextensionProfile($value_arr)
{
	
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	//$dataToSend = $_SESSION['tech_user_id']."*".$extension."*".$asterisk_ip."*".$channel."*".$context."*".$prefix;
	$tablename="user";
	$condition="user_id='$value_arr[0]'";
	$fields="extension='$value_arr[1]',asterisk_ip='$value_arr[2]',channel='$value_arr[3]',context='$value_arr[4]',prefix='$value_arr[5]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = "1"; 
	return $result_Arr;
}



function updateCrmProfile($value_arr)
{
	
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	//var dataToSend = "crm_url="+crm_url+"&crm_username="+crm_username+"&crm_password="+crm_password;
	$tablename="user";
	$condition="user_id='$value_arr[0]'";
	$fields="crm_url='$value_arr[1]',crm_username='$value_arr[2]',crm_password='$value_arr[3]',secret='$value_arr[4]',crm_type='$value_arr[5]',crm_user_id='$value_arr[6]',ms_client_id='$value_arr[7]',call_log_in_crm='$value_arr[8]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = "1"; 
	return $result_Arr;
}







function updateAdminTechextensionProfile($value_arr)
{
	
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	//$dataToSend = $_SESSION['tech_user_id']."*".$extension."*".$asterisk_ip."*".$channel."*".$context."*".$prefix;
	$tablename="admin";
	$condition="user_id='$value_arr[0]'";
	$fields="extension='$value_arr[1]',asterisk_ip='$value_arr[2]',channel='$value_arr[3]',context='$value_arr[4]',prefix='$value_arr[5]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = "1"; 
	return $result_Arr;
}



function checkEmailInUser($email)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "email='$email' ";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	return $count;
}




function getAllAsteriskInfo()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="asterisk_ip";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getAllLicenseModuleInfo()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="modules";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAllCRMInfo()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="crm_configuration";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}






function signupUser($value_arr)
{
	 
	$result_Arr=array();     
	$value_arr=explode('***',$value_arr); 
	$name=$value_arr[0]; 
	$email=$value_arr[1]; 
	$password=$value_arr[2];
	$original_password=$value_arr[2];
	$profile_image = "image/avtar.png";
	if($password)
	{
		$password = md5($password);
	}
		$result_email=checkEmailInUser($email);
		if($result_email > 0)
		{
			$result_Arr['status']=-2;
		}
		else
		{
		$user="";
			$tablename='user';
			$fields='`name`, `email`, `password`,`profile_image` ,`date`, `deleted`, `status`, `original_password`';
			$value="'$name','$email','$password','$profile_image',NOW(),'0','1','$original_password'";
			$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$tablename='user';
			$where_cond="email='$email' and date=NOW() and status='0'";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$user=$row['user_id'];
			} 
			 $result_Arr['id']=$user;
			 $result_Arr['status']=1;
		  }
		}
    return $result_Arr;
}






function createUser($value_arr)
{
	 
	$result_Arr=array();     
	$value_arr=explode('***',$value_arr); 
	//$name."***".$email."***".$password."***".$phone."***".$extension."***".$designation."***".$asterisk."***".$context."***".$channel."***".$prefix;
	$name=$value_arr[0]; 
	$email=$value_arr[1]; 
	$password=$value_arr[2];
	$phone=$value_arr[3];
	$extension=$value_arr[4];
	$designation=$value_arr[5];
	$asterisk=$value_arr[6];
	$context=$value_arr[7];
	$channel=$value_arr[8];
	$prefix=$value_arr[9];
	$call_log=$value_arr[10];
	
	$original_password=$value_arr[2];
	$profile_image = "image/avtar.png";
	if($password)
	{
		$password = md5($password);
	}
		$result_email=checkEmailInUser($email);
		if($result_email > 0)
		{
			$result_Arr['status']=-2;
		}
		else
		{
			
		$result_email=checkEmailPresentInAdmin($email);
		$result_email = $result_email['status'];
		if($result_email > 0)
		{
			$result_Arr['status']=-2;
			return $result_Arr;
			exit;
		}
		$user="";
			$tablename='user';
			$fields='`name`, `email`, `password`,`profile_image` ,`date`, `deleted`, `status`, `original_password`, `contact`, `designation`, `extension`, `asterisk_ip`, `channel`, `context`, `prefix`, `call_log_in_crm`';
			$value="'$name','$email','$password','$profile_image',NOW(),'0','1','$original_password','$phone','$designation','$extension','$asterisk','$channel','$context','$prefix','$call_log'";
			$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$tablename='user';
			$where_cond="email='$email' and date=NOW() and status='1'";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$user=$row['user_id'];
			} 
			 $result_Arr['id']=$user;
			 $result_Arr['status']=1;
		  }
		}
    return $result_Arr;
}

function updateCurrentCallStatus($arr_value)
{
	
	$result_Arr=array();
	$value_arr=explode('*',$arr_value);

	$asterisk_id = $value_arr[0];
	$status = $value_arr[1];

	$tablename="current_call_status";
	$condition="asterisk_id='$asterisk_id'";
	$fields="status='$status',date_time=NOW()";
	$result=update_record($fields,$condition,$tablename);
	
	 $tablename="call_information";
	$condition="unique_id='$asterisk_id'";
	$fields="status='$status',date_modified=NOW()";
	$result=update_record($fields,$condition,$tablename);
	
	
	$result_Arr['status'] = $result;
	
	
	return $result_Arr;
}


function resetLiveAgentStatus()
{
	$tablename="current_call_status";
	$condition="1";
	$fields="status='Disconnected',date_time=NOW()";
	$result=update_record($fields,$condition,$tablename);
	$result_max=update_record_max($fields,$condition,$tablename);
	$result_Arr['result'] = $result;
	$result_Arr['result_max'] = $result_max;
	return $result_Arr;
}


function getCurrentCallStatus($ext)
{
	
	$status="";
	$to_fetch="*";
	$tablename='current_call_status';
	$where_cond="extension='$ext'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$status=$row['status'];
		$asterisk_id=$row['asterisk_id'];
	}
	$arrayPackey['status'] = $status;
	$arrayPackey['asterisk_id'] = $asterisk_id;
	return $arrayPackey;
}


function getAllCurrentExtensionStatus()
{
	
	$status="";
	$to_fetch="*";
	$tablename='current_call_status';
	$where_cond="1 order by extension";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$status[]=$row['status'];
		$extension[]=$row['extension'];
		$asterisk_id[]=$row['asterisk_id'];
	}
	$dataToSend['status'] = $status;
	$dataToSend['extension'] = $extension;
	$dataToSend['asterisk_id'] = $asterisk_id;
	return $dataToSend;
}


function createQueue($queue,$holdTime,$available,$talkTime,$logged,$callers,$longestht)
{
	 
	$result_Arr=array();  
	$id="";
	$to_fetch="*";
	$tablename='queue_info';
	$where_cond="number='$queue'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if(!$id)
	{
		$fields='`number`, `date`, `holdtime`, `available`, `talktime`, `loggedin`, `callers`, `longestht`';
		$value="'$queue',NOW(),'$holdTime','$available','$talkTime','$logged','$callers','$longestht'";
		$result=insert_record($fields,$value,$tablename);
		
	}
	else{
		$condition="id='$id'";
		$fields="holdtime='$holdTime',date=NOW(),available='$available',talktime='$talkTime',loggedin='$logged',callers='$callers',longestht='$longestht'";
		$result=update_record($fields,$condition,$tablename);
	}
	return "success";
}



function createVoiceBroadcastLog($campaign_id,$unique_id,$audio_name)
{
	 
	$result_Arr=array();  
	$id="";
	$to_fetch="*";
	$tablename='voice_broadcast_log';
	$where_cond="campaign_id='$campaign_id' and unique_id='$unique_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if(!$id)
	{
		$fields='`campaign_id`, `unique_id`';
		$value="'$campaign_id','$unique_id'";
		$result=insert_record($fields,$value,$tablename);
		
	}
	else{
		$condition="id='$id'";
		$fields="campaign_id='$campaign_id',unique_id='$unique_id',audio_name='$audio_name'";
		$result=update_record($fields,$condition,$tablename);
	}
	return "success";
}


function updateVoiceBroadcastLog($unique_id,$start_time,$answer_time,$end_time,$recording,$src,$callerid,$billableseconds,$duration,$disposition,$PBX,$contact_id,$campaign_id,$destination,$lastdata,$lastapplication,$amaflag,$channel,$failcause)
{
		$tablename='voice_broadcast_log';
		$result_Arr=array();  
		$id="";
		$to_fetch="*";
		$tablename='voice_broadcast_log';
		$where_cond="campaign_id='$campaign_id' and unique_id='$unique_id'";
		$result=select_record($to_fetch,$where_cond,$tablename);
		while($row=mysqli_fetch_array($result)){
			$id=$row['id'];
		}
		if(!$id)
		{
			$fields='`unique_id`, `contact_id`, `campaign_id`, `source_number`, `callerid`, `cdr_start_time`, `cdr_answer_time`, `cdr_end_time`, `billableseconds`, `duration`, `disposition`, `pbx`, `destination`, `lastdata`, `lastapplication`, `amaflag`, `channel`, `cause`';
			$value="'$unique_id','$contact_id','$campaign_id','$src','$callerid','$start_time','$answer_time','$end_time','$billableseconds','$duration','$disposition','$PBX','$destination','$lastdata','$lastapplication','$amaflag','$channel','$failcause'";
			$result=insert_record($fields,$value,$tablename);
			
		}
		else{
			$condition="id='$id'";
			$fields="source_number='$src',contact_id='$contact_id',callerid='$callerid',cdr_start_time='$start_time',cdr_answer_time='$answer_time',cdr_end_time='$end_time',billableseconds='$billableseconds',duration='$duration',disposition='$disposition',pbx='$PBX',destination='$destination',lastdata='$lastdata',lastapplication='$lastapplication',amaflag='$amaflag',channel='$channel',cause='$failcause'";
			$result=update_record($fields,$condition,$tablename);
		}
		//if($disposition == "ANSWERED")
		//{
			$tablename="voice_broadcasting_list";
			$condition="contact_id='$contact_id' and voice_broadcast_id='$campaign_id'";
			$fields="status='$disposition'";
			$result=update_record($fields,$condition,$tablename);
		//}
		$results['status']= "success";
		$results['where']= $fields;
	return $results;
}





function createUpdateDialerCall($unique_id,$start_time,$answer_time,$end_time,$recording,$src,$callerid,$billableseconds,$duration,$disposition,$PBX,$contact_id,$campaign_id,$destination,$lastdata,$lastapplication,$amaflag,$channel,$failcause,$list_id)
{
		$tablename='dialer_log';
		$result_Arr=array();  
		$id="";
		$to_fetch="*";
		
		$where_cond="campaign_id='$campaign_id' and unique_id='$unique_id'";
		$result=select_record($to_fetch,$where_cond,$tablename);
		while($row=mysqli_fetch_array($result)){
			$id=$row['id'];
		}
		if(!$id)
		{
			$fields='`unique_id`, `contact_id`, `campaign_id`, `source_number`, `callerid`, `cdr_start_time`, `cdr_answer_time`, `cdr_end_time`, `billableseconds`, `duration`, `disposition`, `pbx`, `destination`, `lastdata`, `lastapplication`, `amaflag`, `channel`, `cause`, `list_id`, `recording`';
			$value="'$unique_id','$contact_id','$campaign_id','$src','$callerid','$start_time','$answer_time','$end_time','$billableseconds','$duration','$disposition','$PBX','$destination','$lastdata','$lastapplication','$amaflag','$channel','$failcause','$list_id','$recording'";
			$result=insert_record($fields,$value,$tablename);
			
		}
		else{
			$condition="id='$id'";
			$fields="source_number='$src',contact_id='$contact_id',callerid='$callerid',cdr_start_time='$start_time',cdr_answer_time='$answer_time',cdr_end_time='$end_time',billableseconds='$billableseconds',duration='$duration',disposition='$disposition',pbx='$PBX',destination='$destination',lastdata='$lastdata',lastapplication='$lastapplication',amaflag='$amaflag',channel='$channel',cause='$failcause',list_id='$list_id',recording='$recording'";
			$result=update_record($fields,$condition,$tablename);
		}
		//if($disposition == "ANSWERED")
		//{
			$tablename="dialer_list";
			$condition="contact_id='$contact_id' and campaign_id='$campaign_id'";
			$fields="status='$disposition'";
			$result=update_record($fields,$condition,$tablename);
		//}
		$results['status']= "success";
		$results['where']= $fields;
	return $results;
}



function fetchDialerCallLogListUniqueId($list_id,$unique_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "list_id='$list_id' and unique_id = '$unique_id'";
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getDialerCallLog($campaign_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id' order by cdr_start_time desc";
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getDialerCallLogDisposition($campaign_id,$disposition,$agent_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	if($agent_id){
		$agentSql = " and user_id='$agent_id'";
	}else{
		$agentSql = "";
	}
	
	if($disposition == "all"){
		if($campaign_id == "all"){
			$where_cond = "campaign_id is not null$agentSql order by cdr_start_time desc";
		}else{
			$where_cond = "campaign_id='$campaign_id'$agentSql order by cdr_start_time desc";
		}
	}else if($disposition == "fail")
	{
		if($campaign_id == "all"){
			$where_cond = "campaign_id is not null and disposition !='ANSWERED'$agentSql order by cdr_start_time desc";
		}else{
			$where_cond = "campaign_id='$campaign_id' and disposition !='ANSWERED'$agentSql order by cdr_start_time desc";
		}
	}else{
		if($campaign_id == "all"){
			$where_cond = "campaign_id is not null and disposition='$disposition'$agentSql order by cdr_start_time desc";
		}else{
			$where_cond = "campaign_id='$campaign_id' and disposition='$disposition'$agentSql order by cdr_start_time desc";
		}
	}
	
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  //$result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}



function getDialerPerformance($campaign_id)
{
	 
	$result_Arr=array();
	
	// total calls in dialer //
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id'";
	$tablename="dialer_log";
	$result_all_calls=select_record($to_fetch,$where_cond,$tablename);
	$total_calls=get_num_rows($result_all_calls);
	if(!$total_calls){
		$total_calls = 0;
	}
	$result_Arr['total_calls']=$total_calls;
	// total calls in dialer //
	
	// total contacts in dialer //
	$where_cond = "campaign_id='$campaign_id'";
	$tablename="dialer_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$total_contacts=get_num_rows($result);
	if(!$total_contacts){
		$total_contacts = 0;
	}
	$result_Arr['total_contacts']=$total_contacts;
	// total contacts in dialer //
	
	// total called contacts in dialer //
	$where_cond = "campaign_id='$campaign_id' and agent_disposition !=''";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$called_contacts=get_num_rows($result);
	if(!$called_contacts){
		$called_contacts = 0;
	}
	$result_Arr['called_contacts']=$called_contacts;
	// total called contacts in dialer //
	
	
	// total pending contacts to call in dialer //
	$where_cond = "campaign_id='$campaign_id' and agent_disposition ==''";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$pending_contacts=get_num_rows($result);
	if(!$pending_contacts){
		$pending_contacts = 0;
	}
	$result_Arr['pending_contacts']=$pending_contacts;
	// total pending contacts to call in dialer //
	
	$campaignInfo = getDialerCampaignFromId($campaign_id);
	$retrieve = $campaignInfo['data'][0]['retrieve'];

	// total dead contacts in dialer //
	$where_cond = "campaign_id='$campaign_id' and attemp ='$retrieve' and (status = 'NO ANSWER' OR status = 'CONGESTION' OR status = 'BUSY' OR status = 'FAILED')";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$dead_contacts=get_num_rows($result);
	if(!$dead_contacts){
		$dead_contacts = 0;
	}
	$result_Arr['dead_contacts']=$dead_contacts;
	// total dead contacts in dialer //
	
	
	// total answered calls in dialer //
	$tablename="dialer_log";
	$where_cond = "campaign_id='$campaign_id' and disposition ='ANSWERED'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$answered_calls=get_num_rows($result);
	if(!$answered_calls){
		$answered_calls = 0;
	}
	$result_Arr['answered_calls']=$answered_calls;
	// total answered calls in dialer //
	
	// occupancy rate in dialer //
	$occupancy_rate = ($answered_calls/$total_calls) * 100 ;
	$occupancy_rate = round($occupancy_rate,2); 
	$result_Arr['occupancy_rate']=$occupancy_rate." %";
	// occupancy rate in dialer //
	
	// total agents in dialer //
	$agent_in_dialer = getAgentsInCampaign($campaign_id);
	$total_agents = $agent_in_dialer['count'];
	$result_Arr['total_agents']=$total_agents;
	// total agents in dialer //
	
	// avg hold time in dialer //
	$total_hold = 0;
	$holdtime = 0;
	$total_talktime = 0;
	  if($total_calls>0){
			 while($row=mysqli_fetch_array($result_all_calls)){
				$duration=$row['duration'];
				$billableseconds=$row['billableseconds'];
				$holdtime = $duration - $billableseconds;
				$total_hold = $total_hold + $holdtime;
				$total_talktime = $total_talktime + $billableseconds;
	  }
	  }else{
		  $total_hold = 0;
		  $total_talktime = 0;
	  }
		$avg_hold_time = $total_hold/$total_calls;
		$avg_hold_time = round($avg_hold_time,2);
		$result_Arr['hold_time']=$avg_hold_time." Sec";
	// avg hold time in dialer //
	
	// avg talk time in dialer //
	  $avg_talktime = $total_talktime/$total_calls;
	  $avg_talktime = round($avg_talktime,2);
	$result_Arr['talk_time']=$avg_talktime." Sec";
	// avg talk time in dialer //
	
	return $result_Arr;
}



function getAgentPerformance($agent_id,$campaign_id)
{
	$result_Arr=array();
	if($campaign_id == "all"){
		$where_cond ="user_id='$agent_id' and campaign_id is not null";
	}else{
		$where_cond = "user_id='$agent_id' and campaign_id='$campaign_id'";
	}
	// total calls for agents //
	$to_fetch="*";
	$tablename="dialer_log";
	$result_all_calls=select_record($to_fetch,$where_cond,$tablename);
	$total_calls=get_num_rows($result_all_calls);
	if(!$total_calls){
		$total_calls = 0;
	}
	$result_Arr['total_calls']=$total_calls;
	// total calls for agents //
	
	
	if($campaign_id == "all"){
		$where_cond ="campaign_id is not null";
	}else{
		$where_cond = "campaign_id='$campaign_id'";
	}
	
	// total calls for agents //
	$to_fetch="*";
	$tablename="dialer_log";
	$result_all_calls_on_campaign=select_record($to_fetch,$where_cond,$tablename);
	$total_calls_in_campaign=get_num_rows($result_all_calls_on_campaign);
	if(!$total_calls_in_campaign){
		$total_calls_in_campaign = 0;
	}
	
	$calls_per_agent = ($total_calls/$total_calls_in_campaign) * 100 ;
	
	if($total_calls_in_campaign == "0"){
		$result_Arr['calls_per_agent']="0 %";
	}else{
		$calls_per_agent = round($calls_per_agent,2); 
		$result_Arr['calls_per_agent']=$calls_per_agent." %";
	}
	
	
	
	if($campaign_id == "all"){
		$where_cond ="user_id='$agent_id' and campaign_id is not null and disposition ='ANSWERED'";
	}else{
		$where_cond = "user_id='$agent_id' and campaign_id='$campaign_id' and disposition ='ANSWERED'";
	}
	
	
	// total answered calls for agents //
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$answered_calls=get_num_rows($result);
	if(!$answered_calls){
		$answered_calls = 0;
	}
	$result_Arr['answered_calls']=$answered_calls;
	// total answered calls for agents //
	
	// occupancy rate for agents //
	$occupancy_rate = ($answered_calls/$total_calls_in_campaign) * 100 ;
	$occupancy_rate = round($occupancy_rate,2); 
	$result_Arr['occupancy_rate']=$occupancy_rate." %";
	// occupancy rate for agents //
	
	
	// Hit rate of Agnt //
	$hit_rate = ($answered_calls/$total_calls) * 100 ;
	$hit_rate = round($hit_rate,2); 
	if($answered_calls == "0"){
		$result_Arr['hit_rate']="0 %";
	}else{
		$result_Arr['hit_rate']=$hit_rate." %";
	}
	// Hit rate of Agnt //
	
	// avg hold time for agents //
	$total_hold = 0;
	$holdtime = 0;
	$total_talktime = 0;
	  if($total_calls>0){
			 while($row=mysqli_fetch_array($result_all_calls)){
				$duration=$row['duration'];
				$billableseconds=$row['billableseconds'];
				$holdtime = $duration - $billableseconds;
				$total_hold = $total_hold + $holdtime;
				$total_talktime = $total_talktime + $billableseconds;
	  }
	  }else{
		  $total_hold = 0;
		  $total_talktime = 0;
		  // for 0 division error solve
		  //$total_calls=1;
	  }
		$avg_hold_time = $total_hold/$total_calls;
		$avg_hold_time = round($avg_hold_time,2);
		if($total_calls == 0){
			$result_Arr['hold_time']="0 Sec";
		}else{
			$result_Arr['hold_time']=$avg_hold_time." Sec";
		}
	// avg hold time for agents //
	
	// avg talk time for agents //
	  $avg_talktime = $total_talktime/$total_calls;
	  $avg_talktime = round($avg_talktime,2);
	
	
	if($total_calls == 0){
			$result_Arr['talk_time']="0 Sec";
		}else{
			$result_Arr['talk_time']=$avg_talktime." Sec";
		}
	// avg talk time for agents //
	
	return $result_Arr;
}



function getAllContactDialerCalls($contact_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "contact_id='$contact_id' order by cdr_start_time desc";
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getDialerCallLogID($call_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$call_id'";
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}




function updateVoiceBroadcastKey($campaign_id,$list_id,$key)
{
		
		$result_Arr=array();  
		$id="";
		$to_fetch="*";
		$tablename='voice_broadcasting_list';
		$where_cond="voice_broadcast_id='$campaign_id' and id='$list_id'";
		$result=select_record($to_fetch,$where_cond,$tablename);
		while($row=mysqli_fetch_array($result)){
			$id=$row['id'];
		}
		if(!$id)
		{
		}
		else{
			$condition="id='$id'";
			$fields="user_input='$key'";
			$result=update_record($fields,$condition,$tablename);
		}	
	return "success";
}



function createQueueParam($queue,$max,$aboned,$weight,$completed,$strategy,$service_percentage,$service_level)
{
	//($queue,$max,$aboned,$weight,$completed,$strategy)
	 
	$result_Arr=array();  
	$id="";
	$to_fetch="*";
	$tablename='queue_info';
	$where_cond="number='$queue'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if(!$id)
	{
		$fields='`number`, `date`, `abandoned`, `max`, `weight`, `completed`, `strategy`, `service_percentage`, `service_level`';
		$value="'$queue',NOW(),'$aboned','$max','$weight','$completed','$strategy','$service_percentage','$service_level'";
		$result=insert_record($fields,$value,$tablename);
		
	}
	else{
		$condition="id='$id'";
		$fields="abandoned='$aboned',date=NOW(),max='$max',weight='$weight',completed='$completed',strategy='$strategy',service_percentage='$service_percentage',service_level='$service_level'";
		$result=update_record($fields,$condition,$tablename);
	}
	return "success";
}


function createQueueMember($queue,$member,$calltaken,$QueueMembership,$penalty,$calleridnum,$incall,$membername,$status,$location,$pause,$pause_reason)
{
	//($queue,$max,$aboned,$weight,$completed,$strategy)
	 
	$result_Arr=array();  
	
	
	
	if($status == "0")
	{
		$status = "UNKNOWN";
	}else if($status == "1")
	{
		$status = "NOT_INUSE";
	}else if($status == "2")
	{
		$status = "INUSE";
	}else if($status == "3")
	{
		$status = "BUSY";
	}else if($status == "4")
	{
		$status = "INVALID";
	}else if($status == "5")
	{
		$status = "UNAVAILABLE";
	}
	if($status == "6")
	{
		$status = "RINGING";
	}

	$id="";
	$to_fetch="*";
	$tablename='queue_member_info';
	$where_cond="queue='$queue' and location='$location'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if(!$id)
	{
		$fields='`queue`, `date_time`, `queue_member`, `penalty`, `membership`, `callstaken`, `calleridnum`, `incall`, `membername`, `location`, `pause`, `pause_reason`, `status`';
		$value="'$queue',NOW(),'$member','$penalty','$QueueMembership','$calltaken','$calleridnum','$incall','$membername','$location','$pause','$pause_reason','$status'";
		$result=insert_record($fields,$value,$tablename);
		
	}
	else{
		$condition="id='$id'";
		$fields="queue_member='$member',date_time=NOW(),penalty='$penalty',membership='$QueueMembership',callstaken='$calltaken',calleridnum='$calleridnum',incall='$incall',membername='$membername',status='$status',location='$location',pause='$pause',pause_reason='$pause_reason'";
		$result=update_record($fields,$condition,$tablename);
	}
	return "success";
}

function getQueue()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="queue_info";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}





function createLiveQueueCall($queue,$caller,$status,$uniqiuw,$channel,$member_number,$member_name,$holtime,$ringtime,$IP)
{
	//$queue,$caller,$IP,$uniqueID,$member_number,$member_name,$holtime,$ringtime
	 
	$result_Arr=array(); 
	$tablename= "queue_live_call";
	if($status == "Ringing")
	{
		$fields='`queue`, `caller`,`unique_id`,`status`,`date_time`,`channel`';
		$value="'$queue','$caller','$uniqiuw','$status',NOW(),'$channel'";
		$result=insert_record($fields,$value,$tablename);
	}else if($status == "Disconnected")
	{
		
		$condition="queue='$queue' and caller='$caller'";
		$result=delete_record($condition,$tablename);
	}else if($status == "Call in Progress")
	{
		$fields='`queue`, `caller`, `pbx_ip`, `unique_id`, `member_number`, `agents`, `holtime`, `ringtime`, `date_time`, `status`,`channel`';
		$value="'$queue','$caller','$IP','$uniqiuw','$member_number','$member_name','$holtime','$ringtime',NOW(),'$status','$channel'";
		$result=insert_record($fields,$value,$tablename);
	}else if($status == "complete")
	{
		
		$condition="queue='$queue' and unique_id='$uniqiuw'";
		$result=delete_record($condition,$tablename);
	}
	 return $result_Arr;
}

function getAllQueueLiveCalls()
{
	 
	$result_Arr=array(); 
	$tablename= "queue_live_call";
	$to_fetch="*";
	
	$where_cond="1 order by queue DESC";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$status=$row['status'];
		if($status == "Ringing" || $status == "Call in Progress")
		{
			$dataToSend[] = $row;
		}
	}
	
	 return $dataToSend;
}


function getQueueLiveCalls($queue)
{
	 
	$result_Arr=array(); 
	$tablename= "queue_live_call";
	$to_fetch="*";
	$where_cond="queue='$queue' order by queue DESC";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$status=$row['status'];
		if($status == "Ringing" || $status == "Call in Progress")
		{
			$dataToSend[] = $row;
		}
	}
	
	 return $dataToSend;
}


function updateQueueLiveCallAgents($queue,$member,$caller,$AgentsStatus)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$agents="";
	$agentUpdatedValue="";
	$tablename='queue_live_call';
	$where_cond="queue='$queue' and caller='$caller' limit 1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
		$agents=$row['agents'];	
	}
	if($id)
	{
		if($agents)
		{
			if( strpos($agents, ",") !== false ) {
				if( strpos($agents, $member) !== false ) {
				$agentUpdatedValue =$agents;
			}
			}else
			{
				$agentUpdatedValue = $member.",".$agents;
			}
			$condition="id='$id'";
			$fields="agents='$agentUpdatedValue'";
			$result=update_record($fields,$condition,$tablename);
		}else{
			$agentUpdatedValue = $member.",";
			$condition="id='$id'";
			$fields="agents='$agentUpdatedValue'";
			$result=update_record($fields,$condition,$tablename);
		}

	}
		
	
	 return $result;
}


function changeQueueMemberStatusRing($queue,$status)
{
	 
	$to_fetch="*";
	$queueMemberId='';
	$tablename='queue_member_info';
	$where_cond="queue='$queue' and status='NOT_INUSE'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id[]=$row['id'];
	}
	$fields="status='$status'";
	for($i=0;$i<count($id);$i++)
	{
		$queueMemberId =$id[$i];
		$condition="id='$queueMemberId'";
		$result=update_record($fields,$condition,$tablename);
	}
	return 'success';
}


function changeQueueMemberStatusInUse($queue,$inteface)
{
	 
	$to_fetch="*";
	$queueMemberId='';
	$tablename='queue_member_info';
	$where_cond="queue='$queue' and location='$inteface'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}

		$fields="status='INUSE'";
		$condition="id='$id'";
		$results=update_record($fields,$condition,$tablename);

	$where_cond="queue='$queue' and status='RINGING'";
	$results=select_record($to_fetch,$where_cond,$tablename);
	 while($row=mysqli_fetch_array($results)){
		$ids[]=$row['id'];
	}
	
	for($i=0;$i<count($ids);$i++)
	{
		$queueMemberId =$ids[$i];
		
		$fields="status='NOT_INUSE'";
		$condition="id='$queueMemberId'";
		$result=update_record($fields,$condition,$tablename);
	}
	return 'success';
}

function changeStatusMemberInAllQueue($extension,$status)
{
	
	$tablename='queue_member_info';
	$fields="status='$status'";
	$condition="queue_member='$extension'";
	update_record($fields,$condition,$tablename);
}



function updateAvailableForQueue($queue)
{
	 
	$to_fetch="*";
	$queueMemberId='';
	$tablename='queue_member_info';
	$where_cond="queue='$queue' and status='NOT_INUSE'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	$tablename='queue_info';
	$fields="available='$count'";
	$condition="number='$queue'";
	$results=update_record($fields,$condition,$tablename);
}



function updateAvailableAndLoggedInAllQueue($extension,$status)
{
	 
		$queueMemberId='';
	$to_fetch="*";
	$tablename='queue_member_info';
	if($status == "NOT_INUSE")
	{
		$where_cond="queue_member='$extension' and status='UNAVAILABLE'";
	}else
	{
		$where_cond="queue_member='$extension' and status='NOT_INUSE'";
	}
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$queues[]=$row['queue'];
	}
foreach ($queues as $queue) {
	$available="";
	$loggedin="";
	$to_fetch="*";
	$tablename='queue_info';
	$where_cond="number='$queue'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$available=$row['available'];
		$loggedin=$row['loggedin'];
	}
	if($status == "NOT_INUSE")
	{
		$available = $available+1;
		$loggedin = $loggedin+1;
	}else{
		if($available > 0)
		{
			$available = $available-1;
		}
		if($loggedin > 0)
		{
			$loggedin = $loggedin-1;
		}
	}
	
	
		$fields="loggedin='$loggedin',available='$available'";
		$condition="number='$queue'";
		$results=update_record($fields,$condition,$tablename);
		}
}


function changeQueueMemberStatusNotInUse($queue,$inteface)
{
	 
	$to_fetch="*";
	$queueMemberId='';
	$tablename='queue_member_info';
	$where_cond="queue='$queue' and location='$inteface' and status='RINGING' or status='INUSE'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}

		$fields="status='NOT_INUSE'";
		$condition="id='$id'";
		$results=update_record($fields,$condition,$tablename);
}


function changeVoiceCampaignStatus($campaign_id,$user_id,$status)
{
	$tablename='voice_broadcasting';
	$fields="status='$status'";
	$condition="id='$campaign_id'";
	$results=update_record($fields,$condition,$tablename);
}

function changeDialerStatus($campaign_id,$user_id,$status)
{
	$tablename='dialer';
	$fields="status='$status'";
	$condition="id='$campaign_id'";
	$results=update_record($fields,$condition,$tablename);
}


function changeStatusAgent($campaign_id,$user_id,$status)
{
	$tablename='dialer_agent_group';
	$fields="login_status='$status'";
	$condition="campaign_id='$campaign_id' and agent_id='$user_id'";
	$results=update_record($fields,$condition,$tablename);
	//$result=update_record_max($fields,$condition,$tablename);
	return $results;
}

function makeAgentLoginLogoutInDialer($campaign_id,$user_id,$status)
{
	$tablename='dialer_agent_group';
	$fields="status='$status'";
	$condition="campaign_id='$campaign_id' and agent_id='$user_id'";
	$results=update_record($fields,$condition,$tablename);
	$result=update_record_max($fields,$condition,$tablename);
	return $results;
}




function createQueueCurrentStatus($ext,$status,$unique_id)
{
	 
	$result_Arr=array(); 
	$to_fetch="*";
	$tablename='queue_info';
	$where_cond="number='$ext'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if($id)
	{
		
		$condition="id='$id'";
		$fields="current_status='$status',date=NOW()";
		$result=update_record($fields,$condition,$tablename);
	}else
	{
		
		$fields='`number`, `current_status`,`date`';
		$value="'$ext','$status',NOW()";
		$result=insert_record($fields,$value,$tablename);
	}
	 return $result_Arr;
}

function createPortalURL($url)
{
	 
	$to_fetch="*";
	$id="";
	$tablename='license';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if($id)
	{
		
		$condition="id='$id'";
		$fields="url='$url',date_time=NOW()";
		$result=update_record($fields,$condition,$tablename);
	}else
	{
		
		$fields='`url`,`date_time`';
		$value="'$url',NOW()";
		$result=insert_record($fields,$value,$tablename);
	}
	return $result;
}

function createCall($value_arr)
{
	 
	$result_Arr=array();     
	$value_arr=explode('**',$value_arr); 
	//$asterisk_id."**".$source_number."**".$destination_number."**".$status."**".$direction."**".$call_durations.**.$tart_date;
	$user_id=$value_arr[0]; 
	$unique_id=$value_arr[1]; 
	$parent=$value_arr[2];
	$parent_id=$value_arr[3];
	$parent_name=$value_arr[4];
	$subject=$value_arr[5];
	$note=$value_arr[6];
	$recording=$value_arr[7];
	$status=$value_arr[8];
	$source_number=$value_arr[9];
	$destination_number=$value_arr[10];
	$call_second=$value_arr[11];
	$date_start=$value_arr[12];
	$date_end=$value_arr[13];
	$direction=$value_arr[14];
	$s_channel=$value_arr[15];
	$d_channel=$value_arr[16];
	$contact_id=$value_arr[17];
	if($direction == 'inbound')
	{
		$ext = $destination_number;
	}
	else
	{
		$ext = $source_number;
	}
	/**************** Create Current Call Information *************************/
	
	$to_fetch="*";
	$tablename='current_call_status';
	$where_cond="extension='$ext'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if($id)
	{
		$tablename="current_call_status";
		$condition="id='$id'";
		$fields="asterisk_id='$unique_id',status='Ringing',date_time=NOW()";
		$result=update_record($fields,$condition,$tablename);
	}else
	{
		$tablename='current_call_status';
		$fields='`extension`, `asterisk_id`, `status`,`date_time`';
		$value="'$ext','$unique_id','Ringing',NOW()";
		$result=insert_record($fields,$value,$tablename);
	}
	/**************** End Of Create Current Call Information *************************/
	
	$tablename='call_information';
	$fields='`user_id`, `unique_id`, `parent`,`parent_id` ,`parent_name`, `subject`, `status`, `direction`, `source_number`, `destination_number`, `call_second`, `date_start`, `note`, `s_channel`, `d_channel`, `contact_id`';
	$value="'$user_id','$unique_id','$parent','$parent_id','$parent_name','$subject','$status','$direction','$source_number','$destination_number','0',NOW(),'','$s_channel','$d_channel','$contact_id'";
	$result=insert_record($fields,$value,$tablename);
	if($result){

	$result_Arr['status']=1;
	}
    return $result_Arr;
}

function updateCallLogAfterSearch($asterisk_id,$name,$id,$module_name)
{
	
	$result_Arr=array();
	$tablename="call_information";
	$condition="unique_id='$asterisk_id'";
	$fields="parent='$module_name',parent_id='$id',parent_name='$name',searching='1'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = "1"; 
	return $result_Arr;
}


function getCallInfoAfterUpdation($asterisk_id)
{
	
	$to_fetch="*";
	$where_cond = "unique_id='$asterisk_id' order by date_start DESC";
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$result_Arr['module_name']=$row['parent'];
				$result_Arr['parent_id']=$row['parent_id'];
				$result_Arr['parent_name']=$row['parent_name'];
				$result_Arr['searching']=$row['searching'];
				$result_Arr['status']=$row['status'];
				$result_Arr['recording']=$row['recording'];
				$result_Arr['call_second']=$row['call_second'];
				$result_Arr['date_end']=$row['date_end'];
				$result_Arr['answertime']=$row['answertime'];
				$result_Arr['all']=$row;
			}
	
}else
{
	$result_Arr=array();
}
return $result_Arr;
}


function hangUpCall($value_arr)
{
	//$asterisk_id."*".$recordLnk."*".$duration;
	 
	$result_Arr=array();
	$value_arr=explode('*',$value_arr); 
	//$dataToSend = $asterisk_id."*".$recordLnk."*".$duration."*".$dispo."*".$lastdata."*".$total_seconds."*".$account_code."*".$clid."*".$channel."*".$amaflag."*".$endtime."*".$starttime;
	$asterisk_id=$value_arr[0]; 
	$recordLnk=$value_arr[1]; 
	$duration=$value_arr[2];
	$dispo=$value_arr[3];
	
	$lastdata=$value_arr[4];
	$total_seconds=$value_arr[5];
	$account_code=$value_arr[6];
	$clid=$value_arr[7];
	$channel=$value_arr[8];
	$amaflag=$value_arr[9];
	$endtime=$value_arr[10];
	$starttime=$value_arr[11];
	$application=$value_arr[12];
	$answertime=$value_arr[13];
	$extension=$value_arr[14];
	
		$endtime=date("Y-m-d H:i:s",strtotime($endtime));
		$starttime=date("Y-m-d H:i:s",strtotime($starttime));
		$answertime=date("Y-m-d H:i:s",strtotime($answertime));
	
	$tablename="call_information";
	if($application == "Queue")
	{
		$condition="unique_id='$value_arr[0]' and destination_number='$extension'";
	}else{
	$condition="unique_id='$value_arr[0]' order by id DESC";
	}
	$fields="recording='$recordLnk',call_second='$duration',date_end='$endtime',desposition='$dispo',lastdata='$lastdata',total_seconds='$total_seconds',account_code='$account_code',asterisk_clid='$clid',channel='$channel',amaflag='$amaflag',application_data='$application',date_start='$starttime',answertime='$answertime'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result;
return $result_Arr;	
}


function getUserInfoFromId($user_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id'";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
		  
			$to_fetch="*";
			$where_cond = "user_id='$user_id'";
			$tablename="user";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$count=get_num_rows($result);
			if($count>0){
			while($row=mysqli_fetch_array($result)){
			$data[]=$row;
			} 
			}
		  
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }
	  return $result_Arr;
}






function getAdminInfoFromId($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id'";
	$tablename="admin";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getAdminInfoFromExtension($extension)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "extension='$extension'";
	$tablename="admin";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getUserInfoFromEmail($email)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "email='$email'";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAdminInfoFromEmail($email)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "email='$email'";
	$tablename="admin";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getUserInfoFromExtension($extension)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "extension='$extension'";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['type']="user";
	  }else{
			$to_fetch="*";
			$where_cond = "extension='$extension'";
			$tablename="admin";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$count=get_num_rows($result);
			if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['type']="admin";
			}
			else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			}
	  }
	  return $result_Arr;
}









function getAllExtension()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count_user=get_num_rows($result);
	 
			 while($row=mysqli_fetch_array($result)){
				$data_user[]=$row['extension'];
				$user_name[]=$row['name'];
				$user_id[]=$row['user_id'];
			} 
			$result_Arr['count']=$count_user;
			$result_Arr['extension']=$data_user;
			$result_Arr['user_name']=$user_name;
			$result_Arr['user_id']=$user_id;
			
			$result_Arr['type']="user";
	
			$to_fetch="*";
			$where_cond = "1";
			$tablename="admin";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$count=get_num_rows($result);
			if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row['extension'];
				$admin_name[]=$row['name'];
				$admin_id[]=$row['user_id'];
			} 
			$result_Arr['count_admin']=$count;
			$result_Arr['extension_admin']=$data;
			$result_Arr['admin_name']=$admin_name;
			$result_Arr['admin_id']=$admin_id;
			$data_list = array_merge($data,$data_user);
			$data_name = array_merge($admin_name,$user_name);
			$data_id = array_merge($user_id,$admin_id);
			
			$result_Arr['extension_list']=$data_list;
			$result_Arr['name']=$data_name;
			$result_Arr['id']=$data_id;
			$result_Arr['type']="admin";
			}
			else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			}
	  
	  return $result_Arr;
}




function getAlluserInfo()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getUserCallDetails($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id' and DATE(date_start) = CURDATE() order by date_start DESC";
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function removeMemberFromCampaign($contact_id,$campaign_id)
{
	$result_Arr=array();
	$tablename="campaign_details";
	$condition="campaign_id='$campaign_id' and contact_id='$contact_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}


function removeMemberFromVoiceCampaign($contact_id,$campaign_id)
{
	$result_Arr=array();
	$tablename="voice_broadcasting_list";
	$condition="voice_broadcast_id='$campaign_id' and contact_id='$contact_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}



function removeContactFromDialerCampaign($contact_id,$campaign_id)
{
	$result_Arr=array();
	$tablename="dialer_list";
	$condition="campaign_id='$campaign_id' and contact_id='$contact_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}



function removeAgentsToDialer($agent_id,$campaign_id)
{
	$result_Arr=array();
	$tablename="dialer_agent_group";
	$condition="campaign_id='$campaign_id' and agent_id='$agent_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}


function removeMemberFromDialerCampaign($member_id,$campaign_id)
{
	$result_Arr=array();
	$tablename="dialer_agent_group";
	$condition="campaign_id='$campaign_id' and agent_id='$member_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function getUserSMSDetails($user_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id' OR user_id='1' order by date_time desc";
	$tablename="sms_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAllContactDirectSMS($contact_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "contact_id='$contact_id'";
	$tablename="sms_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getCallDetailsFromCallId($call_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$call_id'";
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getCallDetailFromID($module_name,$call_id)
{
	if($module_name == "vb")
	{
		$tablename="voice_broadcast_log";
	}
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$call_id'";
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getCallDetailsFromUniqueAndUseId($unique_id,$user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "unique_id='$unique_id' and user_id='$user_id' order by id desc ";
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['status']="1";
	  }else{
			$result_Arr['count']="0";
			$result_Arr['status']="";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getSMSDetailsFromSMSId($call_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$call_id'";
	$tablename="sms_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getStatusCampaignDetailsAfterSendSMS($user_id,$campaign_id,$contact_id)
{
	 
	$result_Arr=array();
	$to_fetch="sms_status";
	$where_cond = "user_id='$user_id' and campaign_id='$campaign_id' and contact_id='$contact_id'";
	$tablename="sms_campaign_details";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data=$row['sms_status'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['status']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['status']='';
			$result_Arr['where']=$where_cond;
	  }
	  return $result_Arr;
}


function getDetailsForVoiceBroadcastCall($user_id,$campaign_id,$contact_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id' and voice_broadcast_id='$campaign_id' and contact_id='$contact_id'";
	$tablename="voice_broadcasting_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data=$row['status'];
				$attemp=$row['attemp'];
				$user_input=$row['user_input'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['status']=$data;
			$result_Arr['attemp']=$attemp;
			$result_Arr['user_input']=$user_input;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['status']='';
			$result_Arr['attemp']='';
			$result_Arr['user_input']='';
			$result_Arr['where']=$where_cond;
	  }
	  return $result_Arr;
}



function getCounterSMSCampaignReport($campaign_id,$user_id)
{
	 
	$result_Arr=array();
	$to_fetch="sms_status, count(sms_status) as count";
	$where_cond = "campaign_id='$campaign_id' and user_id='$user_id' and sms_status !='' GROUP by sms_status";
	$tablename="sms_campaign_details";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$sms_status[]=$row['sms_status'];
				$status_count[]=$row['count'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['sms_status']=$sms_status;
			$result_Arr['status_count']=$status_count;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['status']="";
			$result_Arr['status_count']="";
	  }
	  return $result_Arr;
}

function getSMSLogsCampaignWise($campaign_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id'";
	$tablename="sms_campaign_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAllContactCampaignSMS($contact_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "contact_id='$contact_id'";
	$tablename="sms_campaign_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getVoiceBroadcastLogs($campaign_id,$contact_id,$limit)
{
	 
	if($limit == "all")
	{
		$where_cond = "campaign_id='$campaign_id' and contact_id='$contact_id' order by cdr_start_time DESC";
	}else{
		$where_cond = "campaign_id='$campaign_id' and contact_id='$contact_id' order by cdr_start_time DESC limit 1";
	}
	$result_Arr=array();
	$to_fetch="*";
	
	$tablename="voice_broadcast_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getDialerContactCampaignCallLog($campaign_id,$contact_id,$limit)
{
	 
	if($limit == "all")
	{
		$where_cond = "campaign_id='$campaign_id' and contact_id='$contact_id' order by cdr_start_time DESC";
	}else{
		$where_cond = "campaign_id='$campaign_id' and contact_id='$contact_id' order by cdr_start_time DESC limit 1";
	}
	$result_Arr=array();
	$to_fetch="*";
	
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAllVoiceBroadcastLogs($campaign_id,$type)
{
	if($type == "all")
	{
		$where_cond = "campaign_id = '$campaign_id' and disposition IS NOT NULL order by cdr_start_time DESC";
	}else{
		$where_cond = "campaign_id = '$campaign_id' and disposition='$type' order by cdr_start_time DESC";
	}
	$result_Arr=array();
	$to_fetch="*";
	$noanswerCount=0;
	$answerCount=0;
	$failedCount=0;
	$busyCount=0;
	$tablename="voice_broadcast_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount + 1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answerCount = $answerCount + 1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCount = $failedCount + 1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount + 1;
				}
			} 
			$result_Arr['count']=$count;
			$result_Arr['noanswerCount']=$noanswerCount;
			$result_Arr['answerCount']=$answerCount;
			$result_Arr['failedCount']=$failedCount;
			$result_Arr['busyCount']=$busyCount;
			//$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['noanswerCount']="0";
			$result_Arr['answerCount']="0";
			$result_Arr['failedCount']="0";
			$result_Arr['busyCount']="0";
			//$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getSMSLogForCampaign($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$id'";
	$tablename="sms_campaign_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getCampaignList($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id'";
	$tablename="sms_campaign";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}





function getVoiceCampaignList($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id'";
	$tablename="voice_broadcasting";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getDialerCampaignList($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="dialer";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getActiveVoiceCampaignList()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "status='1'";
	$tablename="voice_broadcasting";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function createCampaign($user_id,$campain_name,$sms_template,$description)
{
	$result_Arr=array();     
	
			$tablename='sms_campaign';
			$fields='`user_id`, `name`, `sms_template_id`,`date_created`,`description`';
			$value="'$user_id','$campain_name','$sms_template',NOW(),'$description'";
			$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$tablename='sms_campaign';
			$where_cond="name='$name' and user_id='$user_id' and date_created=NOW()";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
			} 
			 $result_Arr['id']=$id;
			 $result_Arr['status']=1;
		  }
	
    return $result_Arr;
}



function createVoiceCampaign($current_user,$campain_name,$audio_file,$context,$type,$number,$ivr_type,$no_of_calls,$retrieve,$wait,$asterisk_id,$trunk,$caller_id,$time_out,$prefix)
{
	$result_Arr=array();     
	
			$tablename='voice_broadcasting';
			$fields='`user_id`, `name`, `audio_name`,`type`,`number`,`ivr_type`,`context`,`number_of_calls`,`status`,`retrieve`,`wait_time`,`date_created`,`asterisk`,`trunk`,`time_out`,`caller_id`,`prefix`';
			$value="'$current_user','$campain_name','$audio_file','$type','$number','$ivr_type','$context','$no_of_calls','0','$retrieve','$wait',NOW(),'$asterisk_id','$trunk','$time_out','$caller_id','$prefix'";
			$result=insert_record($fields,$value,$tablename);
			$result_maxx=insert_record_max($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$where_cond="name='$campain_name' and user_id='$user_id' and date_created=NOW()";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
			} 
			 $result_Arr['id']=$id;
			 $result_Arr['status']=1;
			  $result_Arr['result_maxx']=$result_maxx;
		  }
	
    return $result_Arr;
}



function createDialerCampaign($current_user,$campain_name,$asterisk_id,$w_url,$context,$dialer_type,$retrieve,$wait,$search_crm,$crm_url,$prefix,$dispo_url,$start_time,$end_time,$auto_launch,$trunk,$caller_id,$time_out)
{
	
$start_time=date_create($start_time);
$start_time= date_format($start_time,"H:i:s");

$end_time=date_create($end_time);
$end_time= date_format($end_time,"H:i:s");
	
	$result_Arr=array();
			$tablename='dialer';
			$fields='`user_id`, `asterisk`, `webform_url`,`name`,`type`,`context`,`status`,`retrieve`,`wait_time`,`crm_search`,`crm_id`,`date_created`,`prefix`,`dispo_url`,`start_time`,`end_time`,`auto_launch`,`trunk`,`time_out`,`caller_id`';
			$value="'$current_user','$asterisk_id','$w_url','$campain_name','$dialer_type','$context','0','$retrieve','$wait','$search_crm','$crm_url',NOW(),'$prefix','$dispo_url','$start_time','$end_time','$auto_launch','$trunk','$time_out','$caller_id'";
			$result=insert_record($fields,$value,$tablename);
			$result_maxx=insert_record_max($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$where_cond="name='$campain_name' and user_id='$current_user' and date_created=NOW()";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
			} 
			 $result_Arr['id']=$id;
			 $result_Arr['status']=1;
			
		  }
		   $result_Arr['result_maxx']=$result_maxx;
		   
	
    return $result_Arr;
}


function createDialerDisposition($disposition)
{
$result_Arr=array();
$tablename='dialer_disposition';
$to_fetch='*';
$where_cond="disposition='$disposition'";
$result=select_record($to_fetch,$where_cond,$tablename);
$count=get_num_rows($result);

	if($count == 0)
	{
		$fields='`disposition`';
		$value="'$disposition'";
		$result=insert_record($fields,$value,$tablename);
		$result_Arr['status']="0";
	}else{
		$result_Arr['status']="1";
	}
	
    return $result_Arr;
}


function deleteDialerDispostion($id)
{
	$result_Arr=array();
	$tablename="dialer_disposition";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	//$result=delete_record_max($condition,$tablename);
	return $result;	
}


function deleteDialerCampaignDisposition($campaign_id,$disposition){
	$tablename="dialer_campaign_disposition";
	$condition="campaign_id='$campaign_id' and id='$disposition'";
	$result=delete_record($condition,$tablename);
}


function getDialerSummary($fromDate,$toDate){
	
	$dateBetween="";
	if($fromDate)
	{
		$from=date_create($fromDate);
		$from= date_format($from,"Y/m/d H:i:s");
		
		$to=date_create($toDate);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}
	
	$to_fetch='count(`agent_disposition`) as count_agent_disposition';
	$tablename='dialer_log';
	$dispoInfo = getAllDialerDisposition();
	$dialerInfo = getDialerCampaignList("all");
	
for($k=0;$k<$dialerInfo['count'];$k++){
	$dialer_id = $dialerInfo['data'][$k]['id'];
	for($i=0;$i<$dispoInfo['count'];$i++){
		$agent_disposition_name = $dispoInfo['data'][$i]['disposition'];
		$where_cond="agent_disposition = '$agent_disposition_name' and campaign_id='$dialer_id'$dateBetween";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$data[$agent_disposition_name]=$row['count_agent_disposition'];
				} 
				$result_Arr['dialer_count']=$dialerInfo['count'];
				$result_Arr['dispocount']=$dispoInfo['count'];
				$result_Arr[$dialerInfo['data'][$k]['name']]=$data;
		  }else{
				$result_Arr['count']=$count;
				$result_Arr['data']='';
		  }
	}
}
	
	
	 return $result_Arr;
}

function getAgentDispoCountWithCampaign($agent_id,$campaign_id,$disposition){
$tablename='dialer_log';
$to_fetch='*';
$where_cond="user_id = '$agent_id' and campaign_id='$campaign_id' and agent_disposition='$disposition'";
$result=select_record($to_fetch,$where_cond,$tablename);
$count=get_num_rows($result);
return $count;
}


function getDialerSummaryFromID($fromDate,$toDate,$dialer_id){
	
	$dateBetween="";
	if($fromDate)
	{
		$from=date_create($fromDate);
		$from= date_format($from,"Y/m/d H:i:s");
		
		$to=date_create($toDate);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}
	
	$to_fetch='count(`agent_disposition`) as count_agent_disposition';
	$tablename='dialer_log';
	$dispoInfo = getDialerDisposition($dialer_id);
	if($dispoInfo['count'] == 0){
		$dispoInfo =getAllDialerDisposition();
	}
	$campaignInfo = getDialerCampaignFromId($dialer_id);
	$campaign_name = $campaignInfo['data'][0]['name'];
	
	for($i=0;$i<$dispoInfo['count'];$i++){
		$agent_disposition_name = $dispoInfo['data'][$i]['disposition'];
		$where_cond="agent_disposition = '$agent_disposition_name' and campaign_id='$dialer_id'$dateBetween";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$data[$agent_disposition_name]=$row['count_agent_disposition'];
				} 
				$result_Arr['dialer_count']="1";
				$result_Arr['dispocount']=$dispoInfo['count'];
				$result_Arr[$campaign_name]=$data;
		  }else{
				$result_Arr['count']=$count;
				$result_Arr['data']='';
		  }
		  
	}
	
	$where_cond="agent_disposition = 'DEAD' and campaign_id='$dialer_id'$dateBetween";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		if($count>0){
			while($row=mysqli_fetch_array($result)){
				$data["DEAD"]=$row['count_agent_disposition'];
		} 
		$result_Arr[$campaign_name]=$data;
	}
	
	
	$where_cond="agent_disposition = 'ANSWERING MACHINE' and campaign_id='$dialer_id'$dateBetween";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		if($count>0){
			while($row=mysqli_fetch_array($result)){
				$data["ANSWERING MACHINE"]=$row['count_agent_disposition'];
		} 
		$result_Arr[$campaign_name]=$data;
	}

	
	
	 return $result_Arr;
}



function getAllAgentAllCampaignSummary($fromDate,$toDate){
	
	$dateBetween="";
	if($fromDate)
	{
		$from=date_create($fromDate);
		$from= date_format($from,"Y/m/d H:i:s");
		
		$to=date_create($toDate);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}
	
	$to_fetch='count(`agent_disposition`) as count_agent_disposition';
	$tablename='dialer_log';
	$dispoInfo = getAllDialerDisposition();
	$agentInfo = getAllAgentInAllCampaign();
	
for($k=0;$k<$agentInfo['count'];$k++){
	$agent_id = $agentInfo['data'][$k];
	for($i=0;$i<$dispoInfo['count'];$i++){
		$agent_disposition_name = $dispoInfo['data'][$i]['disposition'];
		$where_cond="agent_disposition = '$agent_disposition_name' and user_id='$agent_id'$dateBetween";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$data[$agent_disposition_name]=$row['count_agent_disposition'];
				} 
				$result_Arr['dialer_count']=$agentInfo['count'];
				$result_Arr['dispocount']=$dispoInfo['count'];
				$result_Arr[$agentInfo['data'][$k]]=$data;
		  }else{
				$result_Arr['count']=$count;
				$result_Arr['data']='';
		  }
	}
}
	
	
	 return $result_Arr;
}


function getDialerAgentDispoSummary($campaign_id){
	$to_fetch='count(`agent_disposition`) as count_agent_disposition';
	$tablename='dialer_log';
	$dispoInfo = getDialerDisposition($campaign_id);
	if($dispoInfo['count'] == 0){
		$dispoInfo =getAllDialerDisposition();
	}
	$agentInfo = getAgentsInCampaign($campaign_id);
	
for($k=0;$k<$agentInfo['count'];$k++){
	$agent_id = $agentInfo['data'][$k]['agent_id'];
	for($i=0;$i<$dispoInfo['count'];$i++){
		$agent_disposition_name = $dispoInfo['data'][$i]['disposition'];
		$where_cond="agent_disposition = '$agent_disposition_name' and user_id='$agent_id' and campaign_id='$campaign_id'";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$data[$agent_disposition_name]=$row['count_agent_disposition'];
				} 
				$result_Arr['dialer_count']=$agentInfo['count'];
				$result_Arr['dispocount']=$dispoInfo['count'];
				$result_Arr[$agentInfo['data'][$k]['agent_id']]=$data;
		  }else{
				$result_Arr['count']=$count;
				$result_Arr['data']='';
		  }
	}
	
		$agent_disposition_name = "ANSWERING MACHINE";
		$where_cond="agent_disposition = '$agent_disposition_name' and user_id='$agent_id' and campaign_id='$campaign_id'";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$data[$agent_disposition_name]=$row['count_agent_disposition'];
				} 
				$result_Arr[$agentInfo['data'][$k]['agent_id']]=$data;
		  }
		  
		 $agent_disposition_name = "DEAD";
		$where_cond="agent_disposition = '$agent_disposition_name' and user_id='$agent_id' and campaign_id='$campaign_id'";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$data[$agent_disposition_name]=$row['count_agent_disposition'];
				} 
				$result_Arr[$agentInfo['data'][$k]['agent_id']]=$data;
		  }
	
}
	
	
	 return $result_Arr;
}


function getAllDialerDisposition()
{ 
	$to_fetch='*';
	$tablename='dialer_disposition';
	$where_cond="1 order by id asc";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getTrunks()
{ 
	$to_fetch='*';
	$tablename='trunk_provider';
	$where_cond="1 order by name asc";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function createTrunkProvider($name)
{
	$tablename="trunk_provider";
	$fields='`name`';
	$value="'$name'";
	$result=insert_record($fields,$value,$tablename);
	
    return $count;
}

function removeTrunk($id){
	$tablename="trunk_provider";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
}


function getDialerDisposition($campaign_id)
{ 
	$to_fetch='*';
	$tablename='dialer_campaign_disposition';
	$where_cond="campaign_id='$campaign_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}




function getVoiceCampaignStatus($campaign_id)
{ 
	$to_fetch='*';
	$tablename='voice_broadcasting';
	$where_cond="id='$campaign_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		//$result_Arr['status']="1";
		$result_Arr['data']=$datas['status'];
	}else{
		//$result_Arr['status']="0";
		$result_Arr['data']='';
	}
    return $result_Arr;
}


function getDialerCampaignStatus($campaign_id)
{ 
	$to_fetch='*';
	$tablename='dialer';
	$where_cond="id='$campaign_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		//$result_Arr['status']="1";
		$result_Arr['data']=$datas['status'];
	}else{
		//$result_Arr['status']="0";
		$result_Arr['data']='';
	}
    return $result_Arr;
}


function getCampaignStatus($campaign_id)
{ 
	$to_fetch='*';
	$tablename='sms_campaign_status';
	$where_cond="campaign_id='$campaign_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		$result_Arr['status']="1";
		$result_Arr['data']=$datas;
	}else{
		$result_Arr['status']="0";
		$result_Arr['data']='';
	}
    return $result_Arr;
}


function getVoiceBroadcastCampaignStatus($campaign_id)
{ 
	$to_fetch='status';
	$tablename='voice_broadcasting';
	$where_cond="id='$campaign_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count>0){
		$datas=get_sgn_rows($result);
		//$result_Arr['status']=$datas;
		//$result_Arr['data']=$datas;
	}else{
		//$result_Arr['status']="0";
		//$result_Arr['data']='';
	}
    return $datas;
}



function getContactListFromCampaignID($user_id,$campaign_id)
{
	 
	$result_Arr=array();
	$to_fetch="contact_id";
	$where_cond = "user_id='$user_id' and campaign_id='$campaign_id'";
	$tablename="sms_campaign_details";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$contactDetails = getContact($contact_id);
				
					//$count = $count-1;
					$name[] = $contactDetails['data'][0]['first_name']." ".$contactDetails['data'][0]['last_name'];
					$phone[] = $contactDetails['data'][0]['phone'];
					$idss[] = $contactDetails['data'][0]['id'];
					$address[] = $contactDetails['data'][0]['address'];
					$email[] = $contactDetails['data'][0]['email'];
					$first_name[] = $contactDetails['data'][0]['first_name'];
					$last_name[] = $contactDetails['data'][0]['last_name'];
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['name']=$name;
			$result_Arr['phone']=$phone;
			$result_Arr['id']=$idss;
			$result_Arr['address']=$address;
			$result_Arr['email']=$email;
			$result_Arr['first_name']=$first_name;
			$result_Arr['last_name']=$last_name;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['name']="";
			$result_Arr['phone']="";
			$result_Arr['id']="";
			$result_Arr['address']="";
			$result_Arr['email']="";
			$result_Arr['first_name']="";
			$result_Arr['last_name']="";
	  }
	  return $result_Arr;
}




function getContactListFromVoiceCampaignID($user_id,$campaign_id,$query)
{
	 
	$result_Arr=array();
	$to_fetch="contact_id";
	if($query == "all")
	{
	$where_cond = "user_id='$user_id' and voice_broadcast_id='$campaign_id'";
	}else{
		$where_cond = "user_id='$user_id' and voice_broadcast_id='$campaign_id' and status='$query'";
	}
	$tablename="voice_broadcasting_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$contactDetails = getContact($contact_id);
					$name[] = $contactDetails['data'][0]['first_name']." ".$contactDetails['data'][0]['last_name'];
					$phone[] = $contactDetails['data'][0]['phone'];
					$idss[] = $contactDetails['data'][0]['id'];
					$address[] = $contactDetails['data'][0]['address'];
					$email[] = $contactDetails['data'][0]['email'];
					$first_name[] = $contactDetails['data'][0]['first_name'];
					$last_name[] = $contactDetails['data'][0]['last_name'];
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['name']=$name;
			$result_Arr['phone']=$phone;
			$result_Arr['id']=$idss;
			$result_Arr['address']=$address;
			$result_Arr['email']=$email;
			$result_Arr['first_name']=$first_name;
			$result_Arr['last_name']=$last_name;
			$result_Arr['where_cond']=$where_cond;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['name']="";
			$result_Arr['phone']="";
			$result_Arr['id']="";
			$result_Arr['address']="";
			$result_Arr['email']="";
			$result_Arr['first_name']="";
			$result_Arr['last_name']="";
			$result_Arr['where_cond']=$where_cond;
	  }
	  return $result_Arr;
}


function getContactListFromDialerCampaignID($user_id,$campaign_id,$query)
{
	 
	$result_Arr=array();
	$to_fetch="contact_id";
	if($query == "all")
	{
	$where_cond = "user_id='$user_id' and campaign_id='$campaign_id'";
	}else{
		$where_cond = "user_id='$user_id' and campaign_id='$campaign_id' and status='$query'";
	}
	$tablename="dialer_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$contactDetails = getContact($contact_id);
				
					//$count = $count-1;
					$name[] = $contactDetails['data'][0]['first_name']." ".$contactDetails['data'][0]['last_name'];
					$phone[] = $contactDetails['data'][0]['phone'];
					$idss[] = $contactDetails['data'][0]['id'];
					$address[] = $contactDetails['data'][0]['address'];
					$email[] = $contactDetails['data'][0]['email'];
					$first_name[] = $contactDetails['data'][0]['first_name'];
					$last_name[] = $contactDetails['data'][0]['last_name'];
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['name']=$name;
			$result_Arr['phone']=$phone;
			$result_Arr['id']=$idss;
			$result_Arr['address']=$address;
			$result_Arr['email']=$email;
			$result_Arr['first_name']=$first_name;
			$result_Arr['last_name']=$last_name;
			$result_Arr['where_cond']=$where_cond;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['name']="";
			$result_Arr['phone']="";
			$result_Arr['id']="";
			$result_Arr['address']="";
			$result_Arr['email']="";
			$result_Arr['first_name']="";
			$result_Arr['last_name']="";
			$result_Arr['where_cond']=$where_cond;
	  }
	  return $result_Arr;
}


function getDialerListInfo($campaign_id){
	$tablename="dialer_list";
	$to_fetch="*";
	$where_cond="campaign_id='$campaign_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['data']='';
	  }
	return $result_Arr;
}


function getDialerContact($campaign_id,$query){
	$tablename="dialer_list";
	$to_fetch="*";
	if($query == "all"){
		$where_cond="campaign_id='$campaign_id'";
	}else if($query == "called"){
		$where_cond="campaign_id='$campaign_id' and agent_disposition is not null";
	}else if($query == "pending"){
		$where_cond="campaign_id='$campaign_id' and agent_disposition ==''";
	}else if($query == "dead"){
		$campaignInfo = getDialerCampaignFromId($campaign_id);
		$retrieve = $campaignInfo['data'][0]['retrieve'];
		$where_cond="campaign_id='$campaign_id' and attemp ='$retrieve' and (status = 'NO ANSWER' OR status = 'CONGESTION' OR status = 'BUSY' OR status = 'FAILED')";
	}
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['data']='';
	  }
	return $result_Arr;
}



function getDialerListDispositionWise($campaign_id,$disposition){
	$tablename="dialer_list";
	$to_fetch="*";
	$where_cond="campaign_id='$campaign_id' and agent_disposition='$disposition'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['data']='';
	  }
	return $result_Arr;
}


function getDialerAgentDispoCountInCampaign($campaign_id,$disposition){
	$tablename="dialer_list";
	$to_fetch="count(`agent_disposition`) as dispocount";
	if($disposition == "all"){
		$where_cond="campaign_id='$campaign_id' and agent_disposition !=''";
	}else{
		$where_cond="campaign_id='$campaign_id' and agent_disposition='$disposition'";
	}
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$dispocount=$row['dispocount'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['dispocount']=$dispocount;
	  }else{
			$result_Arr['count']=$count;
			$result_Arr['dispocount']='0';
	  }
	return $result_Arr;
}


function getContactCampaignIdForIVRList($list_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$list_id'";
	$tablename="voice_broadcasting_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$attemp=$row['attemp'];
				$campaign_id=$row['voice_broadcast_id'];
				$user_id=$row['user_id'];
				
					//$count = $count-1;
					
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['contact_id']=$contact_id;
			$result_Arr['attemp']=$attemp;
			$result_Arr['campaign_id']=$campaign_id;
			$result_Arr['user_id']=$user_id;
			
	  }else{
			$result_Arr['count']='0';
			$result_Arr['contact_id']='0';
			$result_Arr['attemp']='0';
			$result_Arr['campaign_id']='0';
			$result_Arr['user_id']='0';
	  }
	  return $result_Arr;
}



function getContactCampaignIdForDialerList($list_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$list_id'";
	$tablename="dialer_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$attemp=$row['attemp'];
				$campaign_id=$row['campaign_id'];
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['contact_id']=$contact_id;
			$result_Arr['attemp']=$attemp;
			$result_Arr['campaign_id']=$campaign_id;
			
	  }else{
			$result_Arr['count']='0';
			$result_Arr['contact_id']='0';
			$result_Arr['attemp']='0';
			$result_Arr['campaign_id']='0';
			
	  }
	  return $result_Arr;
}


function getCountFromLiveBroadcastCampaign($campaign_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id'";
	$tablename="voice_broadcast_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	return $count;
}


function getCallsFromLiveBroadcastCampaign($campaign_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id'";
	$tablename="voice_broadcast_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$phone[]=$row['phone'];
				$status[]=$row['status'];
				$list_id[]=$row['list_id'];
				//$contact_details[] = getContactDetailsFromVoiceListId($list_id);
			}
			$dataToReturn['count']=$count;
			$dataToReturn['phone']=$phone;
			$dataToReturn['status']=$status;
			$dataToReturn['list_id']=$list_id;
			//$dataToReturn['contact_details']=$contact_details;
	  }else{
		  $dataToReturn['count']=$count;
			$dataToReturn['phone']='';
			$dataToReturn['list_id']='';
			$dataToReturn['status']='';
	  }
return $dataToReturn;
	  }


function getContactDetailsFromVoiceListId($list_id)
{
	$to_fetch="*";
		$where_cond = "id='$list_id'";
		$tablename="voice_broadcasting_list";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$attemp='';
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$contact_id=$row['contact_id'];
					$contacDetails = getContact($contact_id);
					$contact_name = $contacDetails['data'][0]['first_name']." ".$contacDetails['data'][0]['last_name'];
					$attemp=$row['attemp'];
		  }
		  $dataToReturn['contact_id']=$contact_id;
		  $dataToReturn['contact_name']=$contact_name;
		  $dataToReturn['attemp']=$attemp;
		  }else{
			$dataToReturn['contact_id']="";
			$dataToReturn['contact_name']="";
			$dataToReturn['attemp']="";
		  }
		  return $dataToReturn;
}

function getContactDialerToCall($campaign_id,$number_of_calls)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id' and status !='ANSWERED' and agent_disposition !='ANSWERING MACHINE' and agent_disposition !='DEAD' and attemp < $number_of_calls  order by attemp ASC LIMIT 1";
	$tablename="dialer_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$list_id=$row['id'];
				$attemp_in_db=$row['attemp'];
				$contactDetails = getContact($contact_id);
					$name = $contactDetails['data'][0]['first_name']." ".$contactDetails['data'][0]['last_name'];
					$phone = $contactDetails['data'][0]['phone'];
					$idss = $contactDetails['data'][0]['id'];
					$address = $contactDetails['data'][0]['address'];
					$email = $contactDetails['data'][0]['email'];
					$first_name = $contactDetails['data'][0]['first_name'];
					$last_name = $contactDetails['data'][0]['last_name'];
					$prefix = $contactDetails['data'][0]['prefix'];
					//update status
					$attemp_in_db = $attemp_in_db+1;
						$condition="id='$list_id'";
						$fields="status='dialling',attemp='$attemp_in_db'";
						$result=update_record($fields,$condition,$tablename);
					}
					$result_Arr['count']=$count;
					$result_Arr['name']=$name;
					$result_Arr['phone']=$phone;
					$result_Arr['contact_id']=$idss;
					$result_Arr['list_id']=$list_id;
					//$result_Arr['campaign_id']=$cam;
					$result_Arr['address']=$address;
					$result_Arr['email']=$email;
					$result_Arr['first_name']=$first_name;
					$result_Arr['last_name']=$last_name;
					$result_Arr['prefix']=$prefix;
					$result_Arr['where_cond']=$where_cond;
				}else{
					$result_Arr['count']=$count;
					$result_Arr['phone']="";
					//$result_Arr['where_cond']=$where_cond;
				}
	return $result_Arr;
}


function getContactListForActiveCampaign($campaign_id,$no_of_call)
{
	 
	
		$campaign_retrieve="";	  
	$to_fetch="*";
	$where_cond = "id='$campaign_id' and status ='1'";
	$tablename="voice_broadcasting";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$campaign_retrieve=$row['retrieve'];
				$number_of_calls=$row['number_of_calls'];
	  }
	  
	  }
	
	
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "voice_broadcast_id='$campaign_id' and status !='ANSWERED' and attemp < $campaign_retrieve  order by attemp ASC LIMIT $no_of_call";
	$tablename="voice_broadcasting_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$contact_id=$row['contact_id'];
				$list_id[]=$row['id'];
				$contactDetails = getContact($contact_id);
				
					//$count = $count-1;
					$name[] = $contactDetails['data'][0]['first_name']." ".$contactDetails['data'][0]['last_name'];
					$phone[] = $contactDetails['data'][0]['phone'];
					$idss[] = $contactDetails['data'][0]['id'];
					$address[] = $contactDetails['data'][0]['address'];
					$email[] = $contactDetails['data'][0]['email'];
					$first_name[] = $contactDetails['data'][0]['first_name'];
					$last_name[] = $contactDetails['data'][0]['last_name'];
				
			}
			$result_Arr['count']=$count;
			$result_Arr['name']=$name;
			$result_Arr['phone']=$phone;
			$result_Arr['id']=$idss;
			$result_Arr['list_id']=$list_id;
			//$result_Arr['campaign_id']=$cam;
			$result_Arr['address']=$address;
			$result_Arr['email']=$email;
			$result_Arr['first_name']=$first_name;
			$result_Arr['last_name']=$last_name;
			$result_Arr['where_cond']=$where_cond;
			
			
			
			
			
		/* 	$campaign_retrieve="";	  
			$to_fetch="*";
			$where_cond = "id='$campaign_id' and status ='1'";
			$tablename="voice_broadcasting";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$count=get_num_rows($result);
			if($count>0){
			while($row=mysqli_fetch_array($result)){
			$campaign_retrieve=$row['retrieve'];
			$number_of_calls=$row['number_of_calls'];
			$result_Arr['where_cond']=$where_cond;
			}
			}
			
			
		$result_Arr=array();
		$to_fetch="*";
		$where_cond = "voice_broadcast_id='$campaign_id' and attemp < $campaign_retrieve";
		$tablename="voice_broadcasting_list";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count_remains=get_num_rows($result);
		if($count_remains == 0)
		{
			$tablename="voice_broadcasting";
			$condition="id='$campaign_id'";
			$fields="status='0'";
			$result=update_record($fields,$condition,$tablename);
			$result_Arr['where_cond']=$where_cond;
		} */
			
	  }else{

	  
	$to_fetch="*";
	$where_cond = "voice_broadcast_id='$campaign_id' and status !='ANSWERED' and attemp < $campaign_retrieve order by attemp ASC LIMIT $number_of_calls";
	$tablename="voice_broadcasting_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count == 0)
	  {
			$tablename="voice_broadcasting";
			$condition="id='$campaign_id'";
			$fields="status='0'";
			$result=update_record($fields,$condition,$tablename);
			$result_Arr['count']="0";
			$result_Arr['name']="";
			$result_Arr['phone']="";
			$result_Arr['id']="";
			$result_Arr['address']="";
			$result_Arr['email']="";
			$result_Arr['first_name']="";
			$result_Arr['last_name']="";
			$result_Arr['where_cond']=$where_cond;
	  }
	  }
	  return $result_Arr;
}


function addMemberToCampaign($contact_id,$campaign_id,$user_id)
{
	
	$to_fetch="*";
	$where_cond = "user_id='$user_id' and campaign_id='$campaign_id' and contact_id='$contact_id'";
	$tablename="sms_campaign_details";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
	$tablename='sms_campaign_details';
	$fields='`user_id`, `campaign_id`, `contact_id`,`date_added`';
	$value="'$user_id','$campaign_id','$contact_id',NOW()";
		$result=insert_record($fields,$value,$tablename);
	}else{
		$result="0";	
	}
    return $result;
}


function addDispositiontoDialer($campaign_id,$disposition)
{
	
	$to_fetch="*";
	$where_cond = "campaign_id='$campaign_id' and disposition='$disposition'";
	$tablename="dialer_campaign_disposition";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
	$fields='`campaign_id`, `disposition`';
	$value="'$campaign_id','$disposition'";
		$result=insert_record($fields,$value,$tablename);
	}else{
		$result="0";	
	}
    return $result;
}


function addVoiceBroadcastLiveCalls($campaign_id,$list_id,$status,$phone)
{
	$tablename='voice_broadcast_live_calls';
	$to_fetch="*";
	$where_cond = "list_id='$list_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
		$fields='`list_id`, `campaign_id`, `status`, `phone`';
		$value="'$list_id','$campaign_id','$status','$phone'";
		$result=insert_record($fields,$value,$tablename);
		return "1";
	}
}





function addMemberToVoiceBroadcast($contact_id,$campaign_id,$user_id)
{
	
	$to_fetch="*";
	$where_cond = "user_id='$user_id' and voice_broadcast_id='$campaign_id' and contact_id='$contact_id'";
	$tablename="voice_broadcasting_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
	$tablename='voice_broadcasting_list';
	$fields='`user_id`, `voice_broadcast_id`, `contact_id`,`date_created`,`attemp`';
	$value="'$user_id','$campaign_id','$contact_id',NOW(),'0'";
		$result=insert_record($fields,$value,$tablename);
	}else{
		$result="0";	
	}
    return $count;
}


function addContactsToDialer($contact_id,$campaign_id,$user_id)
{
	
	$to_fetch="*";
	$where_cond = "user_id='$user_id' and campaign_id='$campaign_id' and contact_id='$contact_id'";
	$tablename="dialer_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
	$fields='`user_id`, `campaign_id`, `contact_id`,`date_created`,`attemp`,`status`';
	$value="'$user_id','$campaign_id','$contact_id',NOW(),'0',''";
		$result=insert_record($fields,$value,$tablename);
	}else{
		$result="0";	
	}
    return $count;
}


function addAgentsToDialer($agent_id,$campaign_id)
{
	
	$to_fetch="*";
	$where_cond = "agent_id='$agent_id' and campaign_id='$campaign_id'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
	$fields='`agent_id`, `campaign_id`, `status`,`login_status`';
	$value="'$agent_id','$campaign_id','0','1'";
	$result=insert_record($fields,$value,$tablename);
	}else{
		$result="0";	
	}
    return $count;
}


function updateSMSCampaign($campaign_id,$name,$sms_template,$description)
{
	$result_Arr=array();	
	$tablename="sms_campaign";
	$condition="id='$campaign_id'";
	$fields="name='$name',sms_template_id='$sms_template',description='$description'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function removeLiveCallFromVBList($list_id){
	$tablename="voice_broadcast_live_calls";
	$condition="list_id='$list_id'";
	$result=delete_record($condition,$tablename);
}


function removeLiveCallFromListDialer($list_id){
	$tablename="dialer_live_calls";
	$condition="list_id='$list_id'";
	$result=delete_record($condition,$tablename);
}


function removeLiveCallForAgent($agent){
	$tablename="dialer_live_calls";
	$condition="agent='$agent'";
	$result=delete_record($condition,$tablename);
}

function resetVoiceCampaign($campaign_id,$user_id)
{
	$tablename="voice_broadcast_live_calls";
	$condition="campaign_id='$campaign_id'";
	$result=delete_record($condition,$tablename);
	
	$tablename="voice_broadcast_log";
	$condition="campaign_id='$campaign_id'";
	$result=delete_record($condition,$tablename);
	
	
	$tablename='voice_broadcasting_list';
	$condition="voice_broadcast_id='$campaign_id'";
	$fields="status='',attemp='0',user_input=''";
	$result=update_record($fields,$condition,$tablename);
}

function resetDialer($campaign_id,$user_id)
{
	$tablename="dialer_list";
	$condition="campaign_id='$campaign_id'";
	$fields="status='', agent_disposition='', attemp='0'";
	$result=update_record($fields,$condition,$tablename);
	
	$tablename="dialer_live_calls";
	$condition="campaign_id='$campaign_id'";
	$result=delete_record($condition,$tablename);
	
	$tablename="dialer_log";
	$condition="campaign_id='$campaign_id'";
	$result=delete_record($condition,$tablename);
}


function updateVoiceBroadcastLiveCalls($list_id,$status)
{
	$tablename='voice_broadcast_live_calls';
	$condition="list_id='$list_id'";
	$fields="status='$status'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
	
}



function checkAndupdateAttempCalls($list_id,$retrieve,$campaign_id)
{
	$result_Arr=array();	
	$to_fetch="*";
	$where_cond = "id='$campaign_id'";
	$tablename="voice_broadcasting";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$retrieve='';
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$retrieve=$row['retrieve'];
	  }
	  }
	if($retrieve)
	{
		
		$to_fetch="*";
		$where_cond = "id='$list_id'";
		$tablename="voice_broadcasting_list";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$attemp='';
		$count=get_num_rows($result);
		  if($count>0){
				 while($row=mysqli_fetch_array($result)){
					$attemp=$row['attemp'];
		  }
		  }
	$attemp = $attemp+1;
		if($retrieve < $attemp){
			$result_Arr['status'] = "NOTOK"; 
			$result_Arr['attemp'] = $attemp; 
		}else{
			$condition="id='$list_id'";
			$fields="attemp='$attemp'";
			$result=update_record($fields,$condition,$tablename);
			$result_Arr['status'] = "OK"; 
			$result_Arr['attemp'] = $attemp; 
		}
	
	
}else{
	$result_Arr['status'] = "NOTOK"; 
	$result_Arr['attemp'] = ""; 
}
	
	
	
	return $result_Arr;
}



function updateVoiceCampaign($campaign_id,$name,$type,$ivr_type,$number,$context,$number_of_calls,$audio_name,$status,$wait_time,$retrieve,$asterisk,$trunk,$caller_id,$time_out,$prefix)
{
	$result_Arr=array();	
	$tablename="voice_broadcasting";
	$condition="id='$campaign_id'";
	if($audio_name)
	{
		$audioSql = ",audio_name='$audio_name'";
	}else{
		$audioSql = "";
	}
	$fields="name='$name',type='$type',ivr_type='$ivr_type',number='$number',context='$context',number_of_calls='$number_of_calls',status='$status',wait_time='$wait_time',retrieve='$retrieve',asterisk='$asterisk',trunk='$trunk',caller_id='$caller_id',time_out='$time_out',prefix='$prefix'$audioSql";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}


function updateDialerCampaign($campaign_id,$name,$type,$context,$wait_time,$retrieve,$asterisk,$search_crm,$crm_url,$prefix,$dispo_url,$start_time,$end_time,$auto_launch,$webform_url,$trunk,$caller_id,$time_out)
{
	$result_Arr=array();	
	$tablename="dialer";
	$condition="id='$campaign_id'";
	
	//wait_time='$wait_time'
	
	
	$start_time=date_create($start_time);
	$start_time= date_format($start_time,"H:i:s");

	$end_time=date_create($end_time);
	$end_time= date_format($end_time,"H:i:s");
	
	$fields="name='$name',type='$type',context='$context',retrieve='$retrieve',asterisk='$asterisk',crm_search='$search_crm',crm_id='$crm_url',prefix='$prefix',dispo_url='$dispo_url',start_time='$start_time',end_time='$end_time',auto_launch='$auto_launch',webform_url='$webform_url',trunk='$trunk',caller_id='$caller_id',time_out='$time_out'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}




function getSMSTemplate($user_id)
{
 
 $to_fetch="*";
	$where_cond = "user_id='$user_id'";
	$tablename="sms_template";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}




function checkDialerLiveCallStatus($list_id)
{
 
 $to_fetch="*";
	$where_cond = "list_id='$list_id'";
	$tablename="dialer_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$status=$row['status'];
				$unique_id=$row['unique_id'];
			}
			
			if($status == "disconnected"){
				$result_Arr['status']="1";
			}else if($status == "Connected"){
				$result_Arr['status']="2";
			}else if($status == "dial"){
				$result_Arr['status']="3";
			}
			$result_Arr['unique_id']=$unique_id;
	  }else{
			$result_Arr['status']="0";
			$result_Arr['unique_id']="";
	  }
	  return $result_Arr;
}


function getDialerLiveCallChannelFromListId($list_id)
{
	$to_fetch="*";
	$where_cond = "list_id='$list_id'";
	$tablename="dialer_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	//$result_q=select_record_max($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']="";
			
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}



function getDialerLiveCallFromId($campaign_id)
{
 
 $to_fetch="*";
	if($campaign_id == "all"){
		$where_cond = "campaign_id is not null order by campaign_id desc";
	}else{
		$where_cond = "campaign_id='$campaign_id' order by campaign_id desc";
	}
	$tablename="dialer_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']="";
			
	  }
	  return $result_Arr;
}




function removeSMSTemplate($id)
{
	 
	$result_Arr=array();
	$tablename="sms_template";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function removeFromBroadcastLiveCall($list_id)
{
	$result_Arr=array();
	$tablename="voice_broadcast_live_calls";
	$condition="list_id='$list_id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function updateSMSTemplate($template_id,$name,$sms)
{
	$result_Arr=array();	
	$tablename="sms_template";
	$condition="id='$template_id'";
	$fields="name='$name',message='$sms'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}


function getSMStemplateFromID($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$id'";
	$tablename="sms_template";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getUserSMSTemplates($user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "user_id='$user_id'";
	$tablename="sms_template";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function createTemplate($user_id,$name,$sms)
{ 
	$result_Arr=array();     
	
		
			$tablename='sms_template';
			$fields='`user_id`, `name`, `message`,`date_created`';
			$value="'$user_id','$name','$sms',NOW()";
			$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			
			$where_cond="name='$name' and date=NOW()";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$template_id=$row['id'];
			} 
			 $result_Arr['id']=$template_id;
			 $result_Arr['status']=1;
		  }
		
    return $result_Arr;
}

function getSMSId($mobile_no,$smsContent,$user_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "number='$mobile_no' and sms='$smsContent' and user_id='$user_id'";
	$tablename="sms_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data=$row['id'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getCallDetailsForPopup($extension)
{
	 
	$result_Arr=array();
	$currentPopupStatus='';
	$to_fetch="*";
	$where_cond = "extension='$extension' order by id DESC";
	$tablename="current_call_status";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$currentPopupStatus=$row['status'];
				$asterisk_id=$row['asterisk_id'];
			}
			$to_fetch="*";
			$where_cond = "unique_id='$asterisk_id' order by id DESC";
			$tablename="call_information";
			$result=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($result);
	  if($counts>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			}
			$result_Arr['data']=$data;
			$result_Arr['status']=$currentPopupStatus;
			$result_Arr['asteriskID']=$asterisk_id;
	  }
	  
}
else{
			$result_Arr['status']='';
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function getAllCallsDetails()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "DATE(date_start) = CURDATE() order by date_start DESC";
	//$where_cond = "DATE(date_start) >= DATE_ADD(CURDATE(), INTERVAL -3 DAY) order by date_start DESC";
	//DATE( date_start ) = CURDATE( ) 
	$tablename="call_information";
	$inboundCounter=0;
	$outboundCounter=0;
	$internalCounter=0;
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
				if($row['direction'] == "inbound")
				{
					$inboundCounter = $inboundCounter+1;
				}
				if($row['direction'] == "outbound")
				{
					$outboundCounter = $outboundCounter+1;
				}
				if($row['direction'] == "internal")
				{
					$internalCounter = $internalCounter+1;
				}
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$outboundCounter;
			$result_Arr['internalCounter']=$internalCounter;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$receivedCounter;
			$result_Arr['internalCounter']=0;
	  }
	  $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}


function getAllContactCalls($contact_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "contact_id = '$contact_id' and DATE(date_start) = CURDATE() order by date_start DESC";
	//DATE( date_start ) = CURDATE( ) 
	$tablename="call_information";
	$inboundCounter=0;
	$outboundCounter=0;
	$failedCounter=0;
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
				if($row['direction'] == "inbound")
				{
					$inboundCounter = $inboundCounter+1;
				}
				if($row['direction'] == "outbound")
				{
					$outboundCounter = $outboundCounter+1;
				}
				/* if($row['status'] == "failed")
				{
					$failedCounter = $failedCounter+1;
				} */
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$outboundCounter;
			/* $result_Arr['failedCounter']=$failedCounter; */
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$receivedCounter;
			/* $result_Arr['failedCounter']=0; */
	  }
	  return $result_Arr;
}


function updateStatusCampaignDetailsAfterSendSMS($user_id,$campaign_id,$contact_id,$status)
{
	 
	$result_Arr=array();
	
	$fields="sms_status='$status'";
	$condition = "user_id='$user_id' and campaign_id='$campaign_id' and contact_id='$contact_id'";
	$tablename="sms_campaign_details";
	$result=update_record($fields,$condition,$tablename);
	  return $result;
}


function getAllSMSDetails()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1 order by date_time DESC";
	//DATE( date_time ) = CURDATE( ) 
	$tablename="sms_log";
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';		
	  }
	  return $result_Arr;
}



function getAllDialerCalls()
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "DATE( cdr_start_time ) = CURDATE() order by cdr_start_time DESC";
	$tablename="dialer_log";
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';		
	  }
	  return $result_Arr;
}

function getAllDialerDispositionReport($fromDate,$toDate,$campaign_id,$agent_disposition,$agent_id)
{
	$result_Arr=array();
	$dateBetween="";
	$to_fetch="*";
	if(!$fromDate && !$toDate && !$campaign_id && !$agent_disposition && !$agent_id){
		$where_cond = "date >= ( CURDATE() - INTERVAL 3 DAY ) order by date DESC";
	}else{
			if($fromDate)
			{
				$from=date_create($fromDate);
				$from= date_format($from,"Y/m/d H:i:s");
				
				$to=date_create($toDate);
				$to= date_format($to,"Y/m/d H:i:s");
				$dateBetween = " and (date BETWEEN '$from' AND '$to')";
			}
			
			if($campaign_id)
			{
				$campaignSql = " and campaign_id='$campaign_id'";
			}else{
				$campaignSql = " and campaign_id IS NOT NULL";
			}
			
			if($agent_disposition)
			{
				$agentDispoSql = " and agent_disposition='$agent_disposition'";
			}else{
				$agentDispoSql = " and agent_disposition IS NOT NULL";
			}
			
			if($agent_id)
			{
				$agentSql = "agent_id='$agent_id'";
			}else{
				$agentSql = "agent_id IS NOT NULL";
			}
			$where_cond = "$agentSql$agentDispoSql$campaignSql$dateBetween order by date DESC";
	}
	$tablename="dialer_dispo_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';		
	  }
	  $result_Arr['where_cond']=$where_cond;		
	  return $result_Arr;
}



function searchAgentCalls($fromDate,$toDate,$campaign_id,$disposition,$agent_id)
{
	$result_Arr=array();
	$dateBetween="";
	$to_fetch="*";
	if($fromDate)
	{
		$from=date_create($fromDate);
		$from= date_format($from,"Y/m/d H:i:s");
		
		$to=date_create($toDate);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}
	
	if($campaign_id)
	{
		$campaignSql = " and campaign_id='$campaign_id'";
	}else{
		$campaignSql = " and campaign_id IS NOT NULL";
	}
	
	if($disposition)
	{
		$agentDispoSql = " and disposition='$disposition'";
	}else{
		$agentDispoSql = " and disposition IS NOT NULL";
	}
	
	if($agent_id)
	{
		$agentSql = "user_id='$agent_id'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	
	$where_cond = "$agentSql$agentDispoSql$campaignSql$dateBetween order by cdr_start_time DESC";
	$tablename="dialer_log";
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  //$result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}



function searchAllDialerCalls($fromDate,$toDate,$campaign_id,$agent_disposition,$agent_id)
{
	$result_Arr=array();
	$dateBetween="";
	$to_fetch="*";
	if($fromDate)
	{
		$from=date_create($fromDate);
		$from= date_format($from,"Y/m/d H:i:s");
		
		$to=date_create($toDate);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}
	
	if($campaign_id)
	{
		$campaignSql = " and campaign_id='$campaign_id'";
	}else{
		$campaignSql = " and campaign_id IS NOT NULL";
	}
	
	if($agent_disposition)
	{
		$agentDispoSql = " and agent_disposition='$agent_disposition'";
	}else{
		$agentDispoSql = " and agent_disposition IS NOT NULL";
	}
	
	if($agent_id)
	{
		$agentSql = "user_id='$agent_id'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	
	$where_cond = "$agentSql$agentDispoSql$campaignSql$dateBetween order by cdr_start_time DESC";
	$tablename="dialer_log";
	
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  //$result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}


function getAllUnreadSMSDetails()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond ="direction = 'InComing' and incoming_status='unread' order by date_time DESC";
	//DATE( date_time ) = CURDATE( ) 
	$tablename="sms_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';		
	  }
	  return $result_Arr;
}




function getAllCallsDetailsHourDispo($value_arr)
{
	 
	$value_arr=explode('*',$value_arr);
	
	$from = $value_arr[0];
	$to = $value_arr[1];
	$dispo = $value_arr[2];
	$dir = $value_arr[3];
	if(isset($value_arr[4])){
		$date=date_create($value_arr[4]);
		$date= date_format($date,"Y-m-d");
		$todate = $date." 23:59:59";
		$dateSql = " and DATE(date_start) BETWEEN '$date' AND '$todate'";
		
	}else{
		$dateSql = "and DATE(date_start) = CURDATE()";
	}
	$dispoquery='';
	$dirquery='';
	if($dispo != "all")
	{
		$dispoquery = " and desposition='$dispo'";
	}
	if($dir != "all")
	{
		$dirquery = " and direction='$dir'";
	}
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "TIME(date_start) between '$from' AND '$to'$dispoquery$dirquery$dateSql";
	$tablename="call_information";
	$inboundCounter=0;
	$outboundCounter=0;
	$failedCounter=0;
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			//$result_Arr['where_cond']=$where_cond;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			
	  }
	  //$result_Arr['count']=$result;
	  return $result_Arr;
}


function getAllCallsDetailsHourDispoQueue($value_arr)
{
	 
	$value_arr=explode('*',$value_arr);
	
	$from = $value_arr[0];
	$to = $value_arr[1];
	$dispo = $value_arr[2];
	$dir = $value_arr[3];
	
	if(isset($value_arr[4])){
		$date=date_create($value_arr[4]);
		$date= date_format($date,"Y-m-d");
		$todate = $date." 23:59:59";
		$dateSql = " and DATE(cdr_start_time) BETWEEN '$date' AND '$todate'";
		
	}else{
		$dateSql = " and DATE(cdr_start_time) = CURDATE()";
	}
	
	$dispoquery='';
	$dirquery='';
	if($dispo != "all")
	{
		$dispoquery = " and disposition='$dispo'";
	}
	if($dir != "all")
	{
		$dirquery = " and direction='$dir'";
	}
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "TIME(cdr_start_time) between '$from' AND '$to'$dispoquery$dirquery$dateSql";
	$tablename="all_time_queue_summary";
	$inboundCounter=0;
	$outboundCounter=0;
	$failedCounter=0;
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			
	  }
	  //$result_Arr['count']=$result;
	  return $result_Arr;
}


function getUserCallCountDialer($user_id)
{
	$recieveCount = 0;
	$missedCount = 0;
	$duration=0;
	
	$to_fetch="*";
	$where_cond="user_id='$user_id' and DATE(cdr_start_time) = CURDATE()";
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	$result_Arr['allCalls']=$count;
	$result_Arr['outboundCount']=$count;
	
	$to_fetch="SUM(`duration`) AS duration";
	$result=select_record($to_fetch,$where_cond,$tablename);
			while($row=mysqli_fetch_array($result)){
				$duration = $row['duration'];
			}
			if(!$duration){
				$duration=0;
			}
	$result_Arr['duration']=$duration;
	return $result_Arr;
}
function getUserCallCountWithStatus($user_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="user_id='$user_id' and DATE(date_start) = CURDATE()";
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$recieveCount = 0;
	$missedCount = 0;
	$failedCount = 0;
	$duration=0;
	$internalCount=0;
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				if(trim($row['direction']) == "inbound")
				{
					$recieveCount = $recieveCount+1;
				}
				if(trim($row['direction']) == "outbound" )
				{
					$missedCount = $missedCount+1;
				}
				if(trim($row['direction']) == "internal" )
				{
					$internalCount = $internalCount+1;
				}
				/* if(trim($row['direction']) == "failed")
				{
					$failedCount = $failedCount+1;
				} */
			}
			
			$to_fetch="SUM(`call_second`) AS duration";

	$result=select_record($to_fetch,$where_cond,$tablename);
			while($row=mysqli_fetch_array($result)){
				$duration = $row['duration'];
			}
			
			$result_Arr['allCalls']=$count;
			$result_Arr['inboundCount']=$recieveCount;
			$result_Arr['outboundCount']=$missedCount;
			$result_Arr['duration']=$duration;
			$result_Arr['internalCount']=$internalCount;
			
			/* $result_Arr['failedCount']=$failedCount; */
			
	  }else{
			$result_Arr['allCalls']=0;
			$result_Arr['inboundCount']=$recieveCount;
			$result_Arr['outboundCount']=$missedCount;
			$result_Arr['internalCount']=$internalCount;
			$result_Arr['duration']=0;
	  }
	  return $result_Arr;
}

function getAllUserCallCountWithStatus()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="DATE(date_start) = CURDATE()";
	//DATE(date_start) = CURDATE()
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$recieveCount = 0;
	$missedCount = 0;
	$failedCount = 0;
	$internalCount=0;
	$duration=0;
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				if(trim($row['direction']) == "inbound")
				{
					$recieveCount = $recieveCount+1;
				}
				if(trim($row['direction']) == "outbound" )
				{
					$missedCount = $missedCount+1;
				}
				if(trim($row['direction']) == "internal" )
				{
					$internalCount = $internalCount+1;
				}
			}
			
			$to_fetch="SUM(`call_second`) AS duration";

	$result=select_record($to_fetch,$where_cond,$tablename);
			while($row=mysqli_fetch_array($result)){
				$duration = $row['duration'];
			}
			
			$result_Arr['allCalls']=$count;
			$result_Arr['inboundCount']=$recieveCount;
			$result_Arr['outboundCount']=$missedCount;
			$result_Arr['internalCount']=$internalCount;
			$result_Arr['duration']=$duration;
	  }else{
			$result_Arr['allCalls']=$count;
			$result_Arr['inboundCount']=$recieveCount;
			$result_Arr['outboundCount']=$missedCount;
			$result_Arr['internalCount']=$internalCount;
			$result_Arr['duration']=0;
	  }
	  return $result_Arr;
}


function getAllUserDialerCallCount()
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="DATE(cdr_start_time) = CURDATE()";
	$tablename="dialer_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$recieveCount = 0;
	$missedCount = 0;
	$failedCount = 0;
	$internalCount=0;
	$duration=0;
	$count=get_num_rows($result);
	  if($count>0){
			$to_fetch="SUM(`duration`) AS duration";

	$result=select_record($to_fetch,$where_cond,$tablename);
			while($row=mysqli_fetch_array($result)){
				$duration = $row['duration'];
			}
			$result_Arr['allCalls']=$count;
			$result_Arr['outboundCount']=$count;
			$result_Arr['duration']=$duration;
	  }else{
			$result_Arr['allCalls']=$count;
			$result_Arr['outboundCount']=$count;
			$result_Arr['duration']=0;
	  }
	  return $result_Arr;
}


function getAsteriskById($asterisk_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="id='$asterisk_id'";
	$tablename="asterisk_ip";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function checkConference($conference)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="conference='$conference' and channel !=''";
	$tablename="dialer_conference";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getDialerRealtimeReport($campaign_id)
{
	////count live total_calls in campaign
	$result_Arr=array();
	$total_calls=0;
	$total_ringing=0;
	$total_connected=0;
	$to_fetch="sum(`attemp`) as total_calls";
	if($campaign_id =="all"){
		$where_cond="campaign_id is not null";
	}else{
		$where_cond="campaign_id='$campaign_id'";
	}
	$tablename="dialer_list";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$total_calls=$row['total_calls'];
			}
	  }else{
		  $total_calls=0;
	  }
	  
	$result_Arr['total_calls']=$total_calls;
	  
	  
	//count dial and connected calls in live campaign
	$to_fetch="*";
	//$where_cond="campaign_id='$campaign_id'";
	$tablename="dialer_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$status_count=get_num_rows($result);
	  if($status_count>0){
			 while($row=mysqli_fetch_array($result)){
				if(trim($row['status']) == "dial")
				{
					$total_ringing = $total_ringing+1;
				}
				if(trim($row['status']) == "Connected")
				{
					$total_connected = $total_connected+1;
				}
			}
			$result_Arr['total_ringing']=$total_ringing;
			$result_Arr['total_connected']=$total_connected;
	  }else{
			$result_Arr['total_ringing']='0';
			$result_Arr['total_connected']='0';
	  }
	  
	  
	  //Count Total Agents, Paused Agents and Admin Paused Agent
	  $total_paused=0;
	  $admin_paused=0;
	$to_fetch="*";
	//$where_cond="campaign_id='$campaign_id'";
	$tablename="dialer_agent_group";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$total_agents=get_num_rows($result);
	  if($total_agents>0){
			 while($row=mysqli_fetch_array($result)){
				if(trim($row['status']) == "0")
				{
					$total_paused = $total_paused+1;
				}
				if(trim($row['login_status']) == "0")
				{
					$admin_paused = $admin_paused+1;
				}
			}
			$result_Arr['total_agents']=$total_agents;
			$result_Arr['total_paused']=$total_paused;
			$result_Arr['admin_paused']=$admin_paused;
	  }else{
			$result_Arr['total_agents']='0';
			$result_Arr['total_paused']='0';
			$result_Arr['admin_paused']='0';
	  }
	  
	  
	//Count Agent Logged In in Dialer
	$to_fetch="*";
	if($campaign_id =="all"){
		$where_cond="campaign_id is not null and conference !='' and channel !=''";
	}else{
		$where_cond="campaign_id='$campaign_id' and conference !='' and channel !=''";
	}
	$tablename="dialer_conference";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$total_loggedin=get_num_rows($result);
	  if($total_loggedin>0){
			 $result_Arr['total_loggedin']=$total_loggedin;
	  }else{
			$result_Arr['total_loggedin']='0';
			
	  }
	  return $result_Arr;
}


function updateLiveCallStatusForDialer($status,$list_id,$channel,$unique_id)
{
	$result_Arr=array();	
	$tablename="dialer_live_calls";
	$condition="list_id='$list_id'";
	$fields="status='$status',channel='$channel',unique_id='$unique_id'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function updateContactChannel($list_id,$channel)
{
	$result_Arr=array();	
	$tablename="dialer_live_calls";
	$condition="list_id='$list_id'";
	$fields="channel='$channel'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}




function createConferenceInDB($name,$agent,$campaign_id)
{
	$result_Arr=array(); 
	$tablename= "dialer_conference";
	$to_fetch="*";
	$where_cond="conference='$name'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{
		$fields='`conference`, `agent`, `campaign_id`';
		$value="'$name','$agent','$campaign_id'";
		$result=insert_record($fields,$value,$tablename);
	}
	return "1";
}


function addDispositionToDialerList($list_id,$disposition){
	$result_Arr=array(); 
	$tablename= "dialer_list";
	/* $to_fetch="*";
	$where_cond="id='$list_id' and agent_disposition !=''";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count == 0)
	{ */
		$condition="id='$list_id'";
		$fields="agent_disposition='$disposition'";
		$result=update_record($fields,$condition,$tablename);
	/* } */
	return $result;
}

function updateAgentDispositionInDialerCallLog($unique_id,$agent_disposition,$user_id){
	$result_Arr=array(); 
	$tablename= "dialer_log";
	
		$condition="unique_id='$unique_id'";
		$fields="agent_disposition='$agent_disposition',user_id='$user_id'";
		$result=update_record($fields,$condition,$tablename);
	
	return $result;
}








function createLiveCallInDBForDialer($phone,$list_id,$campaign_id,$agent,$status)
{ 
	$result_Arr=array();  
			$tablename='dialer_live_calls';
			$fields='`phone`, `list_id`, `campaign_id`,`agent`,`status`';
			$value="'$phone','$list_id','$campaign_id','$agent','$status'";
			$result=insert_record($fields,$value,$tablename);
		  if($result){
			 $to_fetch="*";
			$where_cond="list_id='$list_id' and agent='$agent' and status='$status' and phone='$phone'";
			$result=select_record($to_fetch,$where_cond,$tablename);
			 while($row=mysqli_fetch_array($result)){
				$live_call_id=$row['id'];
			} 
			 $result_Arr['id']=$live_call_id;
			 $result_Arr['status']=1;
		  }
    return $result_Arr;
}


function checkLiveCallForAgentInDialer($agent)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="agent='$agent'";
	$tablename="dialer_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getDilaerliveCallUniqueId($unique_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="unique_id='$unique_id'";
	$tablename="dialer_live_calls";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}


function getAsteriskDetailsByIP($ip)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="ip='$ip'";
	$tablename="asterisk_ip";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}







function getCRMById($id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="id='$id'";
	$tablename="crm_configuration";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}

function updateAdminProfileCRM($arr_value)
{
	 
	$result_Arr=array();
	$value_arr=explode('*',$arr_value);
	$crm_url = trim($value_arr[1]);

	$tablename="admin";
	$condition="user_id='$value_arr[0]'";
	$fields="crm_url='$value_arr[1]',crm_username='$value_arr[2]',crm_password='$value_arr[3]',secret='$value_arr[4]',crm_type='$value_arr[5]',crm_user_id='$value_arr[6]',ms_client_id='$value_arr[7]',call_log_in_crm='$value_arr[8]' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}

function updateChannelInConference($conferencename,$conferencechannel)
{
	 
	$result_Arr=array();
	
	$tablename="dialer_conference";
	$condition="conference='$conferencename'";
	$fields="channel='$conferencechannel'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}





function updateCRMCallId($crm_id,$portal_id)
{
	 
	$result_Arr=array();
	
	$tablename="call_information";
	$condition="id='$portal_id'";
	$fields="crm_call_id='$crm_id'";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}


function createCRMConfiguration($value_arr)
{
	 
	$result_Arr=array();
	$value_arr=explode('max',$value_arr);
	//$user_id = trim($value_arr[0]);
	$name = trim($value_arr[1]);
	$crm_url = trim($value_arr[2]);
	$admin_username = trim($value_arr[3]);
	$admin_password = trim($value_arr[4]);
	$secret = trim($value_arr[5]);
	$for_all = trim($value_arr[6]);
	$client_id = trim($value_arr[7]);
	$to_fetch='*';
	$tablename='crm_configuration';
	$where_cond="crm_url='$crm_url'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);

	if($count>0){
		$result_Arr['status']="present";
		$result_Arr['id']="";
	}else{
		$tablename='crm_configuration';
		$fields='`crm_url`, `date`, `admin_username`, `admin_password`, `for_all`, `crm_name`, `secret`, `ms_client_id`';
		$value="'$crm_url',NOW(),'$admin_username','$admin_password','$for_all','$name','$secret','$client_id'";
		$result=insert_record($fields,$value,$tablename);
		
		$where_cond="crm_url='$crm_url' and admin_username='$admin_username'";
		$results=select_record($to_fetch,$where_cond,$tablename);
		while($row=mysqli_fetch_array($results)){
			$id=$row['id'];
		} 
		
		$result_Arr['status']="created";
		$result_Arr['id']=$id;
	} 
	return $result_Arr;
}

function getAllCRMList()
{
 
 $to_fetch='*';
	  $tablename='crm_list';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$datas[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$datas;
			
	  }else{
			$result_Arr['status']="0";
			$result_Arr['data']='';
	  } 
	  return $result_Arr;
}

function getCallBySearch($value_arr)
{
	 //Deprecated from 10_09_2020
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_start BETWEEN '$from' AND '$to')";
	}
	
	$direction=strtolower($value_arr[2]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}
	
	$last_application=$value_arr[5];
	if($last_application)
	{
		$dlast_applicationSql = " and application_data='$last_application'";
	}
	
	$missedCounter=0;
	$receivedCounter=0;
	$failedCounter=0;
	$to_fetch="*";
	$where_cond = "$agentSql$dispositionSql$directionSql$dateBetween$dlast_applicationSql order by date_start DESC";
	$tablename="call_information";
	$result=select_record($to_fetch,$where_cond,$tablename);
 	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				if($row['desposition'] == "NO ANSWER")
				{
					$missedCounter = $missedCounter+1;
				}
				if($row['desposition'] == "FAILED")
				{
					$receivedCounter = $receivedCounter+1;
				}
				if($row['desposition'] == "BUSY")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['desposition'] == "ANSWERED")
				{
					$failedCounter = $failedCounter+1;
				}
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['missedCounter']=$missedCounter;
			$result_Arr['receivedCounter']=$receivedCounter;
			$result_Arr['failedCounter']=$failedCounter;
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			$result_Arr['missedCounter']=0;
			$result_Arr['receivedCounter']=0;
			$result_Arr['failedCounter']=0;
	  }   
	  
	  //$result_Arr['data']=$result;
	  
	  return $result_Arr;
}



function getSMSBySearch($value_arr)
{
	 
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_time BETWEEN '$from' AND '$to')";
	}else
	{
		//$dateBetween = " and DATE(date_time) = CURDATE()";
		$dateBetween = "";
	}
	
	$agent=$value_arr[2];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$to_fetch="*";
	$where_cond = "$agentSql$dateBetween";
	$tablename="sms_log";
	$result=select_record($to_fetch,$where_cond,$tablename);
 	 $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			
	  }  
	  
	  //$result_Arr['data']=$result;
	  
	  return $result_Arr;
}




function readSMSFromID($id,$status)
{
	 
	$result_Arr=array();
	

	$tablename="sms_log";
	$condition="id='$id'";
	$fields="incoming_status='$status' ";
	$result=update_record($fields,$condition,$tablename);
	$result_Arr['status'] = $result; 
	return $result_Arr;
}


function searchNormalCalls($fromDate,$toDate,$direction,$agent,$disposition)
{
//Updated Function for getAllCallsDetailsConditionWise
	$directionSql="";
	$agentSql="";
	
	$to_fetch="*";
	if($fromDate)
	{
		$fromDate=date_create($fromDate);
		$fromDate= date_format($fromDate,"Y/m/d H:i:s");
		
		$toDate=date_create($toDate);
		$toDate= date_format($toDate,"Y/m/d H:i:s");
		$dateBetween = " and date_start BETWEEN '$fromDate' AND '$toDate' order by date_start DESC";
	}else{
	//all time calls
		$dateBetween = "";
	}

	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}else{
		$directionSql = " and direction IS NOT NULL";
	}

	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}else{
		$dispositionSql = " and desposition IS NOT NULL";
	}
	$where_cond = "$agentSql$dispositionSql$directionSql$dateBetween";
	$tablename="call_information";
	$inboundCounter=0;
	$outboundCounter=0;
	$internalCounter=0;
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
				if($row['direction'] == "inbound")
				{
					$inboundCounter = $inboundCounter+1;
				}
				if($row['direction'] == "outbound")
				{
					$outboundCounter = $outboundCounter+1;
				}
				 if($row['direction'] == "internal")
				{
					$internalCounter = $internalCounter+1;
				}
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$outboundCounter;
			$result_Arr['internalCounter']=$internalCounter;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']=$where_cond;
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$receivedCounter;
			$result_Arr['internalCounter']=0;
	  }
	  return $result_Arr;
}

function getAllCallsDetailsConditionWise($value_arr)
{
	 //Deprecated Function from 10_09_2020
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_start BETWEEN '$from' AND '$to')";
	}
	
	$direction=strtolower($value_arr[2]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}
	$to_fetch="*";
	$where_cond = "$agentSql$dispositionSql$directionSql$dateBetween";
	//DATE( date_start ) = CURDATE( ) 
	$tablename="call_information";
	$inboundCounter=0;
	$outboundCounter=0;
	$failedCounter=0;
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
				if($row['direction'] == "inbound")
				{
					$inboundCounter = $inboundCounter+1;
				}
				if($row['direction'] == "outbound")
				{
					$outboundCounter = $outboundCounter+1;
				}
				/* if($row['status'] == "failed")
				{
					$failedCounter = $failedCounter+1;
				} */
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$outboundCounter;
			/* $result_Arr['failedCounter']=$failedCounter; */
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			$result_Arr['inboundCounter']=$inboundCounter;
			$result_Arr['outboundCounter']=$receivedCounter;
			/* $result_Arr['failedCounter']=0; */
	  }
	  return $result_Arr;
}



function getDataForBarChart()
{
 $to_fetch='*';
	  $tablename='user';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $user_id[]=$row['user_id'];
				$datas[]=$row['name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$user_id;
	  }
	  
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$value' and DATE(date_start) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$inboundcount=0;
				$outcount=0;
				
				while($rows=mysqli_fetch_array($results)){
				if($rows['direction'] == "inbound")
				{
					$inboundcount = $inboundcount+1;
				}else
				{
					$inboundcount = 0;
				}
				if($rows['direction'] == "outbound")
				{
					$outcount = $outcount+1;
				}else
				{
					$outcount = 0;
				}
					
				 $result_Arr['where_cond'][$value]=$where_cond;
				 $result_Arr['inbound'][$value]=$inboundcount;
				$result_Arr['outbound'][$value]=$outcount;
				$result_Arr['totalrecord'][$value]=$counts;
			}
			}
			
			
			$to_fetch='*';
			$tablename='dialer_log';
			$where_cond="user_id='$value' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$dialer_call_count=get_num_rows($results);
				$result_Arr['outbound'][$value]=$result_Arr['outbound'][$value]+$dialer_call_count;
				$result_Arr['totalrecord'][$value]=$result_Arr['totalrecord'][$value]+$dialer_call_count;
		}
	  return $result_Arr;
}

function getSummaryDataForInAndOut()
{
 
 $to_fetch='*';
	  $tablename='user';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $user_id[]=$row['user_id'];
				$datas[]=$row['name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$user_id;
	  }
	  
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$value' and DATE(date_start) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$inboundcount=0;
				$outcount=0;
				$totalinDuration=0;
				$totalOutDuration=0;
				while($rows=mysqli_fetch_array($results)){
			
			
				if($rows['direction'] == "inbound")
				{
					$inboundcount = $inboundcount+1;
					$totalinDuration = $totalinDuration + $rows['call_second'];
				}
				if($rows['direction'] == "outbound")
				{
					$outcount = $outcount+1;
					$totalOutDuration = $totalOutDuration + $rows['call_second'];
				}
					$total = $totalinDuration + $totalOutDuration;
					/* $total= $total/60;
					$total = number_format($total, 2, '.', ''); */
				 $result_Arr['inbound'][$value]=$inboundcount;
				 $result_Arr['totalInDuration'][$value]=$totalinDuration;
				 $result_Arr['totalOutDuration'][$value]=$totalOutDuration;
				$result_Arr['outbound'][$value]=$outcount;
				$result_Arr['total'][$value]=$total;
				//$result_Arr['totalrecord'][$value]=$counts;
			}
			}
			else{
				$result_Arr['inbound'][$value]=0;
				$result_Arr['outbound'][$value]=0;
				 $result_Arr['totalInDuration'][$value]=0;
				 $result_Arr['totalOutDuration'][$value]=0;
				 $result_Arr['total'][$value]=0;
				//$result_Arr['totalrecord'][$value]=0;
			}

			
		}
	  
	  return $result_Arr;
}


function getSummaryDataForInAndOutConditionWise($value_arr)
{
  
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_start BETWEEN '$from' AND '$to')";
	}
	
	$direction=strtolower($value_arr[2]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}
	
	if(!$agent)
	{
		$to_fetch='*';
	  $tablename='user';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $user_id[]=$row['user_id'];
				$datas[]=$row['name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$user_id;
	  }
	 // $result_Arr['user_name']=$result;
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$value'$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$inboundcount=0;
				$outcount=0;
				$totalinDuration=0;
				$totalOutDuration=0;
				while($rows=mysqli_fetch_array($results)){
			
			
				if($rows['direction'] == "inbound")
				{
					$inboundcount = $inboundcount+1;
					$totalinDuration = $totalinDuration + $rows['call_second'];
				}
				if($rows['direction'] == "outbound")
				{
					$outcount = $outcount+1;
					$totalOutDuration = $totalOutDuration + $rows['call_second'];
				}
					$total = $totalinDuration + $totalOutDuration;
					
				 $result_Arr['inbound'][$value]=$inboundcount;
				 $result_Arr['totalInDuration'][$value]=$totalinDuration;
				 $result_Arr['totalOutDuration'][$value]=$totalOutDuration;
				$result_Arr['outbound'][$value]=$outcount;
				$result_Arr['total'][$value]=$total;
				//$result_Arr['totalrecord'][$value]=$counts;
			}
			}
			else{
				$result_Arr['inbound'][$value]=0;
				$result_Arr['outbound'][$value]=0;
				 $result_Arr['totalInDuration'][$value]=0;
				 $result_Arr['totalOutDuration'][$value]=0;
				 $result_Arr['total'][$value]=0;
				//$result_Arr['totalrecord'][$value]=0;
			}

			
		 }
	}
	else{
		$to_fetch='*';
	  $tablename='user';
	  $where_cond=$agentSql;
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				
				$result_Arr['user_name']=$row['name'];
				
			}
	  }
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="$agentSql$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$inboundcount=0;
				$outcount=0;
				$totalinDuration=0;
				$totalOutDuration=0;
				
				while($rows=mysqli_fetch_array($results)){
			
			
				if($rows['direction'] == "inbound")
				{
					$inboundcount = $inboundcount+1;
					$totalinDuration = $totalinDuration + $rows['call_second'];
				}
				if($rows['direction'] == "outbound")
				{
					$outcount = $outcount+1;
					$totalOutDuration = $totalOutDuration + $rows['call_second'];
				}
					$total = $totalinDuration + $totalOutDuration;
				
				 $result_Arr['inbound'][$value]=$inboundcount;
				 $result_Arr['totalInDuration'][$value]=$totalinDuration;
				 $result_Arr['totalOutDuration'][$value]=$totalOutDuration;
				$result_Arr['outbound'][$value]=$outcount;
				$result_Arr['total'][$value]=$total;
				//$result_Arr['totalrecord'][$value]=$counts;
			}
			} else{
				$result_Arr['inbound'][$value]=0;
				$result_Arr['outbound'][$value]=0;
				 $result_Arr['totalInDuration'][$value]=0;
				 $result_Arr['totalOutDuration'][$value]=0;
				 $result_Arr['total'][$value]=0;
				
			}
	}
	  return $result_Arr;
}


function getTableSummary()
{
	 
	$result_Arr=array();

 
 $to_fetch='*';
	  $tablename='user';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $user_id[]=$row['user_id'];
				$datas[]=$row['name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$user_id;
	  }
	  
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$value' and DATE(date_start) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
		

		//echo $results;
			$counts=get_num_rows($results);
			if($counts>0){
//				echo "adsadsads";
				$noanswercounter=0;
	  $failedCounter=0;
	  $busyCount=0;
	  $answeredCount=0;
				
				while($row=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			//echo $row['desposition'];
				if($row['desposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['desposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['desposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['desposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
			//$result_Arr['noanswercounter'] = "Mahavir";
				 $result_Arr['noanswercounter'][$value]=$noanswercounter;
				$result_Arr['failedCounter'][$value]=$failedCounter;
				$result_Arr['busyCount'][$value]=$busyCount;
				$result_Arr['answeredCount'][$value]=$answeredCount;
				

			}
			} 

			
		}
	  
	  return $result_Arr;

			}
			
			
			
			
function getTableDispoSummaryConditionWise($value_arr)
{
	 
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_start BETWEEN '$from' AND '$to')";
	}
	
	$direction=strtolower($value_arr[2]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}
	if(!$agent)
	{
		$to_fetch='*';
	  $tablename='user';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $user_id[]=$row['user_id'];
				$datas[]=$row['name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$user_id;
	  }
	  
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$value'$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				while($row=mysqli_fetch_array($results)){

				if($row['desposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['desposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['desposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['desposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				 $result_Arr['noanswercounter'][$value]=$noanswercounter;
				$result_Arr['failedCounter'][$value]=$failedCounter;
				$result_Arr['busyCount'][$value]=$busyCount;
				$result_Arr['answeredCount'][$value]=$answeredCount;
				

			}
			}else{
				 $result_Arr['noanswercounter'][$value]=0;
				$result_Arr['failedCounter'][$value]=0;
				$result_Arr['busyCount'][$value]=0;
				$result_Arr['answeredCount'][$value]=0;
			} 
		}
	}else
		{
			$to_fetch='*';
	  $tablename='user';
	  $where_cond=$agentSql;
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				
				$result_Arr['user_name']=$row['name'];
				
			}
	  }
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="$agentSql$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
			$noanswercounter=0;
			$failedCounter=0;
			$busyCount=0;
			$answeredCount=0;
				
				while($rows=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			
				if($rows['desposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($rows['desposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($rows['desposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['desposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
			//$result_Arr['noanswercounter'] = "Mahavir";
				 $result_Arr['noanswercounter']=$noanswercounter;
				$result_Arr['failedCounter']=$failedCounter;
				$result_Arr['busyCount']=$busyCount;
				$result_Arr['answeredCount']=$answeredCount;
			}
			} else{
				 $result_Arr['noanswercounter']=0;
				$result_Arr['failedCounter']=0;
				$result_Arr['busyCount']=0;
				$result_Arr['answeredCount']=0;
				
			}
			
			
		}
	 // $result_Arr['ss']=$results;
	  return $result_Arr;

}

function getDataForBarChartConditionWise($value_arr)
{
  
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_start BETWEEN '$from' AND '$to')";
	}
	
	$direction=strtolower($value_arr[2]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}
	
	if(!$agent)
	{
	$to_fetch='*';
	  $tablename='user';
	  $where_cond="1";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $user_id[]=$row['user_id'];
				$datas[]=$row['name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$user_id;
	  }
	  
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$value'$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$inboundcount=0;
	  $outcount=0;
				
				while($rows=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			
				if($rows['direction'] == "inbound")
				{
					$inboundcount = $inboundcount+1;
				}else
				{
					//$inboundcount = 0;
				}
				if($rows['direction'] == "outbound")
				{
					$outcount = $outcount+1;
				}else
				{
					//$outcount = 0;
				}
					
				 $result_Arr['inbound'][$value]=$inboundcount;
				$result_Arr['outbound'][$value]=$outcount;
				$result_Arr['totalrecord'][$value]=$counts;
			}
			} 
		}
			
		}else
		{
			
			
			
			$to_fetch='*';
	  $tablename='user';
	  $where_cond=$agentSql;
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				
				$result_Arr['user_name']=$row['name'];
				
			}
	  }
		
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="$agentSql$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$inboundcount=0;
	  $outcount=0;
				
				while($rows=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			
				if($rows['direction'] == "inbound")
				{
					$inboundcount = $inboundcount+1;
				}else
				{
					//$inboundcount = 0;
				}
				if($rows['direction'] == "outbound")
				{
					$outcount = $outcount+1;
				}else
				{
					//$outcount = 0;
				}
					
				 $result_Arr['inbound'][$value]=$inboundcount;
				$result_Arr['outbound'][$value]=$outcount;
				$result_Arr['totalrecord'][$value]=$counts;
			}
			} 
		}
	  
	  return $result_Arr;
}




function getDataForPieChartHome()
{	
	$to_fetch='*';
	$tablename='call_information';
	$where_cond="DATE(date_start) = CURDATE() order by user_id ASC";
	$results=select_record($to_fetch,$where_cond,$tablename);
	$counts=get_num_rows($results);
	if($counts>0){
	$noanswerCount=0;
	$failedCount=0;
	$busyCount=0;
	$answeredCount=0;
		
		while($rows=mysqli_fetch_array($results)){
	//	echo $rows['direction']."   ";
	
		if($rows['desposition'] == "NO ANSWER")
		{
			$noanswerCount = $noanswerCount+1;
		}
		if($rows['desposition'] == "FAILED")
		{
			$failedCount = $failedCount+1;
		}
		if($rows['desposition'] == "BUSY")
		{
			$busyCount = $busyCount+1;
		}
		if($rows['desposition'] == "ANSWERED")
		{
			$answeredCount = $answeredCount+1;
		}
			
		 $result_Arr['noanswer']/* [$value] */=$noanswerCount;
		$result_Arr['failed']/* [$value] */=$failedCount;
		$result_Arr['busy']/* [$value] */=$busyCount;
		$result_Arr['answered']/* [$value] */=$answeredCount;
	}
	}else{
		$result_Arr['noanswer']=0;
		$result_Arr['failed']=0;
		$result_Arr['busy']=0;
		$result_Arr['answered']=0;
	}
	
	
	$to_fetch='*';
			$tablename='dialer_log';
			$where_cond="DATE(cdr_start_time) = CURDATE() order by user_id ASC";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
			$noanswerCount=0;
			$failedCount=0;
			$busyCount=0;
			$answeredCount=0;
				while($rows=mysqli_fetch_array($results)){
			
				if($rows['disposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedCount = $failedCount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
					
				
			}
			$result_Arr['noanswer']=$result_Arr['noanswer']+$noanswerCount;
			$result_Arr['failed']=$result_Arr['failed']+$failedCount;
			$result_Arr['busy']=$result_Arr['busy']+$busyCount;
			$result_Arr['answered']=$result_Arr['answered']+$answeredCount;
			}
return $result_Arr;
}





function getDataForPieChartConditionWise($value_arr)
{
  
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_start BETWEEN '$from' AND '$to')";
	}
	
	$direction=strtolower($value_arr[2]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}

	
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="$agentSql$dispositionSql$directionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			 $counts=get_num_rows($results);
			if($counts>0){
			$noanswerCount=0;
			$failedCount=0;
			$busyCount=0;
			$answeredCount=0;
				
				while($rows=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			
				if($rows['desposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount+1;
				}
				if($rows['desposition'] == "FAILED")
				{
					$failedCount = $failedCount+1;
				}
				if($rows['desposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['desposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
					
				 $result_Arr['noanswer']/* [$value] */=$noanswerCount;
				$result_Arr['failed']/* [$value] */=$failedCount;
				$result_Arr['busy']/* [$value] */=$busyCount;
				$result_Arr['answered']/* [$value] */=$answeredCount;
			}
			} 
	  return $result_Arr;
}








function getDataForPieChartHomeUserWise($user_id)
{
			$to_fetch='*';
			$tablename='call_information';
			$where_cond="user_id='$user_id' and DATE(date_start) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
			$noanswerCount=0;
			$failedCount=0;
			$busyCount=0;
			$answeredCount=0;
				while($rows=mysqli_fetch_array($results)){
			
				if($rows['desposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount+1;
				}
				if($rows['desposition'] == "FAILED")
				{
					$failedCount = $failedCount+1;
				}
				if($rows['desposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['desposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
					
				$result_Arr['noanswer']=$noanswerCount;
				$result_Arr['failed']=$failedCount;
				$result_Arr['busy']=$busyCount;
				$result_Arr['answered']=$answeredCount;
			}
			}else{
				$result_Arr['noanswer']=0;
				$result_Arr['failed']=0;
				$result_Arr['busy']=0;
				$result_Arr['answered']=0;
			}
			
			$to_fetch='*';
			$tablename='dialer_log';
			$where_cond="user_id='$user_id' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
			$noanswerCount=0;
			$failedCount=0;
			$busyCount=0;
			$answeredCount=0;
				while($rows=mysqli_fetch_array($results)){
			
				if($rows['disposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedCount = $failedCount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
					
				
			}
			$result_Arr['noanswer']=$result_Arr['noanswer']+$noanswerCount;
			$result_Arr['failed']=$result_Arr['failed']+$failedCount;
			$result_Arr['busy']=$result_Arr['busy']+$busyCount;
			$result_Arr['answered']=$result_Arr['answered']+$answeredCount;
			}
			
	  
	  return $result_Arr;
}

function getDataForlineChart()
{
	$to_fetch="HOUR(date_start) AS date_start";
	$tablename='call_information';
	$where_cond="DATE(date_start) = CURDATE()";
	$results=select_record($to_fetch,$where_cond,$tablename);
	$counts=get_num_rows($results);
	if($counts>0){
		while($rows=mysqli_fetch_array($results)){
			$date_start[] = $rows['date_start'];
		}
}
$date_start = array_count_values($date_start);

$dialer_start= array();
	 $to_fetch="HOUR(cdr_start_time) AS date_start";
			$tablename='dialer_log';
			$where_cond="DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				while($rows=mysqli_fetch_array($results)){
					$dialer_start[] = $rows['date_start'];
				}
}
$dialer_start = array_count_values($dialer_start);


for($i=0;$i<=23;$i++)
{
	if(empty($date_start[$i]))
	{
		$date_start[$i] = 0;
	}
	if(empty($dialer_start[$i]))
	{
		$dialer_start[$i] = 0;
	}
	$date_start[$i] = $date_start[$i] + $dialer_start[$i];
}

for($j=0;$j<=23;$j++)
{
	$abc[] = $date_start[$j];
}

 return $abc;
}


function getDataForlineHourly($value_arr)
{
	 
	 $to_fetch="HOUR(date_start) AS date_start,desposition,direction";
			$tablename='call_information';
			
			if($value_arr =="all")
			{
				$where_cond="DATE(date_start) = CURDATE()";
			}else{
				$value_arr=explode('*',$value_arr);
					$direction=strtolower($value_arr[0]);
					if($direction)
					{
						$directionSql = " and direction='$direction'";
					}

					$agent=$value_arr[1];
					if($agent)
					{
						$agentSql = "user_id='$agent'";
					}else{
					$agentSql = "user_id IS NOT NULL";
					}

					$disposition=$value_arr[2];
					if($disposition)
					{
						$dispositionSql = " and desposition='$disposition'";
					}
					$date=$value_arr[3];
					if($date)
					{
						$date=date_create($date);
						$date= date_format($date,"Y-m-d");
						$to = $date." 23:59:59";
						$dateSql = " and DATE(date_start) BETWEEN '$date' AND '$to'";
					}else{
						$dateSql = " and DATE(date_start) = CURDATE()";
					}
				$where_cond="$agentSql$directionSql$dispositionSql$dateSql";
			}
			
			
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$result_Arr['noanswercounter'][]=0;
				$result_Arr['failedCounter'][]=0;
				$result_Arr['busyCount'][]=0;
				$result_Arr['answeredCount'][]=0; 
				$result_Arr['inCount'][]=0; 
				$result_Arr['outCount'][]=0; 
				while($rows=mysqli_fetch_array($results)){
					$date_start[] = $rows['date_start'];
					if(trim($rows['date_start']) >= '0' && trim($rows['date_start']) < '1')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '1' && trim($rows['date_start']) < '2')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '2' && trim($rows['date_start']) < '3')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '3' && trim($rows['date_start']) < '4')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '4' && trim($rows['date_start']) < '5')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '5' && trim($rows['date_start']) < '6')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '6' && trim($rows['date_start']) < '7')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '7' && trim($rows['date_start']) < '8')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
						
					}
					if(trim($rows['date_start']) >= '8' && trim($rows['date_start']) < '9')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '9' && trim($rows['date_start']) < '10')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '10' && trim($rows['date_start']) < '11')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '11' && trim($rows['date_start']) < '12')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '12' && trim($rows['date_start']) < '13')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '13' && trim($rows['date_start']) < '14')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '14' && trim($rows['date_start']) < '15')
					{
						
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '15' && trim($rows['date_start']) < '16')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '16' && trim($rows['date_start']) < '17')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '17' && trim($rows['date_start']) < '18')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '18' && trim($rows['date_start']) < '19')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '19' && trim($rows['date_start']) < '20')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '20' && trim($rows['date_start']) < '21')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '21' && trim($rows['date_start']) < '22')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '22' && trim($rows['date_start']) < '23')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '23' && trim($rows['date_start']) < '24')
					{
						if($rows['desposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['desposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "inbound")
						{
							$result_Arr['inCount'][$rows['date_start']] = $result_Arr['inCount'][$rows['date_start']]+1;
						}
						if($rows['direction'] == "outbound")
						{
							$result_Arr['outCount'][$rows['date_start']] = $result_Arr['outCount'][$rows['date_start']]+1;
						}
					}
					
					$result_Arr['hours']=$date_start;
				
				}
				
}

 return $result_Arr;
}

function getDataForlineChartConditionWise($value_arr)
{
	 $value_arr=explode('*',$value_arr);
	 $directionSql="";
	$agentSql="";
	
	$direction=strtolower($value_arr[0]);
	if($direction)
	{
		$directionSql = " and direction='$direction'";
	}

	$agent=$value_arr[1];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$disposition=$value_arr[2];
	if($disposition)
	{
		$dispositionSql = " and desposition='$disposition'";
	}
	
	$date=$value_arr[3];
	if($date)
	{
		$date=date_create($date);
		$date= date_format($date,"Y-m-d");
		$to = $date." 23:59:59";
		$dateSql = " and DATE(date_start) BETWEEN '$date' AND '$to'";
	}else{
		$dateSql = " and DATE(date_start) = CURDATE()";
	}

			
			$to_fetch="HOUR(date_start) AS date_start";
			$tablename='call_information';
			$where_cond="$agentSql$dispositionSql$directionSql$dateSql";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				while($rows=mysqli_fetch_array($results)){
					$date_start[] = $rows['date_start'];
				}
}
$date_start = array_count_values($date_start);

for($i=0;$i<=23;$i++)
{
	if(empty($date_start[$i]))
	{
		$date_start[$i] = 0;
	}
}

for($j=0;$j<=23;$j++)
{
	$abc[] = $date_start[$j];
}

 return $abc;
}


function getDataForlineChartUserWise($user_id)
{
	 
	 $to_fetch="HOUR(date_start) AS date_start";
			$tablename='call_information';
			$where_cond="user_id='$user_id' and DATE(date_start) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				while($rows=mysqli_fetch_array($results)){
					$date_start[] = $rows['date_start'];
				}
}
$date_start = array_count_values($date_start);


$dialer_start= array();
	 $to_fetch="HOUR(cdr_start_time) AS date_start";
			$tablename='dialer_log';
			$where_cond="user_id='$user_id' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				while($rows=mysqli_fetch_array($results)){
					$dialer_start[] = $rows['date_start'];
				}
}
$dialer_start = array_count_values($dialer_start);

for($i=0;$i<=23;$i++)
{
	if(empty($date_start[$i]))
	{
		$date_start[$i] = 0;
	}
	if(empty($dialer_start[$i]))
	{
		$dialer_start[$i] = 0;
	}
	$date_start[$i] = $date_start[$i] + $dialer_start[$i];
}

for($j=0;$j<=23;$j++)
{
	$abc[] = $date_start[$j];
}

 return $abc;
}

function call($method, $parameters, $url)
{
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$json = json_encode($parameters);
$postArgs = array(
    'method' => $method,
    'input_type' => 'JSON',
    'response_type' => 'JSON',
    'rest_data' => $json,
    );
curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs);
$response = curl_exec($curl);
$result = json_decode($response);
return $result;
}

function call_vtiger($url, $params, $type = "GET") {
    $is_post = 0;
    if ($type == "POST") {
        $is_post = 1;
        $post_data = $params;
    } else {
        $url = $url . "?" . http_build_query($params);
    }
    $ch = curl_init($url);
    if (!$ch) {
        die("Cannot allocate a new PHP-CURL handle");
    }
    if ($is_post) {
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);

    $return = null;
    if (curl_error($ch)) {
        $return = false;
    } else {
        $return = json_decode($data, true);
    }

    curl_close($ch);

    return $return;
}

function findValuesFromObjectName($Object,$toFind)
{
$dataToReturn="";
	foreach($Object as $item) {
	//print_r($item);
	
	if($item->val === $toFind) {
		$dataToReturn = $item->content;
		break;
	}else
	{
		$dataToReturn = "";
	}
	
	}
	return $dataToReturn;
}


function getAllQueueStatus()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="1 order by number ASC";
	//DATE(date_start) = CURDATE()
	$tablename="queue_info";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$holdtime = 0;
	$available = 0;
	$talktime = 0;
	$loggedin=0;
	$callers=0;
	$abandoned=0;
	$max=0;
	$completed=0;
	//$weight=0;

	
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$abandoned = $abandoned+$row['abandoned'];
				$callers = $callers+$row['callers'];
				$holdtime = $holdtime+$row['holdtime'];
				$available = $available+$row['available'];
				$talktime = $talktime+$row['talktime'];
				$loggedin = $loggedin+$row['loggedin'];
				$completed = $completed+$row['completed'];
				$max = $max+$row['max'];
			}
			
			$talktime =round($talktime/$count,2);
			$holdtime =round($holdtime/$count,2);
			
			$result_Arr['abandoned']=$abandoned;
			$result_Arr['callers']=$callers;
			$result_Arr['holdtime']=$holdtime;
			$result_Arr['available']=$available;
			$result_Arr['talktime']=$talktime;
			$result_Arr['loggedin']=$loggedin;
			$result_Arr['completed']=$completed;
			$result_Arr['max']=$max;
			$result_Arr['count']=$count;
	
			/* $result_Arr['failedCount']=$failedCount; */
			
	  }else{
			$result_Arr['abandoned']=$abandoned;
			$result_Arr['callers']=$callers;
			$result_Arr['holdtime']=$holdtime;
			$result_Arr['available']=$available;
			$result_Arr['talktime']=$talktime;
			$result_Arr['loggedin']=$loggedin;
			$result_Arr['completed']=$completed;
			$result_Arr['count']=$count;
			$result_Arr['max']=$max;
	
$result_Arr['duration']=0;
	  }
	  return $result_Arr;
}

function getAllQueue()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	$tablename="queue_info";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				$queue[]=$row['number'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			$result_Arr['queue']=$queue;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			$result_Arr['queue']='';
	  }
	  return $result_Arr;
}

function getQueueInfo($queue)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "queue='$queue'";
	$tablename="queue_member_info";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$dataMember[]=$row;
			} 
			$result_Arr['QueueMember']=$dataMember;
	  }else{
			$result_Arr['QueueMember']='';
	  }
	  return $result_Arr;
}

function getQueueStatus($queue)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond="number='$queue'";
	//DATE(date_start) = CURDATE()
	$tablename="queue_info";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$holdtime = 0;
	$available = 0;
	$talktime = 0;
	$loggedin=0;
	$callers=0;
	$abandoned=0;
	$max=0;
	$completed=0;
	$weight=0;

	
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$abandoned = $abandoned+$row['abandoned'];
				$callers = $callers+$row['callers'];
				$holdtime = $holdtime+$row['holdtime'];
				$available = $available+$row['available'];
				$talktime = $talktime+$row['talktime'];
				$loggedin = $loggedin+$row['loggedin'];
				$completed = $completed+$row['completed'];
				$max = $max+$row['max'];
				$weight = $weight+$row['weight'];
				$strategy = $row['strategy'];
				$service_level = $row['service_level'];
				$service_percentage = $row['service_percentage'];
				$longestht = $row['longestht'];
			}
			
			$result_Arr['abandoned']=$abandoned;
			$result_Arr['callers']=$callers;
			$result_Arr['holdtime']=$holdtime;
			$result_Arr['available']=$available;
			$result_Arr['talktime']=$talktime;
			$result_Arr['loggedin']=$loggedin;
			$result_Arr['completed']=$completed;
			$result_Arr['max']=$max;
			$result_Arr['weight']=$weight;
			$result_Arr['strategy']=$strategy;
			$result_Arr['service_level']=$service_level;
			$result_Arr['service_percentage']=$service_percentage;
			$result_Arr['longestht']=$longestht;
			/* $result_Arr['failedCount']=$failedCount; */
			
	  }else{
			$result_Arr['abandoned']=$abandoned;
			$result_Arr['callers']=$callers;
			$result_Arr['holdtime']=$holdtime;
			$result_Arr['available']=$available;
			$result_Arr['talktime']=$talktime;
			$result_Arr['loggedin']=$loggedin;
			$result_Arr['completed']=$completed;
			$result_Arr['weight']=$weight;
			$result_Arr['max']=$max;
$result_Arr['duration']=0;
	  }
	  return $result_Arr;
}

function removeQueueMember($queue)
{
	 
	$result_Arr=array();
	$tablename="queue_member_info";
	$condition="queue='$queue'";
	$result=delete_record($condition,$tablename);
	return $result;
}


function removeConferenceInDB($conference)
{
	 
	$result_Arr=array();
	$tablename="dialer_conference";
	$condition="conference='$conference'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function removeUser($id)
{
	$tablename="user";
	$condition="user_id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function removeAsterisk($id)
{
	$tablename="asterisk_ip";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function removeCRM($id)
{
	$tablename="crm_configuration";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;
}

function removeVoiceCampaign($id)
{
	$tablename="voice_broadcasting";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	
	
	
	$tablename="voice_broadcasting_list";
	$condition="voice_broadcast_id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;
}


function removeDialerCampaign($id)
{
	$tablename="dialer";
	$condition="id='$id'";
	$result=delete_record($condition,$tablename);
	
	$tablename="dialer_agent_group";
	$condition="campaign_id='$id'";
	$result=delete_record($condition,$tablename);
	
	
	
	$tablename="dialer_list";
	$condition="campaign_id='$id'";
	$result=delete_record($condition,$tablename);
	return $result;
}



function removeAllQueue()
{
	 
	$result_Arr=array();
	$tablename="queue_info";
	$condition="1";
	$result=delete_record($condition,$tablename);
	return $result;
}

function removeQueueMemberUsingInterface($queue,$Interface)
{
	 
	$result_Arr=array();
	$tablename="queue_member_info";
	$condition="queue='$queue' and location='$Interface'";
	$result=delete_record($condition,$tablename);
	return $result;
}


function updateAddAvailableMember($queue)
{
	 
	$result_Arr=array();
	
	
	 $to_fetch='*';
	  $tablename="queue_info";
	  $where_cond="number='$queue'";
	  $available_in_db="";
	  $loggedin_in_db="";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$available_in_db=$row['available'];
				$loggedin_in_db=$row['loggedin'];
			}
				$available = $available_in_db + 1;
				$loggedin = $loggedin_in_db + 1;
				$condition="number='$queue'";
				$fields="available='$available',loggedin='$loggedin'";
				$result=update_record($fields,$condition,$tablename);
				$result_Arr['status'] = $result; 
		
			
	  }else{
			
	  }
	return $result_Arr;
}

function updateSubAvailableMember($queue)
{
	 
	$result_Arr=array();
	 $to_fetch='*';
	  $tablename="queue_info";
	  $where_cond="number='$queue'";
	  $available_in_db="";
	  $loggedin_in_db="";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$available_in_db=$row['available'];
				$loggedin_in_db=$row['loggedin'];
				
			}
			if($available_in_db > 0)
			{
				$available = $available_in_db - 1;
				$condition="number='$queue'";
				$fields="available='$available'";
				$result=update_record($fields,$condition,$tablename);
				$result_Arr['status'] = $result; 
			}
			if($loggedin_in_db > 0)
			{
				$loggedin = $loggedin_in_db - 1;
				$condition="number='$queue'";
				$fields="loggedin='$loggedin'";
				$result=update_record($fields,$condition,$tablename);
				$result_Arr['status'] = $result; 
			}
			
	  }else{
			
	  }
	return $result_Arr;
}
/* function createLivePickUpCallByMember($queue,$caller,$IP,$uniqueID,$member_number,$member_name,$holtime,$ringtime)
{
	//($queue,$max,$aboned,$weight,$completed,$strategy)
	 
	$result_Arr=array();  

	$id="";
	$to_fetch="*";
	$tablename='live_queue_member_pickup_call';
	$where_cond="queue='$queue' and member_number='$member_number'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}
	if(!$id)
	{
		$fields='`queue`, `caller`, `pbx_ip`, `unique_id`, `member_number`, `member_name`, `holtime`, `ringtime`, `date_time`';
		$value="'$queue','$caller','$IP','$uniqueID','$member_number','$member_name','$holtime','$ringtime',NOW()";
		$result=insert_record($fields,$value,$tablename);
		
	}
	else{
		$condition="id='$id'";
		$fields="caller='$caller',date_time=NOW(),pbx_ip='$IP',unique_id='$uniqueID',member_name='$member_name',holtime='$holtime',ringtime='$ringtime'";
		$result=update_record($fields,$condition,$tablename);
	}
	return "success";
} */

function changeQueueMemberStatusUsingInterfaceRing($queue,$interface)
{
	 
	//$status=array();
	//$id=array();
	$to_fetch="*";
	$queueMemberId='';
	$tablename='queue_member_info';
	$where_cond="queue='$queue' and location='$interface' and status='NOT_INUSE'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		$id=$row['id'];
	}

		$fields="status='RINGING'";
		$condition="id='$id'";
		$result=update_record($fields,$condition,$tablename);
	
	return 'success';
}
function queueMemberStatusPause($queue,$status,$ip,$message,$Interface)
{
	$tablename='queue_member_info';
	if($status == "true")
	{
		$pause_reason="By TechExtension Admin Panel";
	}else
	{
		$pause_reason="";
	}
	
	$condition="queue='$queue' and location='$Interface'";
	$fields="pause='$status',pause_reason='$pause_reason'";
	$result=update_record($fields,$condition,$tablename);
}

function getAllDistictChannel()
{
	 
	$to_fetch="DISTINCT channel";
	$queueMemberId='';
	$tablename='user';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	while($row=mysqli_fetch_array($result)){
		if($row['channel'])
		{
			$channel[]=$row['channel'];
		}
	}
	return $channel;
}


function checkChannelForConference($conference)
{
	 
	$to_fetch="*";
	$queueMemberId='';
	$tablename='dialer_conference';
	$where_cond="conference='$conference'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count > 0){
	while($row=mysqli_fetch_array($result)){
		$channel=$row['channel'];
	}
	
	$result_Arr['count']=$count;
	$result_Arr['channel']=$channel;
	
	}else{
	$result_Arr['count']=$count;
	$result_Arr['channel']='';
	}
	return $result_Arr;
}


function getConferenceChannel($conference,$campaign_id)
{
	 
	$to_fetch="*";
	
	$tablename='dialer_conference';
	$where_cond="conference='$conference' and campaign_id='$campaign_id' and channel !=''";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	if($count > 0){
	while($row=mysqli_fetch_array($result)){
		$channel=$row['channel'];
	}
	
	$result_Arr['count']=$count;
	$result_Arr['channel']=$channel;
	
	}else{
	$result_Arr['count']=$count;
	$result_Arr['channel']='';
	}
	return $result_Arr;
}




function createQueueSummaryAnswer($queue,$caller,$status,$uniqiuw,$channel,$member_number,$member_name,$holtime,$ringtime,$IP,$interface)
{
	//$queue,$caller,$IP,$uniqueID,$member_number,$member_name,$holtime,$ringtime
	 
	$result_Arr=array(); 
	$tablename= "all_time_queue_summary";
	$to_fetch='*';
	$where_cond="queue='$queue' and interface='$interface' and unique_id='$uniqiuw'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
	 }
	 if(!$id)
	 {
		$fields='`queue`, `callerid_number`, `pbx`, `unique_id`, `member_number`, `member_name`, `holdtime`, `ringtime`, `date_time`, `call_status`, `interface`,`counter`';
		$value="'$queue','$caller','$IP','$uniqiuw','$member_number','$member_name','$holtime','$ringtime',NOW(),'$status','$interface','0'";
		$result=insert_record($fields,$value,$tablename);
	 }else
	 {
		$condition="id='$id'";
		$fields="callerid_number='$caller', member_number='$member_number',member_name='$member_name',holdtime='$holtime',ringtime='$ringtime',call_status='$status',pbx='$IP'";
		$result=update_record($fields,$condition,$tablename);
	 }
	 return $result;
}


function createQueueSummaryNoAnswer($queue,$caller,$status,$uniqiuw,$channel,$member_number,$membername,$ringtime,$IP,$interface)
{
	//$queue,$caller,$IP,$uniqueID,$member_number,$member_name,$holtime,$ringtime
	 
	$result_Arr=array(); 
	$tablename= "all_time_queue_summary";
	$to_fetch='*';
	$where_cond="queue='$queue' and interface='$interface' and unique_id='$uniqiuw'";
	$counter="";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
				$counter=$row['counter'];
	 }
	 if(!$id)
	 {
		$fields='`queue`, `callerid_number`, `pbx`, `unique_id`, `member_number`, `member_name`, `ringtime`, `date_time`, `call_status`, `interface`,`counter`,`cdr_start_time`,`cdr_end_time`,`disposition`';
		$value="'$queue','$caller','$IP','$uniqiuw','$member_number','$membername','$ringtime',NOW(),'$status','$interface','1',NOW(),NOW(),'$status'";
		$result=insert_record($fields,$value,$tablename);
	 }else
	 {
		$counter = $counter + 1;
		$condition="id='$id'";
		$fields="callerid_number='$caller', member_number='$member_number',member_name='$membername',ringtime='$ringtime',call_status='$status',counter='$counter'";
		$result=update_record($fields,$condition,$tablename);
	 }
	 return $result;
}




function createQueueSummaryAnswerComplete($queue,$status,$uniqueID,$reason,$interface,$talktime)
{
	//$queue,$caller,$IP,$uniqueID,$member_number,$member_name,$holtime,$ringtime
	 
	$result_Arr=array(); 
	$tablename= "all_time_queue_summary";
	$to_fetch='*';
	$where_cond="queue='$queue' and interface='$interface' and unique_id='$uniqueID'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
	 }
	 if(!$id)
	 {
		//$fields='`queue`, `callerid_number`, `pbx`, `unique_id`, `member_number`, `member_name`, `holdtime`, `ringtime`, `date_time`, `call_status`, `interface`,`counter`';
		//$value="'$queue','$caller','$IP','$uniqueID','$member_number','$member_name','$holtime','$ringtime',NOW(),'$status','$interface','0'";
		//$result=insert_record($fields,$value,$tablename);
	 }else
	 {
		$condition="id='$id'";
		$fields="talktime='$talktime', reason='$reason',call_status='$status'";
		$result=update_record($fields,$condition,$tablename);
	 }
	 return $result;
}

function createQueueSummaryCDR($queue,$ip,$Interface,$st,$et,$at,$dispostion,$unique_id,$duration,$billable,$status,$recordlink)
{
	//$queue,$caller,$IP,$uniqueID,$member_number,$member_name,$holtime,$ringtime
	 
	$result_Arr=array(); 
	$tablename= "all_time_queue_summary";
	$to_fetch='*';
	$where_cond="queue='$queue' and interface like '$Interface%' and unique_id='$unique_id' order by date_time desc";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
	 }
	 if(!$id)
	 {
		//$fields='`queue`, `callerid_number`, `pbx`, `unique_id`, `member_number`, `member_name`, `holdtime`, `ringtime`, `date_time`, `call_status`, `interface`,`counter`';
		//$value="'$queue','$caller','$IP','$uniqueID','$member_number','$member_name','$holtime','$ringtime',NOW(),'$status','$interface','0'";
		//$result=insert_record($fields,$value,$tablename);
	 }else
	 {
		 
		 $et=date("Y-m-d H:i:s",strtotime($et));
		$st=date("Y-m-d H:i:s",strtotime($st));
		$at=date("Y-m-d H:i:s",strtotime($at));
		 //STR_TO_DATE('$at', '%Y-%m-%d %r')
		$condition="id='$id'";
		$fields="`cdr_start_time`='$st', `cdr_end_time`='$et',`cdr_answer_time`='$at',`billableseconds`='$billable',`duration`='$duration',`disposition`='$dispostion',`call_status`='$status',`recording_url`='$recordlink'";
		$result=update_record($fields,$condition,$tablename);
	 }
	 return $result;
}


function createQueueSummaryAboned($queue,$ip,$caller,$holdtime,$unique,$status)
{
	
	 
	$result_Arr=array(); 
	$tablename= "all_time_queue_summary";
	$to_fetch='*';
	$where_cond="queue='$queue' and unique_id='$unique' order by date_time desc";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$id=$row['id'];
	 }
	 if(!$id)
	 {
		$fields='`queue`, `pbx`, `unique_id`, `callerid_number`, `holdtime`, `disposition`, `call_status`, `date_time`,`counter`,`cdr_start_time`,`cdr_answer_time`,`cdr_end_time`,`billableseconds`,`duration`';
		$value="'$queue','$ip','$unique','$caller','$holdtime','ABONED','$status',NOW(),'0',NOW(),NOW(),NOW(),'0','0'";
		$result=insert_record($fields,$value,$tablename);
	 }
	 return $result;
}


function getAllQueueMember()
{
	 
	$result_Arr=array(); 
	$tablename= "all_time_queue_summary";
	$to_fetch='distinct(`member_name`)';
	$where_cond="1 and member_number != ''";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$QueueMemberdata[]=$row;
	 }
	$data['member_name'] = $QueueMemberdata;
	
	$to_fetch='distinct(`queue`)';
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$Queuedata[]=$row;
	 }
	$data['queue'] = $Queuedata;
	
	$where_cond="1";
	$to_fetch='distinct(`disposition`)';
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$Dispositiondata[]=$row;
	 }
	$data['disposition'] = $Dispositiondata;
	
	
	 return $data;
}

function getAllLastApplication()
{
	 
	$result_Arr=array(); 
	$tablename= "call_information";
	$to_fetch='distinct(`application_data`)';
	$where_cond="1 and application_data != ''";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
				$lastapplicationData[]=$row;
	 }
	$data['last_application'] = $lastapplicationData;
	 return $data;
}


function getAgentDialerID($agent_id)
{
	$result_Arr=array(); 
	$tablename= "dialer_log";
	$to_fetch='distinct(`campaign_id`)';
	$where_cond="user_id='$agent_id'";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
		$campaign_id[]=$row;
	 }
	$data['count'] = $count;
	$data['data'] = $campaign_id;
	return $data;
}


function getDistinctDialerDisposition()
{
	$result_Arr=array(); 
	$tablename= "dialer_log";
	$to_fetch='distinct(`agent_disposition`)';
	$where_cond="1";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	 while($row=mysqli_fetch_array($result)){
		$agent_disposition[]=$row;
	 }
	$data['count'] = $count;
	$data['data'] = $agent_disposition;
	return $data;
}


function searchBreakOfUser($fromDate,$toDate,$user_id)
{
	$directionSql="";
	$agentSql="";
	
	$to_fetch="*";
	if($fromDate)
	{
		$fromDate=date_create($fromDate);
		$fromDate= date_format($fromDate,"Y/m/d H:i:s");
		
		$toDate=date_create($toDate);
		$toDate= date_format($toDate,"Y/m/d H:i:s");
		$dateBetween = " and date_time BETWEEN '$fromDate' AND '$toDate' order by date_time DESC";
	}else{
		$dateBetween="";
	}
	
	if($user_id !== "all")
	{
		$agentSql = "user_id='$user_id'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	

	$where_cond = "$agentSql$dateBetween";
	$tablename="user_pause_queue";

	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=$where_cond;
			$result_Arr['data']="";
	  }
	  return $result_Arr;
}

function getBreakOfUser($user_id)
{
	$result_Arr=array();
	$to_fetch="*";
	if($user_id == "all"){
		$where_cond = "user_id is not null and DATE(date_time) = CURDATE() order by date_time DESC";
	}else{
		$where_cond = "user_id='$user_id' and DATE(date_time) = CURDATE() order by date_time DESC";
	}
	$tablename="user_pause_queue";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}




function getAllUserReadyToPickCalls()
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "readytopickcall='1'";
	$tablename="user";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row['extension'];
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
	  $result_Arr['count']=$count;
	  $result_Arr['data']="";
	  }
	  return $result_Arr;
}

function getAllQueueCallDetails()
{
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "DATE(cdr_start_time) = CURDATE() order by cdr_start_time DESC";
	//DATE( date_start ) = CURDATE( ) 
	$tablename="all_time_queue_summary";

	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
$result_Arr['count']=$count;
$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=0;
			$result_Arr['data']='';
			
	  }
	  return $result_Arr;
}








function searchQueueCalls($fromDate,$toDate,$queue,$agent,$disposition)
{
//Updated Function for getAllCallsDetailsConditionWise
	$queueSql="";
	$agentSql="";
	
	$to_fetch="*";
	if($fromDate)
	{
		$fromDate=date_create($fromDate);
		$fromDate= date_format($fromDate,"Y/m/d H:i:s");
		
		$toDate=date_create($toDate);
		$toDate= date_format($toDate,"Y/m/d H:i:s");
		$dateBetween = " and cdr_start_time BETWEEN '$fromDate' AND '$toDate' order by cdr_start_time DESC";
	}else{
	//all time calls
		$dateBetween = "";
	}
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}else{
		$queueSql = " and queue IS NOT NULL";
	}

	if($agent)
	{
		$agentSql = "member_name='$agent'";
	}else{
		$agentSql = "member_name IS NOT NULL";
	}
	
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}else{
		$dispositionSql = " and disposition IS NOT NULL";
	}
	$where_cond = "$agentSql$dispositionSql$queueSql$dateBetween";
	$tablename="all_time_queue_summary";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']=$where_cond;
	  }
	  return $result_Arr;
}


function getQueueCallBySearch($value_arr)
{
	 
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}else
	{
		$dateBetween = " and DATE(cdr_start_time) = CURDATE()";
	}
	
	$queue=strtolower($value_arr[2]);
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "member_name='$agent'";
	}else{
		$agentSql = "member_name IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}
	
	$missedCounter=0;
	$receivedCounter=0;
	$failedCounter=0;
	$to_fetch="*";
	$where_cond = "$agentSql$dispositionSql$queueSql$dateBetween";
	$tablename="all_time_queue_summary";
	$result=select_record($to_fetch,$where_cond,$tablename);
 	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			
	  }
	  return $result_Arr;
}

function getQueueCallDetailsFromCallId($call_id)
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "id='$call_id'";
	$tablename="all_time_queue_summary";
	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
	  }
	  return $result_Arr;
}



function getDataForPieChartQueue()
{
 

	
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="DATE(cdr_start_time) = CURDATE() and member_number != ''";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
			$noanswerCount=0;
			$failedCount=0;
			$busyCount=0;
			$answeredCount=0;
				
				while($rows=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			
				if($rows['disposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedCount = $failedCount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
					
				 $result_Arr['noanswer']/* [$value] */=$noanswerCount;
				$result_Arr['failed']/* [$value] */=$failedCount;
				$result_Arr['busy']/* [$value] */=$busyCount;
				$result_Arr['answered']/* [$value] */=$answeredCount;
			}
			} 
	  return $result_Arr;
}



function getDispositionAllQueueSummary()
{
	 
	$result_Arr=array();

		
		$to_fetch='distinct(`queue`)';
		$tablename='all_time_queue_summary';
		$where_cond="1";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $member_name[]=$row['queue'];
				$datas[]=$row['queue'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['queue']=$member_name;
	  }
	  
		foreach ($result_Arr_user['queue'] as $value) {
		$value =trim($value);
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="queue='$value' and disposition != 'ABONED' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				$abonedCount=0;
				$talktime=0;
				$holdtime=0;
				$holdtimeCounter=0;
				$talktimeCounter=0;
				while($row=mysqli_fetch_array($results)){
				
				if(!$row['holdtime'])
				{
					
				}else
				{
					$holdtimeCounter = $holdtimeCounter + 1;
					$holdtime = $holdtime + $row['holdtime'];
				}
				if(!$row['talktime'])
				{
					
				}else
				{
					$talktimeCounter = $talktimeCounter + 1;
					$talktime = $talktime + $row['talktime'];
				}
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
				}
			$result_Arr['noanswercounter'][$value]=$noanswercounter;
			$result_Arr['failedCounter'][$value]=$failedCounter;
			$result_Arr['busyCount'][$value]=$busyCount;
			$result_Arr['answeredCount'][$value]=$answeredCount;
			$result_Arr['abonedCounter'][$value]=$abonedCount;
			$result_Arr['talktime'][$value]=$talktime;
			$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime'][$value]=round($avgHoldTime,2);
			$avgtalktime= $talktime/$talktimeCounter;
			$result_Arr['avgtalktime'][$value]=round($avgtalktime,2);
			}
			}else
			{
				$result_Arr['noanswercounter'][$value]=0;
			$result_Arr['failedCounter'][$value]=0;
			$result_Arr['busyCount'][$value]=0;
			$result_Arr['answeredCount'][$value]=0;
			$result_Arr['abonedCounter'][$value]=0;
			$result_Arr['talktime'][$value]=0;
			//$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime'][$value]=0;
			//$avgtalktime= $talktime/$talktimeCounter;
			$result_Arr['avgtalktime'][$value]=0;
			}
		}
	  return $result_Arr;
}



function getAbonedCallsDetails()
{
	 
	$result_Arr=array();

		
		$to_fetch='distinct(`queue`)';
		$tablename='all_time_queue_summary';
		$where_cond="1";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $member_name[]=$row['queue'];
				$datas[]=$row['queue'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['queue']=$member_name;
	  }
	  
		foreach ($result_Arr_user['queue'] as $value) {
	$value =trim($value);
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="queue='$value' and holdtime != '' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$abonedCount=0;
				$holdtime=0;
				while($row=mysqli_fetch_array($results)){
				
				
				if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
					$holdtime = $holdtime + $row['holdtime'];
				}
			$avgHoldTime= $holdtime/$abonedCount;
			$result_Arr['holdtime'][$value]=round($avgHoldTime,2);
			$result_Arr['abonedCount'][$value]=round($abonedCount,2);
			
			$abonedRate= $abonedCount/$counts;
			
			$result_Arr['abonedRate'][$value]=round($abonedRate,2);
			}
			}else
			{
				$result_Arr['holdtime'][$value]=0;
			$result_Arr['abonedCount'][$value]=0;
			$result_Arr['abonedRate'][$value]=0;
			}
		}
	  return $result_Arr;
}


function getAgentDispositionQueueSummary()
{
	 
	$result_Arr=array();

		
		$to_fetch='distinct(`member_name`)';
		$tablename='all_time_queue_summary';
		$where_cond="1 and member_number != ''";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $member_name[]=$row['member_name'];
				$datas[]=$row['member_name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['member_name']=$member_name;
	  }
	  
		foreach ($result_Arr_user['member_name'] as $value) {
	$value =trim($value);
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_name='$value' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				$abonedCount=0;
				$talktime=0;
				$holdtime=0;
				$holdtimeCounter=0;
				$talktimeCounter=0;
				while($row=mysqli_fetch_array($results)){
				//$talktime = $talktime + $row['talktime'];
				if(!$row['holdtime'])
				{
					//$holdtime = 0 + $row['holdtime'];
				}else
				{
					$holdtimeCounter = $holdtimeCounter + 1;
					$holdtime = $holdtime + $row['holdtime'];
					
				}
				if(!$row['talktime'])
				{
					
				}else
				{
					$talktimeCounter = $talktimeCounter + 1;
					$talktime = $talktime + $row['talktime'];
				}
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				/* if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
				} */
			$result_Arr['noanswercounter'][$value]=$noanswercounter;
			$result_Arr['failedCounter'][$value]=$failedCounter;
			$result_Arr['busyCount'][$value]=$busyCount;
			$result_Arr['answeredCount'][$value]=$answeredCount;
			//$result_Arr['abonedCounter'][$value]=$abonedCount;
			$result_Arr['talktime'][$value]=$talktime;
			$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime'][$value]=round($avgHoldTime,2);
			$avgtalktime= $talktime/$talktimeCounter;
			$result_Arr['avgtalktime'][$value]=round($avgtalktime,2);
			}
			}else
			{
				$result_Arr['noanswercounter'][$value]=0;
			$result_Arr['failedCounter'][$value]=0;
			$result_Arr['busyCount'][$value]=0;
			$result_Arr['answeredCount'][$value]=0;
			//$result_Arr['abonedCounter'][$value]=$abonedCount;
			$result_Arr['talktime'][$value]=0;
			
			$result_Arr['holdtime'][$value]=0;
			
			$result_Arr['avgtalktime'][$value]=0;
			}
		}
	  return $result_Arr;
}


function getBarChartDataForQueue()
{
 
 $to_fetch='distinct(`member_name`)';
	  $tablename='all_time_queue_summary';
	  $where_cond="member_name != ''";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	   $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $member_name[]=$row['member_name'];
				$datas[]=$row['member_name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$datas;
	  }
	  
		foreach ($result_Arr_user['user_id'] as $value) {
	
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_name='$value' and DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$answercount=0;
				$failedcount=0;
				$notanswercount=0;
				$busycount=0;
				while($rows=mysqli_fetch_array($results)){
				if($rows['disposition'] == "ANSWERED")
				{
					$answercount = $answercount+1;
				}
				if($rows['disposition'] == "NO ANSWER")
				{
					$notanswercount = $notanswercount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedcount = $failedcount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busycount = $busycount+1;
				}
					
				$result_Arr['answercount'][$value]=$answercount;
				$result_Arr['notanswercount'][$value]=$notanswercount;
				$result_Arr['failedcount'][$value]=$failedcount;
				$result_Arr['busycount'][$value]=$busycount;
				$result_Arr['totalrecord'][$value]=$counts;
			}
			}	
		}
	  return $result_Arr;
}

function getDispositionAllQueueSummaryCondtion($value_arr)
{
	 
	$result_Arr=array();
$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}else
	{
		$dateBetween = " and DATE(cdr_start_time) = CURDATE()";
	}
	
	$queue=$value_arr[2];
	/* if($queue)
	{
		$directionSql = " and queue='$queue'";
	} */

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = " and member_name='$agent'";
	}else{
		$agentSql = " and member_name IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}
if(!$queue)
{
		
		$to_fetch='distinct(`queue`)';
		$tablename='all_time_queue_summary';
		$where_cond="1";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $member_name[]=$row['queue'];
				$datas[]=$row['queue'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['queue']=$member_name;
	  }
	  
		foreach ($result_Arr_user['queue'] as $value) {
		$value =trim($value);
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="queue='$value' $agentSql$dispositionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				$abonedCount=0;
				$talktime=0;
				$holdtime=0;
				$holdtimeCounter=0;
				$talktimeCounter=0;
			
			if($counts>0){
				
				while($row=mysqli_fetch_array($results)){
				if(!$row['holdtime'])
				{
				}else
				{
					$holdtimeCounter = $holdtimeCounter + 1;
					$holdtime = $holdtime + $row['holdtime'];
				}
				if(!$row['talktime'])
				{
				}else
				{
					$talktimeCounter = $talktimeCounter + 1;
					$talktime = $talktime + $row['talktime'];
				}
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
				}
			$result_Arr['noanswercounter'][$value]=$noanswercounter;
			$result_Arr['failedCounter'][$value]=$failedCounter;
			$result_Arr['busyCount'][$value]=$busyCount;
			$result_Arr['answeredCount'][$value]=$answeredCount;
			$result_Arr['abonedCounter'][$value]=$abonedCount;
			$result_Arr['talktime'][$value]=$talktime;
			if($holdtime == "0")
			{
				$avgHoldTime=0;
			}else
			{
				$avgHoldTime= $holdtime/$holdtimeCounter;
			}
			//$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime'][$value]=round($avgHoldTime,2);
			if($talktime == "0")
			{
				$avgtalktime=0;
			}else
			{
				$avgtalktime= $talktime/$talktimeCounter;
			}
			$result_Arr['avgtalktime'][$value]=round($avgtalktime,2);
			}
			
			}else
			{
				$result_Arr['noanswercounter'][$value]=0;
			$result_Arr['failedCounter'][$value]=0;
			$result_Arr['busyCount'][$value]=0;
			$result_Arr['answeredCount'][$value]=0;
			$result_Arr['abonedCounter'][$value]=0;
			$result_Arr['talktime'][$value]=0;
			//$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime'][$value]=0;
			//$avgtalktime= $talktime/$talktimeCounter;
			$result_Arr['avgtalktime'][$value]=0;
			}
		}
		}else{
			$result_Arr['user_name']=array($queue);
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="queue='$queue' $agentSql$dispositionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				$abonedCount=0;
				$talktime=0;
				$holdtime=0;
				$holdtimeCounter=0;
				$talktimeCounter=0;
				while($row=mysqli_fetch_array($results)){
				
				if(!$row['holdtime'])
				{
					
				}else
				{
					$holdtimeCounter = $holdtimeCounter + 1;
					$holdtime = $holdtime + $row['holdtime'];
				}
				if(!$row['talktime'])
				{
					
				}else
				{
					$talktimeCounter = $talktimeCounter + 1;
					$talktime = $talktime + $row['talktime'];
				}
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
				}
			$result_Arr['noanswercounter']=$noanswercounter;
			$result_Arr['failedCounter']=$failedCounter;
			$result_Arr['busyCount']=$busyCount;
			$result_Arr['answeredCount']=$answeredCount;
			$result_Arr['abonedCounter']=$abonedCount;
			$result_Arr['talktime']=$talktime;
			$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime']=round($avgHoldTime,2);
			$avgtalktime= $talktime/$talktimeCounter;
			$result_Arr['avgtalktime']=round($avgtalktime,2);
			}
			}else{
				$result_Arr['noanswercounter']=0;
			$result_Arr['failedCounter']=0;
			$result_Arr['busyCount']=0;
			$result_Arr['answeredCount']=0;
			$result_Arr['abonedCounter']=0;
			$result_Arr['talktime']=0;
			
			$result_Arr['holdtime']=0;
			
			$result_Arr['avgtalktime']=0;
			}
		}
	  return $result_Arr;
}







function getDataForPieChartConditionWiseQueue($value_arr)
{
 
	$result_Arr=array();
$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}else
	{
		$dateBetween = " and DATE(cdr_start_time) = CURDATE()";
	}
	
	$queue=$value_arr[2];
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}else{
		$queueSql = " and queue IS NOT NULL";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = " and member_name='$agent'";
	}else{
		$agentSql = " and member_name IS NOT NULL";
	}
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}
	
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_number != '' $agentSql$queueSql$dispositionSql$dateBetween";
			 $results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
			$noanswerCount=0;
			$failedCount=0;
			$busyCount=0;
			$answeredCount=0;
				
				while($rows=mysqli_fetch_array($results)){
			//	echo $rows['direction']."   ";
			
				if($rows['disposition'] == "NO ANSWER")
				{
					$noanswerCount = $noanswerCount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedCount = $failedCount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($rows['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
					
				 $result_Arr['noanswer']=$noanswerCount;
				$result_Arr['failed']=$failedCount;
				$result_Arr['busy']=$busyCount;
				$result_Arr['answered']=$answeredCount;
			}
			}
	  return $result_Arr;
}



function getBarChartDataForQueueCondtionWise($value_arr)
{
	
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}else
	{
		$dateBetween = " and DATE(cdr_start_time) = CURDATE()";
	}
	
	$queue=$value_arr[2];
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}else{
		$queueSql = " and queue IS NOT NULL";
	}

	$agent=$value_arr[3];
	/* if($agent)
	{
		$agentSql = " and member_name='$agent'";
	}else{
		$agentSql = " and member_name IS NOT NULL";
	} */
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}
 
	if(!$agent)
	{
	  $to_fetch='distinct(`member_name`)';
	  $tablename='all_time_queue_summary';
	  $where_cond="member_name != ''";
	  $result=select_record($to_fetch,$where_cond,$tablename);
	  $count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$member_name[]=$row['member_name'];
				$datas[]=$row['member_name'];
			}
			$result_Arr_user['count']=$count;
			$result_Arr['user_name']=$datas;
			$result_Arr_user['user_id']=$datas;

	
		foreach ($result_Arr_user['user_id'] as $value) {
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_name='$value' $queueSql$dispositionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$answercount=0;
				$failedcount=0;
				$notanswercount=0;
				$busycount=0;
				while($rows=mysqli_fetch_array($results)){
				if($rows['disposition'] == "ANSWERED")
				{
					$answercount = $answercount+1;
				}
				if($rows['disposition'] == "NO ANSWER")
				{
					$notanswercount = $notanswercount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedcount = $failedcount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busycount = $busycount+1;
				}
					
				$result_Arr['answercount'][$value]=$answercount;
				$result_Arr['notanswercount'][$value]=$notanswercount;
				$result_Arr['failedcount'][$value]=$failedcount;
				$result_Arr['busycount'][$value]=$busycount;
				$result_Arr['totalrecord'][$value]=$counts;
			}
			}else{
				$result_Arr['answercount'][$value]=0;
				$result_Arr['notanswercount'][$value]=0;
				$result_Arr['failedcount'][$value]=0;
				$result_Arr['busycount'][$value]=0;
				$result_Arr['totalrecord'][$value]=0;
			}	
		}
	  }
	}else
	{
		$result_Arr['user_name']=$agent;
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_name='$agent' $queueSql$dispositionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$answercount=0;
				$failedcount=0;
				$notanswercount=0;
				$busycount=0;
				while($rows=mysqli_fetch_array($results)){
				if($rows['disposition'] == "ANSWERED")
				{
					$answercount = $answercount+1;
				}
				if($rows['disposition'] == "NO ANSWER")
				{
					$notanswercount = $notanswercount+1;
				}
				if($rows['disposition'] == "FAILED")
				{
					$failedcount = $failedcount+1;
				}
				if($rows['disposition'] == "BUSY")
				{
					$busycount = $busycount+1;
				}
					
				$result_Arr['answercount'][$agent] =$answercount;
				$result_Arr['notanswercount'][$agent]=$notanswercount;
				$result_Arr['failedcount'][$agent]=$failedcount;
				$result_Arr['busycount'][$agent]=$busycount;
				$result_Arr['totalrecord'][$agent]=$counts;
			}
			}else{
				$result_Arr['answercount'][$value]=0;
				$result_Arr['notanswercount'][$value]=0;
				$result_Arr['failedcount'][$value]=0;
				$result_Arr['busycount'][$value]=0;
				$result_Arr['totalrecord'][$value]=0;
			}
	}
	  return $result_Arr;
}



function getAgentDispositionQueueSummaryConditionWise($value_arr)
{		
$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (cdr_start_time BETWEEN '$from' AND '$to')";
	}else
	{
		$dateBetween = " and DATE(cdr_start_time) = CURDATE()";
	}
	
	$queue=$value_arr[2];
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}else{
		$queueSql = " and queue IS NOT NULL";
	}

	$agent=$value_arr[3];
	/* if($agent)
	{
		$agentSql = " and member_name='$agent'";
	}else{
		$agentSql = " and member_name IS NOT NULL";
	} */
	
	$disposition=$value_arr[4];
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}
	if(!$agent)
	{
		$to_fetch='distinct(`member_name`)';
		$tablename='all_time_queue_summary';
		$where_cond="1 and member_number != ''";
		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				 $member_name[]=$row['member_name'];
				$datas[]=$row['member_name'];
				
			}
			$result_Arr_user['count']=$count;
			 $result_Arr['user_name']=$datas;
			$result_Arr_user['member_name']=$member_name;
	  }
	  
		foreach ($result_Arr_user['member_name'] as $value) {
			$value =trim($value);
			$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_name='$value' $queueSql$dispositionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				$abonedCount=0;
				$talktime=0;
				$holdtime=0;
				$holdtimeCounter=0;
				$talktimeCounter=0;
				while($row=mysqli_fetch_array($results)){
				//$talktime = $talktime + $row['talktime'];
				if(!$row['holdtime'])
				{
					//$holdtime = 0 + $row['holdtime'];
				}else
				{
					$holdtimeCounter = $holdtimeCounter + 1;
					$holdtime = $holdtime + $row['holdtime'];
					
				}
				if(!$row['talktime'])
				{
					
				}else
				{
					$talktimeCounter = $talktimeCounter + 1;
					$talktime = $talktime + $row['talktime'];
				}
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				/* if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
				} */
			$result_Arr['noanswercounter'][$value]=$noanswercounter;
			$result_Arr['failedCounter'][$value]=$failedCounter;
			$result_Arr['busyCount'][$value]=$busyCount;
			$result_Arr['answeredCount'][$value]=$answeredCount;
			//$result_Arr['abonedCounter'][$value]=$abonedCount;
			$result_Arr['talktime'][$value]=$talktime;
			if($talktime == "0")
			{
				$avgHoldTime=0;
			}else
			{
				$avgHoldTime= $holdtime/$holdtimeCounter;
			}
			$result_Arr['holdtime'][$value]=round($avgHoldTime,2);
			if($talktime == "0")
			{
				$avgtalktime=0;
			}else
			{
				$avgtalktime= $talktime/$talktimeCounter;	
			}
			
			$result_Arr['avgtalktime'][$value]=round($avgtalktime,2);
			}
			}else
			{
				$result_Arr['noanswercounter'][$value]=0;
				$result_Arr['failedCounter'][$value]=0;
				$result_Arr['busyCount'][$value]=0;
				$result_Arr['answeredCount'][$value]=0;
				$result_Arr['talktime'][$value]=0;
				$result_Arr['holdtime'][$value]=0;
				$result_Arr['avgtalktime'][$value]=0;
			}
		}
	}else
	{
		//
		$result_Arr['user_name']=$agent;
		$value=$agent;
		$to_fetch='*';
			$tablename='all_time_queue_summary';
			$where_cond="member_name='$agent' $queueSql$dispositionSql$dateBetween";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				$noanswercounter=0;
				$failedCounter=0;
				$busyCount=0;
				$answeredCount=0;
				$abonedCount=0;
				$talktime=0;
				$holdtime=0;
				$holdtimeCounter=0;
				$talktimeCounter=0;
				while($row=mysqli_fetch_array($results)){
				//$talktime = $talktime + $row['talktime'];
				if(!$row['holdtime'])
				{
					//$holdtime = 0 + $row['holdtime'];
				}else
				{
					$holdtimeCounter = $holdtimeCounter + 1;
					$holdtime = $holdtime + $row['holdtime'];
					
				}
				if(!$row['talktime'])
				{
					
				}else
				{
					$talktimeCounter = $talktimeCounter + 1;
					$talktime = $talktime + $row['talktime'];
				}
				if($row['disposition'] == "NO ANSWER")
				{
					$noanswercounter = $noanswercounter+1;
				}
				if($row['disposition'] == "FAILED")
				{
					$failedCounter = $failedCounter+1;
				}
				if($row['disposition'] == "BUSY")
				{
					$busyCount = $busyCount+1;
				}
				if($row['disposition'] == "ANSWERED")
				{
					$answeredCount = $answeredCount+1;
				}
				/* if($row['disposition'] == "ABONED")
				{
					$abonedCount = $abonedCount+1;
				} */
			$result_Arr['noanswercounter'][$value]=$noanswercounter;
			$result_Arr['failedCounter'][$value]=$failedCounter;
			$result_Arr['busyCount'][$value]=$busyCount;
			$result_Arr['answeredCount'][$value]=$answeredCount;
			//$result_Arr['abonedCounter'][$value]=$abonedCount;
			$result_Arr['talktime'][$value]=$talktime;
			$avgHoldTime= $holdtime/$holdtimeCounter;
			$result_Arr['holdtime'][$value]=round($avgHoldTime,2);
			$avgtalktime= $talktime/$talktimeCounter;
			$result_Arr['avgtalktime'][$value]=round($avgtalktime,2);
			}
			}
			else
			{
				$result_Arr['noanswercounter'][$value]=0;
				$result_Arr['failedCounter'][$value]=0;
				$result_Arr['busyCount'][$value]=0;
				$result_Arr['answeredCount'][$value]=0;
				$result_Arr['talktime'][$value]=0;
				$result_Arr['holdtime'][$value]=0;
				$result_Arr['avgtalktime'][$value]=0;
			}
	}
	  return $result_Arr;
}

	function createAgentLoginHistory($queue,$name,$interface,$ip,$login)
	{
	
	  $result_Arr=array();	
	
	$tablename='queue_memeber_login_history';
			$fields='`queue`, `member_name`, `interface`,`login` ,`date_time`,`ip`';
			$value="'$queue','$name','$interface','$login',NOW(),'$ip'";
			$result_max=insert_record($fields,$value,$tablename);
			
			 return $result_max;
	}

	function agentLoginLogoutDialer($conferencename,$conferencechannel,$status){
		$to_fetch="*";
		$where_cond = "conference='$conferencename'";
		$tablename="dialer_conference";

		$result=select_record($to_fetch,$where_cond,$tablename);
		$count=get_num_rows($result);
		if($count>0){
		while($row=mysqli_fetch_array($result)){
			$agent_id=$row['agent'];
			$campaign_id=$row['campaign_id'];
			$channel=$row['channel'];
		}
		
		$tablename='dialer_agent_login';
			$fields='`agent_id`, `campaign_id`, `channel`,`status` ,`date`';
			$value="'$agent_id','$campaign_id','$channel','$status',NOW()";
			$result=insert_record($fields,$value,$tablename);
			$result_Arr['result']=$result;
		}else{
			$result_Arr['result']=0;
		}
		return $result_Arr;
	}
function getAllMemberLoginHistory()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "DATE( date_time ) = CURDATE()";
	//DATE( date_time ) = CURDATE( ) 
	$tablename="queue_memeber_login_history";

	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
$result_Arr['count']=$count;
$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=0;
			$result_Arr['data']='';
			
	  }
	  return $result_Arr;
}



function getAllMemberPortalLoginHistory()
{
	 
	$result_Arr=array();
	$to_fetch="*";
	$where_cond = "1";
	//DATE( date_start ) = CURDATE( ) 
	$tablename="portal_login_history";

	$result=select_record($to_fetch,$where_cond,$tablename);
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
$result_Arr['count']=$count;
$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=0;
			$result_Arr['data']='';
			
	  }
	  return $result_Arr;
}



function getDialerAgentLoginHistory($fromDate,$toDate,$campaign_id,$agent_id)
{
	$result_Arr=array();
	$to_fetch="*";
	$dateBetween="";
	if($fromDate)
	{
		$from=date_create($fromDate);
		$from= date_format($from,"Y/m/d H:i:s");
		
		$to=date_create($toDate);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date BETWEEN '$from' AND '$to')";
	}else{
		//$dateBetween = " and and DATE( date ) = CURDATE( )";
	}
	
	if($agent_id)
	{
		$agentSql = "agent_id='$agent_id'";
	}else{
		$agentSql = "agent_id IS NOT NULL";
	}
	
	$where_cond = "campaign_id='$campaign_id' and $agentSql$dateBetween order by date DESC";
	$tablename="dialer_agent_login";
	$result=select_record($to_fetch,$where_cond,$tablename);
	
	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
	  }else{
			$result_Arr['count']=0;
			$result_Arr['data']='';
	  }
	 // $result_Arr['where_cond']=$where_cond;
	  return $result_Arr;
}



function getAllMemberLoginHistoryConditionWise($value_arr)
{
	 
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_time BETWEEN '$from' AND '$to')";
	}else
	{
		$dateBetween = " and DATE(date_time) = CURDATE()";
	}
	
	$queue=strtolower($value_arr[2]);
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}

	$agent=$value_arr[3];
	if($agent)
	{
		$agentSql = "member_name='$agent'";
	}else{
		$agentSql = "member_name IS NOT NULL";
	}
	
	$login=$value_arr[4];
	if($login)
	{
		$loginSql = " and login='$login'";
	}else if($login == "0")
	{
		$loginSql = " and login='0'";
	}
	
	$missedCounter=0;
	$receivedCounter=0;
	$failedCounter=0;
	$to_fetch="*";
	$where_cond = "$agentSql$loginSql$queueSql$dateBetween order by date_time DESC";
	$tablename="queue_memeber_login_history";
	$result=select_record($to_fetch,$where_cond,$tablename);
 	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			
	  }
	  return $result_Arr;
}




function getAllMemberPortalLoginHistoryConditionWise($value_arr)
{
	 
	$result_Arr=array();
	$value_arr=explode('*',$value_arr);
	$directionSql="";
	$agentSql="";
	
	//$from."*".$to."*".$direction."*"$agent;
	$dateBetween="";
	$from=$value_arr[0];
	if($from)
	{
		$from=date_create($from);
		$from= date_format($from,"Y/m/d H:i:s");
		$to=$value_arr[1];
		$to=date_create($to);
		$to= date_format($to,"Y/m/d H:i:s");
		$dateBetween = " and (date_time BETWEEN '$from' AND '$to')";
	}
	
	

	$agent=$value_arr[2];
	if($agent)
	{
		$agentSql = "user_id='$agent'";
	}else{
		$agentSql = "user_id IS NOT NULL";
	}
	
	$login=$value_arr[3];
	if($login)
	{
		$loginSql = " and login='$login'";
	}else if($login == "0")
	{
		$loginSql = " and login='0'";
	}
	
	$missedCounter=0;
	$receivedCounter=0;
	$failedCounter=0;
	$to_fetch="*";
	$where_cond = "$agentSql$loginSql$dateBetween order by date_time DESC";
	$tablename="portal_login_history";
	$result=select_record($to_fetch,$where_cond,$tablename);
 	$count=get_num_rows($result);
	  if($count>0){
			 while($row=mysqli_fetch_array($result)){
				$data[]=$row;
				
			} 
			$result_Arr['count']=$count;
			$result_Arr['data']=$data;
			
			
	  }else{
			$result_Arr['count']="0";
			$result_Arr['data']='';
			
	  }
	  return $result_Arr;
}


function getDataForQueuelineHourly($value_arr)
{
	 
	 $to_fetch="HOUR(cdr_start_time) AS date_start,disposition";
			$tablename='all_time_queue_summary';
			
			if($value_arr =="all")
			{
			$where_cond="DATE(cdr_start_time) = CURDATE()";
			}else{
				$value_arr=explode('*',$value_arr);
					$queue=strtolower($value_arr[0]);
					if($queue)
					{
						$queueSql = " and queue='$queue'";
					}

					$agent=trim($value_arr[1]);
					if($agent)
					{
						$agentSql = "member_name='$agent'";
					}else{
					$agentSql = "member_name IS NOT NULL";
					}

					$disposition=$value_arr[2];
					if($disposition)
					{
						$dispositionSql = " and disposition='$disposition'";
					}
					$date=$value_arr[3];
					if($date)
					{
						$date=date_create($date);
						$date= date_format($date,"Y-m-d");
						$to = $date." 23:59:59";
						$dateSql = " and DATE(cdr_start_time) BETWEEN '$date' AND '$to'";
					}else{
						$dateSql = " and DATE(cdr_start_time) = CURDATE()";
					}
					$where_cond="$agentSql$queueSql$dispositionSql$dateSql";
			}
			
			
			$results=select_record($to_fetch,$where_cond,$tablename);
			
			
			$counts=get_num_rows($results);
			if($counts>0){
				$result_Arr['noanswercounter'][]=0;
				$result_Arr['failedCounter'][]=0;
				$result_Arr['busyCount'][]=0;
				$result_Arr['answeredCount'][]=0; 
				$result_Arr['abonedCount'][]=0; 
				$result_Arr['outCount'][]=0; 
				while($rows=mysqli_fetch_array($results)){
					$date_start[] = $rows['date_start'];
					if(trim($rows['date_start']) >= '0' && trim($rows['date_start']) < '1')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
						
					}
					if(trim($rows['date_start']) >= '1' && trim($rows['date_start']) < '2')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
						
					}
					if(trim($rows['date_start']) >= '2' && trim($rows['date_start']) < '3')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '3' && trim($rows['date_start']) < '4')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '4' && trim($rows['date_start']) < '5')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '5' && trim($rows['date_start']) < '6')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '6' && trim($rows['date_start']) < '7')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '7' && trim($rows['date_start']) < '8')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '8' && trim($rows['date_start']) < '9')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '9' && trim($rows['date_start']) < '10')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '10' && trim($rows['date_start']) < '11')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '11' && trim($rows['date_start']) < '12')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '12' && trim($rows['date_start']) < '13')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '13' && trim($rows['date_start']) < '14')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '14' && trim($rows['date_start']) < '15')
					{
						
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '15' && trim($rows['date_start']) < '16')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '16' && trim($rows['date_start']) < '17')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '17' && trim($rows['date_start']) < '18')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '18' && trim($rows['date_start']) < '19')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '19' && trim($rows['date_start']) < '20')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '20' && trim($rows['date_start']) < '21')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '21' && trim($rows['date_start']) < '22')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '22' && trim($rows['date_start']) < '23')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					if(trim($rows['date_start']) >= '23' && trim($rows['date_start']) < '24')
					{
						if($rows['disposition'] == "NO ANSWER")
						{
							$result_Arr['noanswercounter'][$rows['date_start']] = $result_Arr['noanswercounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "FAILED")
						{
							$result_Arr['failedCounter'][$rows['date_start']] = $result_Arr['failedCounter'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "BUSY")
						{
							$result_Arr['busyCount'][$rows['date_start']] = $result_Arr['busyCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ANSWERED")
						{
							$result_Arr['answeredCount'][$rows['date_start']] = $result_Arr['answeredCount'][$rows['date_start']]+1;
						}
						if($rows['disposition'] == "ABONED")
						{
							$result_Arr['abonedCount'][$rows['date_start']] = $result_Arr['abonedCount'][$rows['date_start']]+1;
						}
					}
					
					$result_Arr['hours']=$date_start;
				
				}
				
}

 return $result_Arr;
}

function getDataForlineChartQueue()
{
	 
	 $to_fetch="HOUR(cdr_start_time) AS date_start";
			$tablename='all_time_queue_summary';
			$where_cond="DATE(cdr_start_time) = CURDATE()";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				while($rows=mysqli_fetch_array($results)){
					$date_start[] = $rows['date_start'];
				}
}
$date_start = array_count_values($date_start);

for($i=0;$i<=23;$i++)
{
	if(empty($date_start[$i]))
	{
		$date_start[$i] = 0;
	}
}

for($j=0;$j<=23;$j++)
{
	$abc[] = $date_start[$j];
}

 return $abc;
}


function getQueueDataForlineChartConditionWise($value_arr)
{
	
	 
	 
	 $value_arr=explode('*',$value_arr);
	 $directionSql="";
	$agentSql="";
	
	$queue=strtolower($value_arr[0]);
	if($queue)
	{
		$queueSql = " and queue='$queue'";
	}

	$agent=$value_arr[1];
	if($agent)
	{
		$agentSql = "member_name='$agent'";
	}else{
		$agentSql = "member_name IS NOT NULL";
	}
	
	$disposition=$value_arr[2];
	if($disposition)
	{
		$dispositionSql = " and disposition='$disposition'";
	}
	$date=$value_arr[3];
	if($date)
	{
		$date=date_create($date);
		$date= date_format($date,"Y-m-d");
		$to = $date." 23:59:59";
		$dateSql = " and DATE(cdr_start_time) BETWEEN '$date' AND '$to'";
	}else{
		$dateSql = " and DATE(cdr_start_time) = CURDATE()";
	}

			
			$to_fetch="HOUR(cdr_start_time) AS date_start";
			$tablename='all_time_queue_summary';
			$where_cond="$agentSql$dispositionSql$queueSql$dateSql";
			$results=select_record($to_fetch,$where_cond,$tablename);
			$counts=get_num_rows($results);
			if($counts>0){
				while($rows=mysqli_fetch_array($results)){
					$date_start[] = $rows['date_start'];
				}
}
$date_start = array_count_values($date_start);

for($i=0;$i<=23;$i++)
{
	if(empty($date_start[$i]))
	{
		$date_start[$i] = 0;
	}
}

for($j=0;$j<=23;$j++)
{
	$abc[] = $date_start[$j];
}

 return $abc;
}


function getUserCreateCRMURL($user_id,$moduleName,$user_type,$phone)
{
	
	if($user_type == "user")
	{
		$user_info = getUserInfoFromId($user_id);
		$crmType = $user_info['data'][0]['crm_type'];
		$crm_url = $user_info['data'][0]['crm_url'];
	}
	else
	{
		$user_info = getAdminProfile($user_id);
		$crmType = $user_info['data']['crm_type'];
		$crm_url = $user_info['data']['crm_url'];
	}
	
	if(trim($crmType) == "SugarCRM" || trim($crmType) == "SuiteCRM")
	{
		$createURL = "?module=".$moduleName."&action=EditView&mobile_number=".$phone;
	}
	if(trim($crmType) == "VtigerCRM")
	{
		if($moduleName == "Cases" || $moduleName == "Tasks")
		{
			$moduleName = "HelpDesk";
			$createURL = "/index.php?module=".$moduleName."&view=Edit&ticket_title=".$phone;
		}else if($moduleName == "Contacts")
		{
			$createURL = "/index.php?module=".$moduleName."&view=Edit&mobile=".$phone;
		}else{
			$createURL = "/index.php?module=".$moduleName."&view=Edit&phone=".$phone;
		}
		
	}
	if(trim($crmType) == "SalesForce Partner" || trim($crmType) == "SalesForce Enterprise")
	{
		if($moduleName == "Accounts")
		{
			$createURL = "/001/e?who_id=''";
		}else if($moduleName == "Contacts")
		{
			$createURL = "/003/e?who_id=''";
		}
		else if($moduleName == "Leads")
		{
			$createURL = "/00Q/e?who_id=''";
		}
		else if($moduleName == "Cases")
		{
			$createURL = "/500/e?who_id=''";
		}
		else if($moduleName == "Tasks")
		{
			$createURL = "/00T/e?who_id=''";
		}
		
	}
	
	if(trim($crmType) == "Microsoft Dynamics CRM")
	{
		if($moduleName == "Accounts")
		{
			$createURL = "/main.aspx?etc=1&extraqs=&newWindow=true&pagetype=entityrecord";
		}else if($moduleName == "Contacts")
		{
			$createURL = "/main.aspx?etc=2&extraqs=&newWindow=true&pagetype=entityrecord";
		}
		else if($moduleName == "Leads")
		{
			$createURL = "/main.aspx?etc=4&extraqs=&newWindow=true&pagetype=entityrecord";
		}
		else if($moduleName == "Cases")
		{
			$createURL = "/main.aspx?etc=112&extraqs=&newWindow=true&pagetype=entityrecord";
		}
		else if($moduleName == "Tasks")
		{
			$createURL = "/main.aspx?etc=4212&extraqs=&newWindow=true&pagetype=entityrecord";
		}
		
	}
	
	
	
	if(trim($crmType) == "Odoo")
	{
		if($moduleName == "Accounts")
		{
			$createURL = "/web#id=&action=118&model=res.partner&view_type=form&menu_id=94";
		}else if($moduleName == "Contacts")
		{
			$createURL = "/web#id=&action=118&model=res.partner&view_type=form&menu_id=94";
		}
		else if($moduleName == "Leads")
		{
			$createURL = "/web#id=&action=118&model=res.partner&view_type=form&menu_id=94";
		}
		else if($moduleName == "Cases")
		{
			$createURL = "/web#view_type=form&model=crm.lead&action=143";
		}
		else if($moduleName == "Tasks")
		{
			$createURL = "/web#view_type=form&model=crm.lead&action=143";
		}
	}
	
	if(trim($crmType) == "HubSpot")
	{
		$CRMINFO = getCRMNameFromURL($crm_url);
		$companyID = $CRMINFO['data']['ms_client_id'];
		if($moduleName == "Accounts")
		{
			$createURL = "contacts/$companyID/companies/list/view/all/";
		}else if($moduleName == "Contacts")
		{
			$createURL = "contacts/$companyID/contacts/list/view/all/";
		}
		else if($moduleName == "Leads")
		{
			$createURL = "contacts/$companyID/contacts/list/view/all/";
		}
		else if($moduleName == "Cases")
		{
			$createURL = "contacts/$companyID/tickets/list/view/all/";
		}
		else if($moduleName == "Tasks")
		{
			$createURL = "contacts/$companyID/tasks/list/view/all/";
		}
		
	}
	
	
	return $createURL;
}
function getUserCRMViewURL($user_id,$id,$moduleName,$user_type)
{
	if($user_type == "admin")
	{
		$user_info = getAdminInfoFromId($user_id);
		
	}else{
		$user_info = getUserInfoFromId($user_id);
		
	}
	$crm_url = $user_info['data'][0]['crm_url'];
	$crmType = $user_info['data'][0]['crm_type'];
	
	if(trim($crmType) == "SugarCRM" || trim($crmType) == "SuiteCRM")
	{
		$viewURL = "?module=".$moduleName."&action=DetailView&record=".$id;
	}
	if(trim($crmType) == "SalesForce Partner" || trim($crmType) == "SalesForce Enterprise")
	{
		$viewURL = "/".$id;
		
	}
	if(trim($crmType) == "VtigerCRM")
	{
		$arr = explode("x", $id);
		if($moduleName == "Cases" || $moduleName == "Tasks")
		{
			$moduleName = "HelpDesk";
		}
		$viewURL = "/index.php?module=".$moduleName."&view=Detail&record=".$arr[1];
		
	}
	if(trim($crmType) == "Microsoft Dynamics CRM")
	{
		if($moduleName == "Contacts")
		{
			$viewURL = "/main.aspx?etc=2&id=%7b".$id."%7d&newWindow=true&pagetype=entityrecord";
		}
		if($moduleName == "Accounts")
		{
			$viewURL = "/main.aspx?etc=1&id=%7b".$id."%7d&newWindow=true&pagetype=entityrecord";
		}
		if($moduleName == "Leads")
		{
			$viewURL = "/main.aspx?etc=4&id=%7b".$id."%7d&newWindow=true&pagetype=entityrecord";
		}
	}
	
	
	if(trim($crmType) == "Odoo")
	{
		if($moduleName == "Contacts")
		{
			$viewURL = "/web#id=".$id."&view_type=form&model=res.partner&action=118";
		}
		if($moduleName == "Accounts")
		{
			$viewURL = "/web#id=".$id."&view_type=form&model=res.partner&action=55";
		}
		if($moduleName == "Leads")
		{
			$viewURL = "/web#id=".$id."&view_type=form&model=res.partner&action=118";
		}
	}
	
	if(trim($crmType) == "HubSpot")
	{
		$CRMINFO = getCRMNameFromURL($crm_url);
		$companyID = $CRMINFO['data']['ms_client_id'];
		if($moduleName == "Contacts")
		{
			$viewURL = "contacts/$companyID/contact/$id/";
		}
		if($moduleName == "Accounts")
		{
			$viewURL = "contacts/$companyID/company/id/";
		}
		if($moduleName == "Leads")
		{
			$viewURL = "contacts/$companyID/contact/$id/";
		}
	}
	
	
	return $viewURL;
}
?>