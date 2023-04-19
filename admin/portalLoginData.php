<?php
include_once "../controller/route.php";

if(isset($_GET['from']))
{
	$from = $_GET['from'];
	$to = $_GET["to"];
}

if(isset($_GET["agent"]))
{
	$agent = $_GET["agent"];
}
if(isset($_GET["status"]))
{
	$status = $_GET["status"];
}

$dataToSend = $from."*".$to."*".$agent."*".$status;

$details = getAllMemberPortalLoginHistoryConditionWise($dataToSend);
?>
<tbody id="tableRow">
<?php
for($i=0;$i<$details['count'];$i++)
{
	if($details['data'][$i]['login'] == "1")
	{
		$details['data'][$i]['login'] = "Log In";
	}else
	{
		$details['data'][$i]['login'] = "Log Out";
	}
$userdata = getUserInfoFromId($details['data'][$i]['user_id']);
?>
<tr>
	<td><?php echo $userdata['data'][0]['name']; ?></td>
	<td><?php echo $details['data'][$i]['login']; ?></td>
	<td><?php echo $details['data'][$i]['date_time']; ?></td>

</tr>
<?php
}
?>
</tbody>
