<?php
include_once("../controller/route.php");
ini_set('display_errors',1);
	ini_set('display_startup_errors',1);
	ini_set('display_errors', 'On');
 if(isset($_POST["Import"])){
	 // header field name => db field name 
	 $map = array( 
      'First_Name'=>'first_name', 
      'Last_name'=>'last_name', 
      'email'=>'email', 
      'phone'=>'phone', 
      'phone2'=>'phone2', 
      'address'=>'address',
    );
	
		if($_FILES["file"]["tmp_name"])
		{
		$filename=$_FILES["file"]["tmp_name"];		


		 if($_FILES["file"]["size"] > 0)
		 {
			 $h = fopen($filename,'r'); 
			 while($row=fgetcsv($h)) {
			// echo $filename;

      // setup the expected field mapping 
      if (empty($flds)) { 
        foreach($map as $single_field=>&$db_field) { 
          foreach($row as $k=>&$v) { 
            if ($single_field==$v) $flds[$k] = $db_field; 
          } 
        } 
        continue; // skip the rest of the first loop 
      } 
     
      // the mapped field locations within each $row: 
      //die('<pre>'.print_r($flds,true)); 

      for($i=0;$i<count($flds);$i++) { 

        // we don't insert all columns, just the mapped ones 
       // $val = mysql_real_escape_string($row[$i]); 
        $cols[] = '"'.$row[$i].'"'; 
      } 
      $query = implode(',',$cols); 
	  print_r($cols);
    //  echo $query;
    } 
  
		 }
		}else{
			  echo "<script type=\"text/javascript\">
			  alert('NO CSV File Found');
						window.location = \"import_contact.php\"
					</script>";
		}
	}
	


 ?>