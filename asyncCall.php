<?php

$dest = $argv[1];

function str_getcsv_line($string)
{
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

if(file_exists($argv[1]))
{
	require_once($basepath.'connection.php');
	set_time_limit(0);
	chdir($basepath);

	$csv = array();
	$lines = file($dest, FILE_IGNORE_NEW_LINES);
	
	foreach ($lines as $key => $value)
	{
		if(!empty($value))
	    $csv[$key] = str_getcsv_line($value);
	}
	
	$audioIndex = count($csv[0])-2; 
	$phoneIndex = count($csv[0])-1;
	$itemCount = count($csv,0);
	$fields=implode(",",$csv[0]);
	$query = "insert into logs(fields,time,status,options,type,csvFile) values('$fields',NOW(),'upload','Nil','heading','$dest')";
	$result = mysql_query($query) or die("Database Error");

	
	
	echo "Records Found : ".($itemCount-1)."<br>";
	$i=1;
	while($i<=$itemCount-1)
	{	
		$config = parse_ini_file("config.ini",true);
		$interval = $config['callblaster']['interval'];
		$waittime = $config['waittimes']['waittime'];
		$prefix = $config['prefixc']['prefix'];
		//pause-stop controls
		file_put_contents('control.ini', $reset_controls);
		$controls=parse_ini_file('control.ini');
		
		if($controls['stop']){
			exit();
		}
		
		if(!$controls['pause']):
			
		$number = $csv[$i][$phoneIndex];
		$audio = $csv[$i][$audioIndex];
		$fields = implode(",",$csv[$i]);
		$query = "insert into logs(fields,time,status,options,type,csvFile) values('$fields',NOW(),'Dialling','Nil','field','$dest')";
		$result = mysql_query($query) or die("Database Error");
		$id = mysql_insert_id();
		$phone = $number;
		$phone=substr($phone,0,15);
		$callFile = "Channel: local/$prefix$phone@from-internal\r\n";
		$callFile .= "WaitTime: $waittime\r\n";
		$callFile .= "CallerID: $caller_id\r\n";
		$callFile .= "Context: callblaster\r\n";
		$callFile .= "Extension: 333\r\n";
		$callFile .= "Account: callblaster\r\n";
		$callFile .= "Set: userAudio=$audio\r\n";
		$callFile .= "Set: userNumber=$number\r\n";
		$callFile .= "Set: dbid=$id\r\n";
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

		//sleep($interval);
		$interval=$interval*1000000;
		usleep($interval);
		
		$i++;
	elseif($controls['pause']):
		sleep(1);
	endif;
	}
}

?>
