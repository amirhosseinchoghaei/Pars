<?php
session_start();
if(!empty($_SESSION['tech_admin_id']))
{
	unset($_SESSION["tech_admin_id"]);
	unset($_SESSION["ActualIP"]);
	unset($_SESSION["user_extension"]);
	unset($_SESSION["user_channel"]);
	//session_destroy();
}
//session_destroy();
header("Location:../index.php");

?>
 