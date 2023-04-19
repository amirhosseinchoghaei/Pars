<?php
include_once "../controller/route.php";
 $agentDetails = getAgentDetails();
 if($agentDetails['loggedIn']['user_id'])
 {
$loggedInCount = count($agentDetails['loggedIn']['user_id']);
 }else
 {
		$loggedInCount = 0;
 }
 if($agentDetails['loggedOut']['user_id'])
 {
$loggedOutCount = count($agentDetails['loggedOut']['user_id']);
 }else{
		$loggedOutCount = 0;
 }
 $unreadSMS = getAllUnreadSMSDetails();
 $unreadSMSCOunt = $unreadSMS['count'];
 $smsNotificationStyle='danger';
 if($unreadSMSCOunt == 0)
 {
	 $smsNotificationStyle="success";
	 $smsNotificationback="green";
 }else
 {
	 $smsNotificationStyle="danger";
	 $smsNotificationback="red";
 }
?>
<ul class="nav navbar-nav">








  <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope"></i>
                  <span class="label label-<?php echo $smsNotificationStyle; ?>"><?php echo $unreadSMSCOunt; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header" style='background-color:<?php echo $smsNotificationback; ?>;color:white;'><?php echo $unreadSMSCOunt; ?> Unread SMS</li>
                 
                  <li class="footer"><a href="new_sms.php">Check Unread SMS</a></li>
                </ul>
              </li>











              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user-o"></i>
                  <span class="label label-success"><?php echo $loggedInCount; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"><?php echo $loggedInCount; ?> Logged In User</li>
                  <li>
                    <ul class="menu">
					
					<?php 
					for($i=0;$i<$loggedInCount;$i++)
					{
						if($agentDetails['loggedIn']['profile_pic'][$i] == "")
						{
							$agentDetails['loggedIn']['profile_pic'][$i] = "../image/avtar.png";
						}
					?>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="<?php echo $agentDetails['loggedIn']['profile_pic'][$i]; ?>" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            <?php echo $agentDetails['loggedIn']['name'][$i]; ?>
                            <small><i class="fa fa-phone"></i> <?php echo $agentDetails['loggedIn']['extension'][$i]; ?></small>
                          </h4>
                          <p><?php echo $agentDetails['loggedIn']['designation'][$i]; ?></p>
                        </a>
                      </li>
					<?php  } ?>
                    </ul>
                  </li>
                  
                </ul>
              </li>

             <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-user-o"></i>
                  <span class="label label-danger"><?php echo $loggedOutCount; ?></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header"><?php echo $loggedOutCount; ?> Logged Out User</li>
                  <li>

                    <ul class="menu">
                      <li>

                    <ul class="menu">
					
					<?php 
					for($i=0;$i<$loggedOutCount;$i++)
					{
						if($agentDetails['loggedOut']['profile_pic'][$i] == "")
						{
							$agentDetails['loggedOut']['profile_pic'][$i] = "../image/avtar.png";
						}
					?>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="<?php echo $agentDetails['loggedOut']['profile_pic'][$i]; ?>" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            <?php echo $agentDetails['loggedOut']['name'][$i]; ?>
                            <small><i class="fa fa-phone"></i> <?php echo $agentDetails['loggedOut']['extension'][$i]; ?></small>
                          </h4>
                          <p><?php echo $agentDetails['loggedOut']['designation'][$i]; ?></p>
                        </a>
                      </li>
					<?php  } ?>
                    </ul>
                  </li>
                     
                    </ul>
                  </li>
                  
                </ul>
              </li>
			  </ul>