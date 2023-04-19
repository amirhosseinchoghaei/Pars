<!DOCTYPE html>
<?php
include_once("controller/route.php");

if($_SESSION['tech_admin_id'])
{
	echo "<script>location.href='index.php';</script>";
}
$user_id=checkAnyAdminPresent();
if(!$user_id)
{
	echo "<script>location.href='registration.php'</script>";
}


?>
<html>
  <head>
    <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="dist/login/bootstrap.min.css">
	<link rel="stylesheet" href="dist/login/fontawesome-all.min.css">
	<link rel="stylesheet" href="dist/login/style.css">
	<link rel="stylesheet" href="dist/login/theme.css">
	
	<link rel="stylesheet" type="text/css" href="toastr/toastr.css">
	<link rel="icon" href="image/favicon.ico" type="image/x-icon">
  </head>
  <body>

  
   <div class="form-body">
        <div class="row">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <h3>Auto Dialer System</h3>
                    <p>Powered By AmirHossein</p>
                    <img src="dist/login/graphic5.svg" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="website-logo-inside">
                            <a href="index.php">
                                <div class="logo">
                                    <img class="logo-size" src="dist/images/techextension-logo.png" alt="">
                                </div>
                            </a>
                        </div>
						<h3>Login</h3>
                        <div class="page-links">
                            
                        </div>
                        <form action="login.php" method="POST" id='login'>
                            <input class="form-control" type="text" name="username" placeholder="UserName" required>
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn" name="login">Login</button>
                            </div>
                        </form>
                      <!--  <div class="other-links">
                            <span>Or login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
                        </div>
						-->
                    </div>
                </div>
				
            </div>

        </div>
    </div>


    <script src="../plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="dist/login/popper.min.js"></script>
	<script src="dist/login/main.js"></script>
<script type="text/javascript" src="toastr/toastr.min.js"></script>
  </body>
  </html>
<?php



  if(isset($_POST['login']))
	{
		$email = $_POST['username'];
		$password = $_POST['password'];
		
		$dataToSend = $email."*".$password;
		$result = CheckAdminLogin($dataToSend);
		//print_r($result);
		if($result['status'] >= "1")
			{
				//echo "<script>$('#email_error').hide();</script>";
				$_SESSION['tech_admin_id'] = $result['data']['user_id'];
				$_SESSION['user_extension'] = $result['data']['extension'];
				$_SESSION['user_channel'] = $result['data']['channel'];
				$_SESSION['asteriskip'] = $result['data']['asterisk_ip'];
				echo "<script>location.href='index.php';</script>";
			}else if($result['status'] == "0")
			{
				//echo "<script>$('#email_error').show();</script>";
				echo "<script>toastr['warning']('Wrong Credentials')</script>";
			}  
		}
		
		
?>

