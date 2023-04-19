<?php
include_once "../controller/route.php";

if(isset($_GET['from']))
{
	$from = $_GET['from'];
	$to = $_GET["to"];
}
if(isset($_GET["queue"]))
{
	$queue = $_GET["queue"];
}
if(isset($_GET["agent"]))
{
	$agent = $_GET["agent"];
}
if(isset($_GET["status"]))
{
	$status = $_GET["status"];
}

$dataToSend = $from."*".$to."*".$queue."*".$agent."*".$status;

$details = getAllMemberLoginHistoryConditionWise($dataToSend);

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
?>
<tr>
	<td><?php echo $details['data'][$i]['queue']; ?></td>
	<td><?php echo $details['data'][$i]['member_name']; ?></td>
	<td><?php echo $details['data'][$i]['interface']; ?></td>
	<td><?php echo $details['data'][$i]['login']; ?></td>
	<td><?php echo $details['data'][$i]['ip']; ?></td>
	
	<td><?php echo $details['data'][$i]['date_time']; ?></td>

</tr>
<?php
}
?>
</tbody>
