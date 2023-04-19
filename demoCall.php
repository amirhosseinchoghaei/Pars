<?php
/**
* @file
*
* All Callblaster code is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
*....................
* www.pars-space.ir
*/

?>
<html>
<center>
	<h2></h2>

<?php
require_once 'config.php';
if($_REQUEST['action']=='Demo Call')
{

	$file = $_REQUEST['file'];
	
?>
<div class="w3-container">
 
  
  <div class="w3-card-4">
    <div class="w3-container w3-green">
	<form method="post" action="demoCall.php">
		<h1>Demo Call</h1>
		<input type="hidden" name="file" value="<?php echo $file; ?>"/>
		<input type="text" name="phone" placeholder="Enter The Number " size="80" class="w3-input"/><br><br>
		<input type="submit" name="action" value="Call" class="my2Button"/>
	
	</form>
  </div>
</div>
<?php
}
if($_REQUEST['action']=="Call")
{
	$phone=$_REQUEST['phone'];
	$file = $basepath."audio/".$_REQUEST['file'];
	
	 $exten = pathinfo($file);
	 $exten = '.'.$exten['extension'];
	                 
	 $fileName= $basepath."audio/".basename($file, $exten);
	                                 
	
	$callFile = "Channel: local/$phone@from-internal\n";
	$callFile .= "Application: Playback\n";
	$callFile .= "CallerID: $caller_id\n";
	$callFile .= "Data: $fileName\n";
	file_put_contents("/tmp/demoCall.call",$callFile);
	exec("mv /tmp/demoCall.call /var/spool/asterisk/outgoing/demoCall.call");
	echo "<script type='text/javascript'>alert('Call initiated'); window.location='audioFile.php';</script>";
	
}



?>
<h1> </h1>
<a href="audioFile.php"> Main Page <</a>
</center>



</html>

<head>


<style>



.button {
  background-color: #000000; /* Green */
  border: none;
  color: white;
  padding: 10px 10px;
  text-align: center;
  text-decoration: none;
  display: inline-white;
  font-size: 14px;
  margin: 2px 2px;
  cursor: pointer;
}

.button1 {width: 250px;}
.button2 {width: 50px;}
.button3 {width: 100%;}
.myButton {width: 250px;}
.my1Button {width: 250px;}
.my2Button {width: 250px;}

.myButton {
	box-shadow:inset 0px 1px 0px 0px #f5978e;
	background:linear-gradient(to bottom, #f24537 5%, #c62d1f 100%);
	background-color:#f24537;
	border-radius:6px;
	border:1px solid #d02718;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #810e05;
}
.myButton:hover {
	background:linear-gradient(to bottom, #c62d1f 5%, #f24537 100%);
	background-color:#c62d1f;
}
.myButton:active {
	position:relative;
	top:1px;
}



.my1Button {
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:linear-gradient(to bottom, #000000 5%, #000000 100%);
	background-color:#000000;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	cursor:pointer;
	color:#666666;
	
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #ffffff;
}
.my1Button:hover {
	background:linear-gradient(to bottom, #e9e9e9 5%, #f9f9f9 100%);
	background-color:#e9e9e9;
}
.my1Button:active {
	position:relative;
	top:1px;
}

        


.my2Button {
	box-shadow:inset 0px 1px 0px 0px #000000;
	background:linear-gradient(to bottom, #000000 5%, #000000 100%);
	background-color:#000000;
	border-radius:6px;
	border:1px solid #000000;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:0px 1px 0px #86ae47;
}
.my2Button:hover {
	background:linear-gradient(to bottom, #a5cc52 5%, #b8e356 100%);
	background-color:#a5cc52;
}
.my2Button:active {
	position:relative;
	top:1px;
}

</head>
</style>



