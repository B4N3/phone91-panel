<?php
//Include Common Configuration File First
include_once('config.php');
//Validate User Login by funtion
//Validate Reseller
class reseller extends SuperMySQLi{
    public function __construct() {
        if($_SERVER['HTTP_HOST'] == 'testing2.phone91.com')
            $this->db = new SuperMySQLi('localhost', 'voip91_switch', 'yHqbaw4zRWrUWtp8', 'voip91_switch');
        else
            $this->db = new SuperMySQLi('localhost', 'phone91', 'yHqbaw4zRWrUWtp8', 'voip');
    }
    function getResellerSetting($userid){
        $table = '91_resellerSetting';
        $this->db->select('mobile,email')->from($table)->where("userId=".$userid);
        $this->db->getQuery();
        $result = $this->db->execute();    
        //echo $result->num_rows;
       
        if ($result->num_rows > 0) {
            $row=$result->fetch_array(MYSQL_ASSOC) ;
            $setting = $row;
        }
        else{
            $setting['mobile']=0;
            $setting['email']=0;
        }
            return $setting;
    }
}
$resellerObj=new reseller();
$userSetting=$resellerObj->getResellerSetting($_SESSION['userid']);

if($userSetting['mobile']==1){
   $mobileChecked="checked=checked"; 
}else
    $mobileChecked = '';
if($userSetting['email']==1){
   $emailChecked="checked=checked"; 
}else
    $emailChecked='';
?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>

<div class="setContainer settRightSec">
	<!--<h2 class="headSetting">Reseller Settings</h2>-->
   <div class="whiteWrapp">
	<p class="mrB1 semi">Do you want to verify your client's Email account at the time of Sign-up?</p>
	<div class="clear resboxrow">
		<input class="changeResellerSettings" name="email" id="email" type="checkbox" <?php echo $emailChecked;?> title="Email"/> 
		<label for="email" title="Email">Email</label>
	</div>
<!--	<div class="clear resboxrow">
		<input name="" type="radio" value="" />
		<label for="" >Yes</label>
		<input name="" type="radio" value="" />
		<label for="" >No</label>
	</div>-->
	<div class="clear resboxrow">
		<input class="changeResellerSettings" name="mobile" id="mobile" type="checkbox" <?php echo $mobileChecked;?> title="Mobiles" /> 
		<label for="mobile" title="Mobiles">Mobiles</label>
	</div>
	</div>
</div>

<!--//Reseller  Wrapper-->
<script type="text/javascript">
dynamicPageName('Reseller Settings')
slideAndBack('.slideLeft','.slideRight');
    $(document).ready(function(){
    $(".changeResellerSettings").click(function(){
                var keyValue=0;
                if($(this).is(':checked'))
                    keyValue=1
		$.ajax({
		url:"action_layer.php?action=changeResellerSettings",
		type:"POST",
                dataType:'json',
		data:"key="+this.id+"&value="+keyValue,
//                $("#"+this.id).val(),
                success: function(msg)
                    {
                             $("#loading_img").hide();
                             show_message(msg.msg,msg.msg_type);
                    }
		})
            })
    });
</script>
