<?php include "header.php" ?>
<?php include "left_sidebar.php" ?>
<?php
$callCountData = getAllUserCallCountWithStatus();

?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Dashboard
            
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">PBX Dashboard</li>
          </ol>
	</section>
	<input type="hidden" value="<?php echo $totalAgent; ?>" name="totalagent" id="totalagent" >
        <!-- Main content -->
        <section class="content">
		<div class="row">
		<button class="btn btn-warning" onclick="refreshAgentStatus()" style="margin-left:12px;margin-bottom:12px;" title="Refresh Agent Status"> Refresh Agent Status</button>
		</div>
		<div class="row" id="extensionrow" style="display:none;">
			<div class="callout callout-info">
				<h4 id="summary">Summary</h4>
			</div>
			<span id="agentdata"></span>
		</div>
		<!--
		<div class="row">
			<div class="callout callout-success">
				<h4 id="summaryiax">Summary</h4>
			</div>
			<span id="agentdataiax"></span>
		</div>
		-->
		
		
		
		 <div class="row" id="pjsiprow" style="display:none;">
		 <div class="callout callout-danger">
		 <h4 id="summarypjsip">PJ SIP Details </h4>
		 </div>
<span id="agentdatapjsip"></span>

      </div>
	  
<div class="row" id="channelRow" style="display:none">
     <div class="col-md-12" id="channelsuccess">
          <div class="box box-warning box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Channel Report</h3>

              <div class="box-tools pull-right">
               
		<button type="button" class="btn btn-box-tool" id="startButton" onclick="startReload()"><i class="fa fa-play"></i>
		</button>

		<button type="button" id="stopButton" onclick="stopReload()" style="display:none" class="btn btn-box-tool"><i class="fa fa-stop"></i>
		</button>

			   <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
				
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body" id="channelreport">
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
		
		 <div class="col-md-12" style="display:none" id="channelload">
          <div class="box box-warning box-solid">
            <div class="box-header">
              <h3 class="box-title">Loading state</h3>
            </div>
            <div class="box-body">
              Channel is fetching
            </div>
            <!-- /.box-body -->
            <!-- Loading (remove the following to stop the loading)-->
            <div class="overlay">
              <i class="fa fa-refresh fa-spin"></i>
            </div>
            <!-- end loading -->
          </div>
          <!-- /.box -->
        </div>
		
		
		
		
	
		<!-- <span id="queuedata"></span> -->
</div>
	<div class="row" id="queuerow" style="display:none;">
		<div class="callout callout-danger">
			<h4 id="summaryqueue">Queue Details </h4>
		</div>
		<span id="queuedata"></span>
	</div>
	  

	  <div class="row">
	  
	  
	  <div class="modal fade" id="centralModalSm" tabindex="-1" role="dialog" aria-labelledby="queueLable" aria-hidden="true">

    <!-- Change class .modal-sm to change the size of the modal -->
    <div class="modal-dialog modal-lg " role="document">


      <div class="modal-content" style="width:1024px;">
        <div class="modal-header" style="background-color:#abc4ef">
          <h4 class="modal-title w-100" id="queueLable"></h4>
      <input type="hidden" id='queuenumber'>
        </div>
        <div class="modal-body" id='queuedescription'>
          ...
        </div>
        <div class="modal-footer">
	
		<button type="button" class="btn btn-primary btn-sm" id='startButtonQueue' data-toggle='modal' title="Start Listen" onclick='startQueueReload()'>Play</button>
		<button type="button" class="btn btn-primary btn-sm" id='stopButtonQueue' data-toggle='modal' title="Pause Listen" style="display:none" onclick='stopQueueReload()'>Pause</button>
          <button type="button" class="btn btn-primary btn-sm" onclick='stopQueueReload()' title="Close Window" data-toggle='modal' data-dismiss="modal">Close</button>
          
        </div>
      </div>
    </div>
  </div>
	  
	  
	  </div>
	  
	  
	  
	  
	  
	  
	  
	<script>
	
$(window).load(function() {	
$(".content-wrapper").addClass("opacity");
});
	var refreshId="";
	function startReload()
	{
		$("#startButton").hide();
		$("#stopButton").show();
		refreshId = setInterval(getReportChannel, 1000);
		console.log(refreshId); 
	}
	function stopReload()
	{
		$("#stopButton").hide();
		$("#startButton").show();
		clearInterval(refreshId);
	}
	var QueueRefreshId="";
	function startQueueReload()
	{
		$("#startButtonQueue").hide();
		$("#stopButtonQueue").show();

		var queue = document.getElementById('queuenumber').value;
	
		QueueRefreshId = setInterval(function(){getQueueDetail(queue)}, 1000);
		console.log(QueueRefreshId); 
	}
	function stopQueueReload()
	{
		 $("#stopButtonQueue").hide();
		$("#startButtonQueue").show();
		clearInterval(QueueRefreshId);
	}
	
	function refreshAgentStatus(){
		$.ajax({url: "getExtensionStatus.php?resetAgentStatus=yes", success: function(result){
			toastr["success"]("Live Agent Successfully Refreshed");
			console.log(result);
			location.reload();
		}});
	}
	
	</script>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <?php
	  include "footer.php";
	  include "footer_script.php";
	  ?>

  </body>
</html>
