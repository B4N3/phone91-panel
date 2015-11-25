<?php include_once("/home/voip91/public_html/newapi/panel_config.php");
if(isset($_SESSION['loginUrl']) && $_SESSION['loginUrl'] != '')
{ $loginUrl=$_SESSION['loginUrl'];}
	else{?>
		 <script>
            self.parent.location.href = '../index.php';
        </script>
                
              <?  }
$session_id=session_id();        
session_destroy();
session_write_close();
session_unset();
session_commit();
unset($_SESSION);
$res=DeleteSessionID($session_id);?>

        
        
       
