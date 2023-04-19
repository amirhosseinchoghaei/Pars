<?php
$sql_details = array('user' => 'techextension','pass' => 'techextension','db'   => 'techextension','host' => 'localhost' );
$GLOBALS['con']  = mysqli_connect($sql_details['host'],$sql_details['user'],$sql_details['pass'],$sql_details['db']);
	   
   function insert_record($fields,$values,$tablename){
			$query="insert into ".$tablename."(".$fields.") values (".$values.")"; 
			$res=mysqli_query($GLOBALS['con'],$query);
			return $res;     
        }
		   function insert_record_max($fields,$values,$tablename){
			$query="insert into ".$tablename."(".$fields.") values (".$values.")"; 
			//echo $query;
			//$res=mysqli_query($GLOBALS['con'],$query);
			return $query;     
        }
      
        function select_record($to_fetch,$where_cond,$tablename){
             $query="select ".$to_fetch."  from ".$tablename." where ".$where_cond;
             $res=mysqli_query($GLOBALS['con'],$query);
             return $res;
        } 
		 function select_record_max($to_fetch,$where_cond,$tablename){
             $query="select ".$to_fetch."  from ".$tablename." where ".$where_cond;
			 //echo $query;
			// exit;
             $res=mysqli_query($GLOBALS['con'],$query);
             $fet=mysql_fetch_assoc($res); 
             return $query;
        }
        function select_mul_records($to_fetch,$where_cond,$tablename){
           $query="select ".$to_fetch."  from ".$tablename." where ".$where_cond;
           $res=mysqli_query($GLOBALS['con'],$query);
          // echo $res;
           return $res;
        } 
		
		  function trunk_table($tablename){
             $query="TRUNCATE ".$tablename;
             $res=mysqli_query($GLOBALS['con'],$query);
             return $res;
        } 
		
		
		
        function delete_record($condition,$tablename){
           $query="delete from ".$tablename." where ".$condition;
          // echo $query;exit;
           $res=mysqli_query($GLOBALS['con'],$query);
           return $res;
        }
		       function delete_record_max($condition,$tablename){
           $query="delete from ".$tablename." where ".$condition;
          // echo $query;exit;
          // $res=mysqli_query($GLOBALS['con'],$query);
           return $query;
        }
		
        function update_record($field_string,$condition,$tablename){
           $query="update ".$tablename." set ".$field_string." where ".$condition;   
           //echo $query;
		   $res=mysqli_query($GLOBALS['con'],$query);
           return $res; 
        }
		function update_record_max($field_string,$condition,$tablename){
           $query="update ".$tablename." set ".$field_string." where ".$condition;   
           //echo $query;
		 //  $res=mysqli_query($GLOBALS['con'],$query);
           return $query; 
        } 
        function get_multi_rows($res){
             $row_cnt=mysqli_fetch_array($res);
             return $row_cnt;
         }
		 
         function get_sgn_rows($res){
             $row_cnt=mysqli_fetch_assoc($res);
             return $row_cnt;
         }
		 
        function get_num_rows($res)
        {
     
             $row_cnt=mysqli_num_rows($res);
             return $row_cnt;
        } 
        function get_last_insertid()
         {
            $last_id=mysqli_insert_id($GLOBALS['con']);
            return $last_id;
         }   
        function getmaxid($column,$table)
         {
              $query = "select max($column) as maxid from $table";
              $result=mysqli_query($GLOBALS['con'],$query);
              $fetch=mysqli_fetch_array($result);
              $maxid = $fetch['maxid'];
              return $maxid;
         }
		
		/*---------------------Start of SQL INJECTION PROTECTION---------------------------*/
        function SecureAttack($string)
        {     
          $cleanstring = mysql_real_escape_string($string);
          return $cleanstring;
        }
		
		
/*---------------------End of SQL INJECTION PROTECTION---------------------------*/



?>
