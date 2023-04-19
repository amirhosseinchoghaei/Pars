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
	<title>Manage Voices</title>
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
			
				<a href="index.php"> Main Page <</a>
				
			
                           

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>




<!-- End Header -->
</body>
</html>



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
require_once('config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);


function displayAudio()
{
	global $basepath;
	 exec("ls -t audio/",$files);
	 echo("<table>");
  	foreach($files as $file)
  	{		
    		//$size     = filesize($basepath."/files/$file")/1024;
      		//$filedate = date ("m/d/Y H:i:s", filemtime($basepath."/files/$file"));
        	$link="audio/$file";
      		echo "<tr><td>$file</td> <td> <a target='_blank' href='$link'>Downlaod</a> </td> <td>
			
      		
      		<form method='post' action='demoCall.php'>
      		
      		<input type='hidden' name='file' value='$file' />
      		<input type='submit' class='button button1' name='action' value='Demo Call' />
		
      		</form>
      		
      		 </td> ";
    	}
  
  	echo("</table>");


}






if(isset($_POST['action'])):
if($_POST['action']=="Upload Audio")
{

	if(isset($_FILES['audioFile']))
	{
	
		$tmpDest = $basepath."tmp/".str_replace(' ', '',$_FILES['audioFile']['name']);
		$exten = pathinfo($_FILES['audioFile']['name']);
		$exten = '.'.$exten['extension'];
		
		$fileName=str_replace(' ', '',basename($_FILES['audioFile']['name'], $exten));
		
		$dest = $basepath."/audio/$fileName.wav";
		move_uploaded_file($_FILES['audioFile']['tmp_name'],$tmpDest);
		
		$out=shell_exec('asterisk -rx "file convert '. $tmpDest.' '.$dest.'"');
		var_dump($out);
		echo "<script type='text/javascript'>alert('Audio Added Successfully');</script>";
	
	}

}
endif;

?>
<html>

<body>
<center>
<h3><b>Manage Voices</b></h3>



<!--<h4>Note : welcome.mp3 is the default audio file. In the csv file you only need to specify the file name(Eg: 'welcome' for 'welcome.mp3')</h4>
-->

</center>


<h3>Uplaod Voice •</h3>
<h1> </h1>

<!DOCTYPE html>
<html>
<head>
  <title>Upload your files</title>
</head>
<body>
  <form enctype="multipart/form-data" action="audioFile.php" method="POST">
  
    <input type="file" name="uploaded_file"></input><br />
    <input type="submit" value="Upload Audio"></input>
  </form>
</body> 
</html>
<?PHP
  if(!empty($_FILES['uploaded_file']))
  {
    $path = "audio/";
    $path = $path . basename( $_FILES['uploaded_file']['name']);

    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $path)) {
      echo "success ! -- ".  basename( $_FILES['uploaded_file']['name']). 
      "  ";
    } else{
        echo "Try Again";
    }
  }
?>	

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

<div id="borderDemo"></div>
<h1> </h1>
<center>
<h3> <b> List of Voices </b> </h3>
</center>
<h3> Voices •</h3>
<table class="paleBlueRows">


<tbody>
<tr>
<td><?php displayAudio(); ?></td>
</tr>

</tbody>
</table>

<h1> </h1>
<div id="borderDemo"></div>



</body>



</html>

<head>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Lalezar:wght@300&family=Cairo:wght@200&family=Lalezar:wght@300&family=Tajawal:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<style>

body {
  font-family: 'Lalezar', Lalezar;
  font-size: 16px;
  direction:rtl;
 

  
  button {   
       background-color: #000000;   
       width: 100%;  
        color: white;   
        padding: 15px;   
        margin: 10px 0px;   
        border: none;   
        cursor: pointer;   
         }   
 
}

hr { 
  display: block;
  margin-top: 0.5em;
  margin-bottom: 0.5em;
  margin-left: auto;
  margin-right: auto;
  border-style: inset;
  border-width: 1px;
} 



table.paleBlueRows {

  border: 1px solid #002F3A;
  background-color: #EAEAEA;
  width: 300px;
  height: 70px;
  text-align: center;
  border-collapse: collapse;
}
table.paleBlueRows td, table.paleBlueRows th {
  border: 1px solid #FFFFFF;
  padding: 3px 2px;
}
table.paleBlueRows tbody td {
  font-size: 16px;
}
table.paleBlueRows tr:nth-child(even) {
  background: #FFFFFF;
}
table.paleBlueRows thead {
  background: #0B6FA4;
  border-bottom: 5px solid #FFFFFF;
}
table.paleBlueRows thead th {
  font-size: 17px;
  font-weight: bold;
  color: #FFFFFF;
  text-align: center;
  border-left: 2px solid #FFFFFF;
}
table.paleBlueRows thead th:first-child {
  border-left: none;
}

table.paleBlueRows tfoot {
  font-size: 14px;
  font-weight: bold;
  color: #333333;
  background: #FFFFFF;
  border-top: 3px solid #444444;
}
table.paleBlueRows tfoot td {
  font-size: 14px;
}
table.paleBlueRows tfoot .links {
  text-align: right;
}
table.paleBlueRows tfoot .links a{
  display: inline-block;
  background: #1C6EA4;
  color: #FFFFFF;
  padding: 2px 8px;
  border-radius: 5px;
}
  
#borderDemo {
border-top: 4px solid #000000;
border-radius: 28px 28px 0px 0px;
}
</style>
</head>



<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lalezar">

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
<center><h3><b>File Manager</b></h3></center>

<!DOCTYPE html>
<html>
 <head>
  <title></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <br /><br />
  <div class="container">

   <br />
   <div align="right">
    
   </div>
   <br />
   <div class="table-responsive" id="folder_table">
    
   </div>
  </div>
 </body>
</html>

<div id="folderModal" class="modal fade" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><span id="change_title">Create Folder</span></h4>
   </div>
   <div class="modal-body">
    <p>Enter Folder Name
    <input type="text" name="folder_name" id="folder_name" class="form-control" /></p>
    <br />
    <input type="hidden" name="action" id="action" />
    <input type="hidden" name="old_name" id="old_name" />
    <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create" />
    
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>
<div id="uploadModal" class="modal fade" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Upload File</h4>
   </div>
   <div class="modal-body">
    <form method="post" id="upload_form" enctype='multipart/form-data'>
     <p>Select Image
     <input type="file" name="upload_file" /></p>
     <br />
     <input type="hidden" name="hidden_folder_name" id="hidden_folder_name" />
     <input type="submit" name="upload_button" class="btn btn-info" value="Upload" />
    </form>
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>

<div id="filelistModal" class="modal fade" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">FileManager</h4>
   </div>
   <div class="modal-body" id="file_list">
    
   </div>
   <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
   </div>
  </div>
 </div>
</div>

<script>
$(document).ready(function(){
 
 load_folder_list();
 
 function load_folder_list()
 {
  var action = "fetch";
  $.ajax({
   url:"action.php",
   method:"POST",
   data:{action:action},
   success:function(data)
   {
    $('#folder_table').html(data);
   }
  });
 }
 
 $(document).on('click', '#create_folder', function(){
  $('#action').val("create");
  $('#folder_name').val('');
  $('#folder_button').val('Create');
  $('#folderModal').modal('show');
  $('#old_name').val('');
  $('#change_title').text("Create Folder");
 });
 
 $(document).on('click', '#folder_button', function(){
  var folder_name = $('#folder_name').val();
  var old_name = $('#old_name').val();
  var action = $('#action').val();
  if(folder_name != '')
  {
   $.ajax({
    url:"action.php",
    method:"POST",
    data:{folder_name:folder_name, old_name:old_name, action:action},
    success:function(data)
    {
     $('#folderModal').modal('hide');
     load_folder_list();
     alert(data);
    }
   });
  }
  else
  {
   alert("Enter Folder Name");
  }
 });
 
 $(document).on("click", ".update", function(){
  var folder_name = $(this).data("name");
  $('#old_name').val(folder_name);
  $('#folder_name').val(folder_name);
  $('#action').val("change");
  $('#folderModal').modal("show");
  $('#folder_button').val('Update');
  $('#change_title').text("Change Folder Name");
 });
 
 $(document).on("click", ".delete", function(){
  var folder_name = $(this).data("name");
  var action = "delete";
  if(confirm("Are you sure you want to remove it?"))
  {
   $.ajax({
    url:"action.php",
    method:"POST",
    data:{folder_name:folder_name, action:action},
    success:function(data)
    {
     load_folder_list();
     alert(data);
    }
   });
  }
 });
 
 $(document).on('click', '.upload', function(){
  var folder_name = $(this).data("name");
  $('#hidden_folder_name').val(folder_name);
  $('#uploadModal').modal('show');
 });
 
 $('#upload_form').on('submit', function(){
  $.ajax({
   url:"upload.php",
   method:"POST",
   data: new FormData(this),
   contentType: false,
   cache: false,
   processData:false,
   success: function(data)
   { 
    load_folder_list();
    alert(data);
   }
  });
 });
 
 $(document).on('click', '.view_files', function(){
  var folder_name = $(this).data("name");
  var action = "fetch_files";
  $.ajax({
   url:"action.php",
   method:"POST",
   data:{action:action, folder_name:folder_name},
   success:function(data)
   {
    $('#file_list').html(data);
    $('#filelistModal').modal('show');
   }
  });
 });
 
 $(document).on('click', '.remove_file', function(){
  var path = $(this).attr("id");
  var action = "remove_file";
  if(confirm("Are you sure you want to remove this file?"))
  {
   $.ajax({
    url:"action.php",
    method:"POST",
    data:{path:path, action:action},
    success:function(data)
    {
     alert(data);
     $('#filelistModal').modal('hide');
     load_folder_list();
    }
   });
  }
 });

$(document).on('blur', '.change_file_name', function(){
  var folder_name = $(this).data("folder_name");
  var old_file_name = $(this).data("file_name");
  var new_file_name = $(this).text();
  var action = "change_file_name";
  $.ajax({
   url:"action.php",
   method:"POST",
   data:{folder_name:folder_name, old_file_name:old_file_name, new_file_name:new_file_name, action:action},
   success:function(data)
   {
    alert(data);
   }
  });
 });
 
});
</script>








<body>

<!-- Header -->
<div id="header">
	<div class="shell">
		<!-- Logo + Top Nav -->
		<div id="top">
			<h1><a href="http://asterisk-pbx.ru/wiki/works/asterisk_autodialer"></a></h1>
			<div id="top-navigation">
				
				<span>Version:2.1   </span>
				<a href="https://t.me/AmirHosseinTSL">Pars-Space.ir</a>
				<span>  </span>
                                

			</div>
		</div>
		<!-- End Logo + Top Nav -->
		</div>
</div>
<!-- End Header -->
</body>
<div style="border-style:double" id="logger"></div>
</html>














<!DOCTYPE html>
<html>
<head>

<style>
body {
  padding: 10px 80px 80px 50px;
  background-color: white;
  color: black;
  font-size: 16px;
  
  

}



a:link {
  color: green;
  background-color: transparent;
  text-decoration: none;
}

a:visited {
  color: pink;
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

.button1 {width: 130px;}
.button2 {width: 10%;}
.button3 {width: 10%;}


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











  