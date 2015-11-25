<?php
/**
 * @lase updated by Ankit Patidar <ankitpatidar@hostnsoft.com> on 29/10/2013
 * @Description file contain code to show bandWidth limit in account manager log
 */

//include required files
include dirname(dirname(__FILE__)) . '/config.php';
//include_once(CLASS_DIR."adminUpdationLog_class.php");

if(isset($_REQUEST['pageNo']))
    $pageNo = $_REQUEST['pageNo'];
else
    $pageNo = 1;

//check login validate
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect(ROOT_DIR . "index.php");
}

$planId = $_REQUEST['planId'];

?>


<!--limited plan detail table -->
<div class="tablflip-scroll">
   
  <div>
  
      <h3 class="yelloThmCrl ellp" id="planName"></h3>
  </div>  
    </br>
    <div>
        <div class="clear mrT2">
        	
            <div class="actionDiv">
            	<p class="mrB">Prefix :</p>
                <input type="text" id="prefix" name="prefix" class="isInput250">
            </div>
            <div class="actionDiv">
            	<p class="mrB">Country :</p>
                <input type="text" id="country" name="country" class="isInput250">
            </div>
            <div class="actionDiv">
            	<p class="mrB">&nbsp;</p>
            	<input type="submit" class="mrL btn btn-medium btn-primary" name="Done" onclick="addPrefixAndCountry(<?php echo $planId;?>);" id="addprefixCnt" value="ADD" title="Done">
            </div>
            
        </div>
        
    </div>
    
    </br></br>
    
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="limitedPlanPrefixDetail" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">Country Prefix</th>
        <th width="10%" class="alC">Country Name</th>
      </tr>
    </thead>
    
    <tbody>
    
      <tr class="zerobal">
        <td colspan="100%"></td>
      </tr>
    </tbody>
  </table>
      <div id="pagination"></div>
</div>
<!--//Account Manager Edit Funds-->

<script type ="text/javascript">
 $(document).ready(function(){
     
     //code to give different color to even odd rows
       $("#changeTeriff tbody tr:visible:even").addClass("even"); 
       $("#changeTeriff tbody tr:visible:odd").addClass("odd");
       
    });
    
         /**
     * @author Ankit Patidar <ankitpatidar@hostnsoft.com>
     * @since 8/04/2014
     * @param {int} type 3 for change teriff
     * @returns void
     */
    function getlimitedPlanPrefix(planId)
    {
        $.ajax({
            url:'/controller/adminManageClientCnt.php?action=getLimitedPlanPrefix',
            type: "POST", 
            dataType: "json",
            data:{planId:planId},
            success:function(data){
                
                var str = '';
                if(data.status == "success"){
                var limitedPlan = data.limitedPlanPrefix;    
                
                $.each(limitedPlan,function(index,value){
                   
                   str+="<tr class=''>\
                            <td>"+value.countryPrefix+"</td>\
                            <td class='alC'>"+value.CountryName+"</td>\
                         </tr>";
                    
                });
              $('#limitedPlanPrefixDetail tbody').html(str); 
              $("#limitedPlanPrefixDetail tbody tr:visible:even").addClass("even"); 
              $("#limitedPlanPrefixDetail tbody tr:visible:odd").addClass("odd");
              
              
            
//                pagination(data.pages,pageNo,'#pagination',type);
            }
            $('#planName').html(data.planName);
        }
        });
        
        
    }
    
    getlimitedPlanPrefix(<?php echo $planId; ?>);
    
    
    function addPrefixAndCountry(planId){
        
        var prefix = $('#prefix').val();
        var country= $('#country').val();
        
        $.ajax({
            url:'/controller/adminManageClientCnt.php?action=addPrefixAndCountry',
            type: "POST", 
            dataType: "json",
            data:{planId:planId,prefix:prefix,country:country},
            success:function(data){
                
                show_message(data.msg,data.status);
                
                if(data.status == "success"){
                getlimitedPlanPrefix(planId);
                $('#prefix').val('');
                $('#country').val('')
                }
        }
        });
        
        
        
    }
    
            
</script>