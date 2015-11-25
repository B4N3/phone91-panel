<?php

//Include Common Configuration File First
include_once('config.php');
//include_once(CLASS_DIR.'setting_class.php');

$reuslt = array();
if(isset($_SESSION['userid']))
  $result = $funobj->getUserBalanceInfo($_SESSION['userid']);

$checked = '';
if(!empty($result) && $result['getMinuteVoice'] == 1)
{
  $checked = 'checked';
}

?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<div id="addemails">
<div class="setContainer">
    <!--Left Phone side-->
    <div id="listenRemainingMin" class="cp" style="position: relative;top: 16px;width: 600px;height: 107px;left: 17px;">
                <input type="checkbox" id="listenStatus" <?php echo $checked; ?> onchange="changeListenStatus(<?php echo $_SESSION['userid']; ?>);">
                <label for="listenStatus" class="mrL cp">Listen the remaining time during the call.</label>
    </div> 
</div>
    	<!--//Left Phone side-->
        <!--Right Phone side-->
        <div class="rightPhone fixed fl"> 
    
</div>  
</div>
    <!--//Inner Container-->
</div>
<!--//Email Wrapper-->
<script type="text/javascript">
dynamicPageName('Common Settings');

function changeListenStatus(userId)
{
    
  var type = 1;
  //var unverifyid = $('#listenStatus').is('checked');
  console.log($('#listenStatus').is('checked'));
  if($('#listenStatus').is(':checked'))
    type = 0;



  $.ajax({
  	    url : "/controller/settingController.php?call=changeListenStatus",
  	    type: "POST",
              dataType: "json",
  	    data: {type:type,
               userId:userId},
  	    success: function(text)
              {
                  show_message(text.msg,text.status);
                  //if deleted then take hide action
                  if(text.msgtype == "success")
                  {
                      //connecting the id=ide and email id number 
                      //$('#unverify').remove();
                      
                      
                      
                  }
              
              }
         }); 
}

</script>

