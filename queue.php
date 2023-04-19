<?php include "header.php" ?>
<?php include "reports_left_sidebar.php" ?>
<script>
$(window).load(function() {
$(".row").addClass("opacity");
});

QueueRefreshId = setInterval(function(){getSummaryQueue()}, 1000);
function getSummaryQueue()
{
	var stringQueue="";
	var stringQueueLiveCalls="";
	$.ajax({url: "checkExtensionStatus.php?action=getAllQueueSummary", success: function(result){
		$(".row").removeClass("opacity");
						var result =  JSON.parse(result);
						result.completed = result.completed + result.abandoned;
						$('#completed').html(result.completed);
						$('#available').html(result.available);
						$('#loggedin').html(result.loggedin);
						$('#abandoned').html(result.abandoned);
						$('#holdtime').html(result.holdtime);
						$('#talktime').html(result.talktime);
						}});
						
						$.ajax({url: "checkExtensionStatus.php?action=getAllQueue", success: function(resultTable){
						var resultTable =  JSON.parse(resultTable);
						
						for (i = 0; i < resultTable.count; i++) {
							
							
						stringQueue+="<tr><td>"+resultTable.data[i].number+"</td><td>"+resultTable.data[i].strategy+"</td><td>"+resultTable.data[i].holdtime+"</td><td>"+resultTable.data[i].talktime+"</td><td>"+resultTable.data[i].abandoned+"</td><td>"+resultTable.data[i].completed+"</td><td class='btn-group'><button class='btn btn-success btn-xs' style='float:left;margin-right:5px;' onclick=remove("+resultTable.data[i].number+")>Live Details</button></td></tr>";
						}
						$('#queueTableBody').html(stringQueue);
						}});
						
						
						
						$.ajax({url: "checkExtensionStatus.php?action=getAllQueueLiveCalls", success: function(resultLiveCalls){
						var resultLiveCalls =  JSON.parse(resultLiveCalls);
						//console.log(resultLiveCalls);
						if (resultLiveCalls != null) 
						{
							$('#livequeuecallheader').show();
							$('#livequeuecalllable').html("Live Calls On Queue");
							for (i = 0; i < resultLiveCalls.length; i++) {
							if(!resultLiveCalls[i].agents)
							{
								resultLiveCalls[i].agents = "No Agents Available";
							}else
							{
								resultLiveCalls[i].agents = resultLiveCalls[i].agents.replace(/,\s*$/, "");
							}
							var checked = resultLiveCalls[i].channel.includes("/");
							var technology='';
							if(checked)
							{
								var array = resultLiveCalls[i].channel.split("/");
								technology = array[0];
							}
							stringQueueLiveCalls+="<tr><td>"+resultLiveCalls[i].queue+"</td><td>"+resultLiveCalls[i].caller+"</td><td>"+resultLiveCalls[i].channel+"</td><td>"+resultLiveCalls[i].agents+"</td><td>"+resultLiveCalls[i].member_number+"</td><td>"+resultLiveCalls[i].holtime+"</td><td>"+resultLiveCalls[i].ringtime+"</td><td>"+resultLiveCalls[i].status+"</td><td class='iconSwecLiveQueue'><img src='../image/spy.png' data-toggle='tooltip' title='Spy Call' onclick=spyCallQueue('"+resultLiveCalls[i].channel+"','"+technology+"','spy')> <img src='../image/barge.png' data-toggle='tooltip' title='Barge Call' onclick=spyCallQueue('"+resultLiveCalls[i].channel+"','"+technology+"','barge')> <img src='../image/whisper.png' data-toggle='tooltip' title='Whisper Call' onclick=spyCallQueue('"+resultLiveCalls[i].channel+"','"+technology+"','whisper')></td></tr>";

							
							
							}
							
						}else{
							stringQueueLiveCalls="";
							$('#livequeuecalllable').html("No Live Calls On Queue");
							$('#livequeuecallheader').hide();
							
						}
							
							$('#queueLiveCallTableBody').html(stringQueueLiveCalls);
						}});

}
</script>

<?php
$callCountData = getAllQueueStatus();
$userDetails = getAllQueue();
$userLiveDetails = getAllQueueLiveCalls();

?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Queue Dashboard <span id="summary" style="color:red;">  </span>
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Queue Dashboard</li>
          </ol>
	</section>

        <!-- Main content -->
        <section class="content">
          <div class="row" style="line-height: 1;">
		   <div class="col-lg-3 col-xs-6">
              <div class="small-box border bg" style="background-color:#F7DC6F;">
                <div class="inner">
                 <h3 id='completed'><?php echo $callCountData['completed']+$callCountData['abandoned']; ?></h3>
                  <p>Total Calls</p>
                </div>
                <div class="icon">
                   <i class="ion ion-android-call" style="margin: 10;" ></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
              <div class="small-box border bg" style="background-color:#FADBD8;">
                <div class="inner">
                  <h3 id='available'><?php echo $callCountData['available']; ?></h3>
                  <p>Total Available Agents</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-person" style="margin: 10;"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-xs-6">
             <div class="small-box border bg" style="background-color:#7DCEA0;">
			 <div class="inner">
                  <h3 id='loggedin'><?php echo $callCountData['loggedin']; ?></h3>
                  <p>Total LoggedIn Agents</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-stalker" style="margin: 10;"></i>
                </div>
              </div>
            </div>
			<div class="col-lg-3 col-xs-6">
                <div class="small-box border bg" style="background-color:#F1948A;">
                <div class="inner">
                  <h3 id='abandoned'><?php echo $callCountData['abandoned']; ?></h3>

                  <p>Total Abandoned Calls</p>
                </div>
                <div class="icon">
                  <i class="ion ion ion-stats-bars" style="margin: 10;"></i>
                </div>
              </div>
            </div>
			<div class="col-lg-3 col-xs-6">
              <div class="small-box border bg" style="background-color:#D7BDE2;">
                <div class="inner">
                  <h3 id='holdtime'><?php echo $callCountData['holdtime']; ?></h3>
                  <p>Avg Hold Time</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-time" style="margin: 10;"></i>
                </div>
              </div>
            </div>
			<div class="col-lg-3 col-xs-6">
               <div class="small-box border bg" style="background-color:#85C1E9;">
            
               <div class="inner">
                  <h3 id='talktime'><?php echo $callCountData['talktime']; ?></h3>
                  <p>Avg Talk Time</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-timer" style="margin: 10;"></i>
                </div>
              </div>
            </div>
           </div>
		   
		   
		    <div class="row" id='tableQueueData'>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                 <strong> <h3 class="box-title">Queue List</h3></strong>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr style="background-color:#dce3e8">
                        <th>Queue Name</th>
						<th>Strategy</th>
                        <th>Hold Time</th>
                        <th>Talk Time</th>
                        <th>Abonded Calls</th>
                        <th>Completed Call</th>
						 <th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="queueTableBody">
					<?php 
				
					for($i=0;$i<$userDetails['count'];$i++)
					{
					?>
                      <tr>
                        <td><?php echo $userDetails['data'][$i]['number']; ?></td>
                        <td><?php echo $userDetails['data'][$i]['strategy']; ?></td>
                        <td><?php echo $userDetails['data'][$i]['holdtime']; ?></td>
                        <td><?php echo $userDetails['data'][$i]['talktime']; ?></td>
                        <td><?php echo $userDetails['data'][$i]['abandoned']; ?></td>
						<td><?php echo $userDetails['data'][$i]['completed']; ?></td>
						  <td class="btn-group">

<button type="submit" onclick="remove(<?php echo $userDetails['data'][$i]['number'];?>)" class="btn btn-success btn-xs" style="float:left;margin-right:5px;">Live Details</button>
                            </td>
                      </tr>
					<?php
					}
					?>
					  
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div>
		
		
		
		     <div class="row" id='liveCallData'>
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title" id='livequeuecalllable'>No Live Calls On Queue</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-striped">
                    <thead id='livequeuecallheader' style='display:none'>
                      <tr style="background-color:#dce3e8">
                        <th>Queue Name</th>
						<th>Caller</th>
                        <th>Channel</th>
						<th>Agents</th>
						<th>Member Extension</th>
						<th>Hold Time</th>
						<th>Ring Time</th>
						<th>Call Status</th>
						<th>Action</th>
                      </tr>
                    </thead>
                    <tbody id="queueLiveCallTableBody">
					<?php 
					$array = array();
					
					for($i=0;$i<count($userLiveDetails);$i++)
					{
						if(!$userLiveDetails[$i]['agents'])
						{
							$userLiveDetails[$i]['agents'] = "No Agents Available";
						}else
						{
							$userLiveDetails[$i]['agents'] = rtrim($userLiveDetails[$i]['agents'],',');
						}
						
						if (strpos($userLiveDetails[$i]['channel'], '/') !== false) {
							$array = explode("/",$userLiveDetails[$i]['channel']);
						}
						
					?>
                      <tr>
                        <td><?php echo $userLiveDetails[$i]['queue']; ?></td>
                        <td><?php echo $userLiveDetails[$i]['caller']; ?></td>
                        <td><?php echo $userLiveDetails[$i]['channel']; ?></td>
						<td><?php echo $userLiveDetails[$i]['agents']; ?></td>
						<td><?php echo $userLiveDetails[$i]['member_number']; ?></td>
						<td><?php echo $userLiveDetails[$i]['holtime']; ?></td>
						<td><?php echo $userLiveDetails[$i]['ringtime']; ?></td>
						<td><?php echo $userLiveDetails[$i]['status']; ?></td>
						<td><img src='../image/spy.png' data-toggle='tooltip' title='Spy Call' onclick="spyCallQueue('<?php echo $userLiveDetails[$i]['channel']; ?>','<?php echo $array[0]; ?>','spy')"> <img src='../image/barge.png' data-toggle='tooltip' title='Barge Call' onclick="spyCallQueue('<?php echo $userLiveDetails[$i]['channel']; ?>','<?php echo $array[0]; ?>','barge')"> <img src='../image/whisper.png' data-toggle='tooltip' title='Whisper Call' onclick="spyCallQueue('<?php echo $userLiveDetails[$i]['channel']; ?>','<?php echo $array[0]; ?>','whisper')">
						</td>
                      </tr>
					<?php
					}
					?>
					  
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div>
		
		  
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <?php
	  include "footer.php";
	  include "footer_script.php";
	  ?>
<script>

function spyCallQueue(channel,channelType,type)
	{		
		var lMessageToken = 
		{
			ns: lWSC.NS,
			type: "spy",
			cphonenumber:admin_channel, 
			CustomContext:type,
			CustomChannel:channel,
			extension:admin_channel,
			ip:asteriskip
		};
			var lCallbacks = 
			{
					OnFailure: function( aToken )
					{
								alert("fail");
					}
			};

			var lRes=lWSC.sendToken( lMessageToken, lCallbacks );
	
	}

function remove(queue)
{
	window.location='queue_details.php?queue='+queue;
	//$.ajax({url: "checkExtensionStatus.php?action=removeQueueMembers&queue="+queue, success: function(result){
		
		//console.log(result);
						//	}});
}

</script>

   
    <!-- page script -->
    <script>

      $(function () {
        $("#example1").DataTable({"autoWidth": true,"searching": false});
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": true,
          "searching": false,
          "ordering": false,
          "info": true,
          "autoWidth": true
        });
	  });
	  
	  
    </script>
  </body>
</html>
