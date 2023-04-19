<html xmlns="http://www.w3.org/1999/xhtml">
<head>







<script>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>

<body onload="startTime()">




</body>











	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>AutoDialer</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<script src='funciones.js'></script>
<script>
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'location=0,width=320,height=420,resizable=0,scrollbars=no');
return false;
}
</script>


</head>
<body>

<!-- Header -->
<div id="header">

	<div class="shell">
	
		<!-- Logo + Top Nav -->
		
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1><!--<center><h3 style="color:white;"><div id="txt"></div></h3></center> -->
			<div id="top-navigation">
			
				
				<span></span>
			
				<span></span>
				                <a href="index.php">Back To Main Page < </a>
                      
								

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>




<!-- End Header -->
</body>
</html>



<?php include "header.php" ?>

<head>  

<style>   

  
  
  

button {   
       background-color: #064E89;   
       width: 100%;  
        color: white;   
        padding: 15px;   
        margin: 10px 0px;   
        border: none;   
        cursor: pointer;   
         }   
 
</style>   
</head>    


<?php
/**
* @file
*
* All Callblaster code is released under the GNU General Public License.
* See COPYRIGHT.txt and LICENSE.txt.
*
*....................
* www.nethram.com
*/

require_once ('config.php');

if($_POST['action']=="Update Config")
{
	$content = "[callblaster]\n"."interval=".$_POST['interval']."\n[press1]\n"."context=".$_POST['context1']."\n"."extension=".$_POST['exten1']."\n";
	$content.= "[press2]\n"."context=".$_POST['context2']."\n"."extension=".$_POST['exten2']."\n[waittimes]\n"."waittime=".$_POST['waittime']."\n";
	
	file_put_contents("config.ini",$content);
	
}

$config = parse_ini_file("config.ini",true);
$interval = $config['callblaster']['interval'];
$context1 = $config['press1']['context'];
$exten1 = $config['press1']['extension'];
$context2 = $config['press2']['context'];
$exten2 = $config['press2']['extension'];
$waittime = $config['waittimes']['waittime'];
?>
<html>
	<head>
	
	
	

	
	
	
	
	
	
	
	
		<title>AutoDialer</title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">
		
		
		
		
	</head>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Audiowide">

	<body style="margin-left: 2.8em;padding: 0 3em 1em 0;border-width: 1px;">
	
	
	
	
	
	
	
	
	<div id="loader"></div>
	
		<center><h2>? How To Use </h2></i></center>
		<div id="borderDemo">
		<a href="index.php" class="button button2 fa fa-reply-all"></a>
		
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
<!--
<div style="float: left">
<button id="pause-btn" value="pause">Pause</button><button id="stop-btn">Stop</button>
</div>		
-->
	<html>
<head>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

direction:rtl;


</style>
</head>
<body>



<table>
 
  <tr>
    
    <td>The distance between each call *</td>
    <td>:interval</td>
  </tr>
  <tr>
    
    <td>Ringing Time Each Call *</td>
    <td>:WaitTime</td>
  </tr>
  <tr>
  
    <td>Your SIP Trunk Number (you can use any number in local) *</td>
    <td>:Caller ID</td>
  </tr>
  <tr>
   
    <td>Its It depends your trunk configuration recommended : from-internal *</td>
    <td>:Context</td>
  </tr>
  <tr>
  
    <td>Call Back To Extension Press 1 or 2 *</td>
    <td>:Extension</td>
  </tr>
  <tr>
   
    <td>Downlaod The CSV File recommended and Fill the Fields then Upload * </td>
    <td>:Make Call</td>
  </tr>
  <tr>
 
    <td>The Audio File Should Be mp3,wav,ogg Formats *</td>
    <td>:Audio</td>
  </tr>
</table>

</body>
</html>
  </tr>
</table>
		<script>
let input = document.querySelector(".input000");
let button = document.querySelector(".button000");
button.disabled = true;
input.addEventListener("change", stateHandle);
function stateHandle() {
  if (document.querySelector(".input000").value === "") {
    button.disabled = true;
  } else {
    button.disabled = false;
  }
}
</script>
		
		
		

		
	<?php

if(isset($_POST['remve_file']))
	
{
	
//The name of the folder.
$folder = 'files';

//Get a list of all of the file names in the folder.
$files = glob($folder . '/*');

//Loop through the file list.
foreach($files as $file){
    //Make sure that this is a file and not a directory.
    if(is_file($file)){
        //Use the unlink function to delete the file.
        unlink($file);
    }
}
}
?>
	 
		
		
</div>

</style>
	</body>

</html>
<?php

function list_logs()
{
  exec("ls -t files/",$files);
  echo("<table>");
  foreach($files as $file)
  {
    if(preg_match("/csv$/",$file))
    {
      $size     = filesize("files/$file")/1024;
      $filedate = date ("m/d/Y H:i:s", filemtime("files/$file"));
	$link="downloadLog.php?file=".urlencode($basepath."/files/$file");
      echo "<tr> <td> <a href='$link'>".$file."</a> </td> <td> $filedate </td>"; //<td> $size kB</td>
    }
  }
  echo("</table>");
}

?>

<head>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300&family=Cairo:wght@200&family=Roboto:wght@300&family=Tajawal:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">









<script>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>





<style>

body {

font-family: 'Roboto', sans-serif;


  font-size: 16px;
  direction:rtl;
  
 
  
  
}


</style>
</head>
</body>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Asterisk Dialer</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
	<script src='funciones.js'></script>
<script>
function popup(mylink, windowname)
{
if (! window.focus)return true;
var href;
if (typeof(mylink) == 'string')
   href=mylink;
else
   href=mylink.href;
window.open(href, windowname, 'location=0,width=320,height=420,resizable=0,scrollbars=no');
return false;
}
</script>

</head>
<body>

<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1>
			<div id="top-navigation">
				
				<span>|</span>
				<a href="https://t.me/AmirHosseinTSL">Powered By AmirHossein</a>
				<span>|</span>
                                

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>
<!-- End Header -->
</body>

</html>




<!DOCTYPE html>
<html>
<head>


<style>
body {
  padding: 25px;
  background-color: white;
  color: black;
  font-size: 16px;
 background: #E3EFFF;
background: -moz-linear-gradient(top, #E3EFFF 0%, #F2F9FF 51%, #E3EFFF 100%);
background: -webkit-linear-gradient(top, #E3EFFF 0%, #F2F9FF 51%, #E3EFFF 100%);
background: linear-gradient(to bottom, #E3EFFF 0%, #F2F9FF 51%, #E3EFFF 100%);
  
}


#borderDemo {
border-top: 10px solid #134B70;
border-radius: 27px 18px 0px 0px;
}



a:link {
  color: green;
  background-color: transparent;
  text-decoration: none;
}

a:visited {
  color: green;
  background-color: transparent;
  text-decoration: none;
}

a:hover {
  color: red;
  background-color: transparent;
  text-decoration: underline;
}

a:active {
  color: red;
  background-color: transparent;
  text-decoration: underline;
}


.button {
  background-color: #4a4a4a; /* Green */
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
	background:linear-gradient(to bottom, #f9f9f9 5%, #e9e9e9 100%);
	background-color:#f9f9f9;
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
	box-shadow:inset 0px 1px 0px 0px #d9fbbe;
	background:linear-gradient(to bottom, #b8e356 5%, #a5cc52 100%);
	background-color:#b8e356;
	border-radius:6px;
	border:1px solid #83c41a;
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





.dark-mode {
  background-color: black;
  color: white;
}
</style>
</head>
<body>




<script>
function myFunction() {
   var element = document.body;
   element.classList.toggle("dark-mode");
}
</script>

</body>
</html>























<!DOCTYPE html>
<html>
<head>
<style>
#more {display: none;}
</style>
</head>
<body>


<script>
function moresFunction() {
  var dots = document.getElementById("dots");
  var moreText = document.getElementById("more");
  var btnText = document.getElementById("myBtn");

  if (dots.style.display === "none") {
    dots.style.display = "inline";
    btnText.innerHTML = "Show All Logs"; 
    moreText.style.display = "none";
  } else {
    dots.style.display = "none";
    btnText.innerHTML = "Hide All Logs"; 
    moreText.style.display = "inline";
  }
}
</script>

</body>
</html>








<!DOCTYPE html>
<html>
<style>

* {box-sizing: border-box;}

/* Set a style for all buttons */


/* Float cancel and delete buttons and add an equal width */
.cancelbtn, .deletebtn {
  float: left;
  width: 50%;
}

/* Add a color to the cancel button */
.cancelbtn {
  background-color: #ccc;
  color: black;
}

/* Add a color to the delete button */
.deletebtn {
  background-color: #f44336;
}

/* Add padding and center-align text to the container */
.container {
  padding: 16px;
  text-align: center;
}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: #474e5d;
  padding-top: 50px;
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* Style the horizontal ruler */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}
 
/* The Modal Close Button (x) */
.close {
  position: absolute;
  right: 35px;
  top: 15px;
  font-size: 40px;
  font-weight: bold;
  color: #f1f1f1;
}

.close:hover,
.close:focus {
  color: #f44336;
  cursor: pointer;
}

/* Clear floats */
.clearfix::after {
  content: "";
  clear: both;
  display: table;
}

/* Change styles for cancel button and delete button on extra small screens */
@media screen and (max-width: 300px) {
  .cancelbtn, .deletebtn {
     width: 100%;
  }
}
</style>
<body>





<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</body>
</html>

















