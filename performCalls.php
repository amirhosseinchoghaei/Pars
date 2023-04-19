<?php 

/**
* @file
*
* All codes is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
*....................
* www.pars-space.ir
*/

?>

<html>
<?php

require_once('connection.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);


function str_getcsv_line($string){
	$string = preg_replace_callback(
        '|"[^"]+"|',
        create_function(
            '$matches',
            'return str_replace(\',\',\'*comma*\',$matches[0]);'
        ),$string );
$array = explode(',',$string);
$array = str_replace('*comma*',',',$array);
return $array;

}

function spawn($cmd,$outputfile,$pidfile)
{
 exec(sprintf("%s >> %s 2>&1 & echo $! >> %s", $cmd, $outputfile, $pidfile));
}


$reset_controls="pause=false\nstop=false";
file_put_contents('control.ini', $reset_controls);
 
$config = parse_ini_file("config.ini",true);
$interval = $config['callblaster']['interval'];

if($_POST['action']=="Start Campain")
{
	if(!isset($_FILES['csvFile']) or $_FILES['csvFile']['error']>0)
	{
		echo "File upload error : ".$_FILES['csvFile']['error'];
	}
	else
	{
		$ts=time();
		$dest = $basepath."files/".$ts.$_FILES['csvFile']['name'];
		move_uploaded_file($_FILES['csvFile']['tmp_name'],$dest);
		
		$msg = "Recieved File $dest at ".date("r",time());
		file_put_contents("logs/uploads.txt",$msg,FILE_APPEND);

		$command = "php ".$basepath."asyncCall.php $dest";
		//echo $command;

		spawn("$command","/tmp/".$_FILES['csvFile']['name'],"/tmp/pid_".$_FILES['csvFile']['name']);
		
		/*
		
		$csv = array();
		$lines = file($dest, FILE_IGNORE_NEW_LINES);
		
		foreach ($lines as $key => $value)
		{
		    $csv[$key] = str_getcsv_line($value);
		}
		
		$audioIndex = count($csv[0])-2; 
		$phoneIndex = count($csv[0])-1;
		$itemCount = count($csv,0);
		$fields=implode(",",$csv[0]);
		$query = "insert into logs(fields,time,status,options,type,csvFile) values('$fields',NOW(),'upload','Nil','heading','$dest')";
		$result = mysql_query($query) or die("Database Error");

		
		
		echo "Records Found : ".($itemCount-1)."<br>";
		for($i=1;$i<=$itemCount-1;$i++)
		{
			$config = parse_ini_file("config.ini",true);
			$interval = $config['callblaster']['interval'];
			$number = $csv[$i][$phoneIndex];
			$audio = $csv[$i][$audioIndex];
			$fields = implode(",",$csv[$i]);
			$query = "insert into logs(fields,time,status,options,type,csvFile) values('$fields',NOW(),'Dialling','Nil','field','$dest')";
			$result = mysql_query($query) or die("Database Error");
			$id = mysql_insert_id();
			$phone = $number;
			$phone=substr($phone,0,15);
			$callFile = "Channel: local/$phone@from-internal\n";
			$callFile .= "MaxRetries: 2\n";
			$callFile .= "WaitTime: 30\n";
			$callFile .= "CallerID: $caller_id\n";
			$callFile .= "Context: callblaster\n";
			$callFile .= "Extension: 333\n";
			$callFile .= "Set: userAudio=$audio\n";
			$callFile .= "Set: userNumber=$number\n";
			$callFile .= "Set: dbid=$id\n";
			$callFileName = $number."_".time().".call";
			file_put_contents("/tmp/$callFileName",$callFile);
			$time=date("c",time());
			try
			{
				exec("mv /tmp/$callFileName /var/spool/asterisk/outgoing/$callFileName");
				$msg = $time." -- Call file to 1".$number." created -- CSV file: $dest\n";
				$status="Dialled";
			}
			catch(Exception $e)
			{
				$msg=$time." -- ERROR:".$e->getMessage()." -- CSV file : $dest\n";
				$status="Dial Failed";
			}
			
			$query = "update logs set status='$status', time=NOW() where autoID='$id'";
			$result = mysql_query($query) or die("Database Error");
			file_put_contents("logs/callLog.txt",$msg,FILE_APPEND);

			sleep($interval);
		}*/
				
	}
}

?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
function updateLogger(file)
{
	
	$.post("readLog.php",{action:"getLog",file:file},function(data,status){
		
		
		$('#logger').html(data);
	});
	

}

$(document).ready(function(){

	var t = setInterval(function(){updateLogger("<?php echo urlencode($dest); ?>");},1000);
});

</script>
<head>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300&family=Cairo:wght@200&family=Roboto:wght@300&family=Tajawal:wght@300&display=swap" rel="stylesheet">



<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Asterisk Dialer</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	</head>
	
<style>

body {

font-family: 'Roboto', sans-serif;


  font-size: 16px;
  direction:rtl;
  
 
  
  
}	
</style>	

<body>

<!-- Header -->
<div id="header">

	<div class="shell">
	
		<!-- Logo + Top Nav -->
		
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1><center><h3 style="color:white;"><div id="txt"></div></h3></center>
			<div id="top-navigation">
			
				
				<a href="index.php">Main Page<</a>
				<span></span>
                              

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>




<!-- End Header -->
</body>
</html>







<center>
<h2>Live Calls</h2></center>

<script>
$(document).ready(function(){
	$("#pause-btn").click(function(){
		var act=$("#pause-btn").val();
		var chng='Start';
		var chngval='start';
	  	$.post("control.php",{action:act},function(data){
	  		if(act=='start'){
	  			chngval='pause';
	  			chng='Pause';
	  		}
	  	$("#pause-btn").val(chngval);
	  	$("#pause-btn").html(chng);
		});
	})
	
	$("#stop-btn").click(function(){
	  $.post("control.php",{action:'stop'},function(data){
	  	$("#pause-btn").attr('disabled','disabled');
	  	$("#stop-btn").attr('disabled','disabled');
		alert("Call blasting Stopped");
		});
	})
	
	
})

	
	
</script>
<button id="pause-btn" value="pause">Pause</button><button id="stop-btn">Stop</button>
<div style="border-style:double" id="logger"></div>


<h1> </h1>
<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1>
			<div id="top-navigation">
				
				<span>|</span>
				<a href="https://t.me/amirparsss">Pars-Space.ir</a>
				<span>|</span>
                                

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>
<!-- End Header -->
</body>
<div style="border-style:double" id="logger"></div>



</html>
