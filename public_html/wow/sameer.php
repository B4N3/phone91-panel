<?php
//include_once('config.php');
//include_once(CLASS_DIR.'callLog_class.php');
//$callObj = new log_class();
//$date = date('Y-m-d',  strtotime("-30 days"));
////$response = $callObj->getStatusDetails('1111',$date);
//$response = $callObj->getResProfitDurationGraphDetails('1111',$date,"duration");
//$decode = json_decode($response,true);
//print_R($decode);
//foreach($decode as $key => $val)
//{
//    
//        print_r($val);
//}

//$avarageCallDuration = $decode['sum']['avgCallDuration'];
//    print_r(date("H:i:s",mktime(0,0,$avarageCallDuration)));
?>
<input type="text" placeholder="search User" id="searchPlanUser" name="searchUser" onkeyup="searchPlanList($(this))"/>
<div class="slideLeft contentHolder" id="leftsec">
    
    <ul class="mngClntList">
                <li onclick="window.location.href='#!tariff-plan.php|tariff-plan-setting.php?clientId=31995'">
                        <div class="tariff">
                                        <h3 class="blackThmCrl">Testplan</h3>
                                        <p>Total Plans: <span class="font15">60</span></p>
                                        <p class="clear mrT1">
                                                <span class="fl blackThmCrl font15">INR</span>
                                                <span class="fr">Billing: <span class="font15">60</span></span>
                                        </p>
                            </div>
        	  </li>
              <li onclick="window.location.href='#!tariff-plan.php|tariff-plan-setting.php?clientId=31995'">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>Total Plans: <span class="font15">60</span></p>
							<p class="clear mrT1">
								<span class="fl  font15">INR</span>
								<span class="fr">Billing: <span class="font15">60</span></span>
							</p>
					  </div>
        	  </li>
              <li onclick="window.location.href='#!tariff-plan.php|tariff-plan-setting.php?clientId=31995'">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>Total Plans: <span class="font15">60</span></p>
							<p class="clear mrT1">
								<span class="fl  font15">INR</span>
								<span class="fr">Billing: <span class="font15">60</span></span>
							</p>
					  </div>
        	  </li>
              <li onclick="window.location.href='#!tariff-plan.php|tariff-plan-setting.php?clientId=31995'">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>Total Plans: <span class="font15">60</span></p>
							<p class="clear mrT1">
								<span class="fl  font15">INR</span>
								<span class="fr">Billing: <span class="font15">60</span></span>
							</p>
					  </div>
        	  </li>
      </ul>
</div>

<script>
    
function getPlanList(search)
{
    $.ajax({
        url:"/controller/managePlanController.php"+((typeof search == 'undefined' ||search == null )?"":search),
        data:{"call":"managePlan"},
        dataType:"json",
        success: function(response)
        {
            var str="";
            if(response.allvalue == "" || response.allvalue == null || typeof response.allvalue ==='undefined')
            {
                str += "<li>No plan available please add a Plan</li>";
//                window.location.href='#!reseller-manage-plan.php|reseller-add-plan.php'
            }
            else
            {
                if(response.allvalue != null && typeof response.allvalue !=='undefined')
                {                                       
                    $.each(response.allvalue, function(i, item){
                        
                       str +=  '<li onclick="window.location.href=\'#!tariff-plan.php|tariff-plan-setting.php?clientId='+item.id+'\'">\
                        <div class="tariff">\
                                        <h3 class="blackThmCrl">'+item.value+'</h3>\
                                        <p>Total Plans: <span class="font15">60</span></p>\
                                        <p class="clear mrT1">\
                                                <span class="fl blackThmCrl font15">'+item.currency+'</span>\
                                                <span class="fr">Billing: <span class="font15">'+item.billInSec+'</span></span>\
                                        </p>\
                            </div>\
        	  </li>';
                    })
                }
                $('.mngClntList').html(str);
            }
            
        }
    })
}
function searchPlanList(ths)
{
    var keyword = ths.val();
    getPlanList("?planName="+keyword+"&mnpln=search");
}
getPlanList();
</script>
