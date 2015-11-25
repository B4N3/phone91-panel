<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}
?>
<!--Register Id's  Wrapper-->
<a title="Back" class="back btn btn-medium btn-primary hidden-desktop backPhone" href="javascript:dynamicPageName('Settings');">Back</a>
<div id="wlids" class="fl">
	<!--Inner Container-->
	<div class="setContainer">
		<a class="btn  btn-primary btn-medium clear alC phoneaddNo email"  href="javascript:showAddAccbox();" title="Id's">
			<div class="clear tryc">
					<span class="ic-24 addW"></span>
					<span>Id's</span>
			</div>
		</a>
		<form id ="whiteLabelForm" action="">
		 <div id="addAccbox">
			  <p class="mrB">Type</p>
			 <select name="type" id="selType" >
					<option value="select">Select Account</option>
					<option value="1" >Gtalk</option>
					<option value="2" >Skype</option>
			</select>
			<p class="mrT2 mrB">Id</p>
				<input type="text" id="emailId" name="emailId" value=""/>
<!--                        <p class="mrT2 mrB">Password</p>
			 <input type="password" id="password" name="password"  value="<?php echo $idsData['password'];?>" />-->
				<input class="mrT2 btn btn-medium btn-primary"  value="Add Id" onclick="addRegisterId()" type="button" title="Add Id" /></div>
		</form>
		<!--Left Phone side-->
		<div class="leftPhone fixed fl mrR1 idsData">
			<ul class="ln" id="wlIdsList">
			</ul>
		</div>
             <!--//Left Phone side-->
            
    		<!--Right Phone side-->
     		 <div class="rightPhone fixed fl">
                   
    		    </div>
             <!--//Right Phone side-->
	</div>  
	<!--//Inner Container-->
</div>
<!--//White Label Id's  Wrapper-->
<script>
dynamicPageName('Registered IDs');
slideAndBack('.slideLeft','.slideRight');
    function fetchRegisterIdDetails()
    {
    $.ajax({
        url:"controller/settingController.php?call=getRegisterIdDetails", 
        type:'POST',
        dataType: 'json',
        success:function(response)
        {
           var str = "";
           if(response != null)
           {
               $.each(response , function(key,value){
               var subStr = "";
               var deletestr = "";
               if(value['confirm'] == 0)
               {
                   subStr = '<p class="acName">Confirm code :: '+value['code']+'</p>';
                   deletestr = '<a title="Delete" onclick="deleteIds(\''+value['email']+'\',\''+value['type']+'\',\''+value[0]+'\',\'temp\');"><span class="ic-24 actdelC cp">';
               }
               else
               {
                   deletestr = '<a title="Delete" onclick="deleteIds(\''+value['email']+'\',\''+value['type']+'\',\''+value[0]+'\');"><span class="ic-24 actdelC cp">';
               }
               console.log(value);
               if(value['type'] == 'skype')
                   var info = "Please add 'voip91.com' in your skype contact";
               else if(value['type'] == 'gtalk')
                   var info = "Please add 'phone@phone91.com' in your gtalk contact";
               
              
               
               str += '<li id="wlbl_'+value[0]+'" class="default" ">\
                                    <p class="acName">'+value['email']+'</p>\
                                    <p class="acProvider">'+value['type']+'</p>\
<?php if($_SESSION['chainId'] == '1111' || substr($_SESSION['chainId'],0,-4) == '1111'){ ?><p class="acProvider">'+info+'</p>\<?php }?>
                                    '+deletestr+' </span></a>\ '+subStr
                               +' </li>';
                });
//                console.log(str);
                $('#wlIdsList').html(str);
           }
        }
    })
    }
    
fetchRegisterIdDetails();

function addRegisterIdValidate(element){
 if(/[^a-zA-Z0-9\.\_\@\-\$]+/.test(element) || element == "")
    return false
 else
     return true;
}
function addRegisterId()
{
    var emailId = $('#emailId').val();
    var type = $('#selType').val();
    if(!addRegisterIdValidate(emailId))
    {
        show_message("Error Invalid Id","error");
        return false;
    }
        
    $.ajax({
        url:"controller/settingController.php?call=addRegisterIds",
        type:"post",
        dataType:"json",
        data:{"emailId":emailId,"type":type,"call":"addRegisterIds"},
        success: function(response)
        {
            if(response.status == "success")
                $('#emailId').val('');
            show_message(response.msg,response.status);
            fetchRegisterIdDetails();
        }
    })
}
 

function deleteIds(id,type,sno,temp){
var tempValue = 0;
if(temp == "temp")
    tempValue = 1;
    $.ajax({
                   url : "controller/settingController.php?call=deleteRegisterIds",
                   type: "POST", 
                   data:{id:id,type:type,temp:tempValue},
                   dataType: "json",
                   success:function (response)
                   {
                       show_message(response.msg,response.status);
                       $('#wlbl_'+sno).hide();
                   }
    })
}    
</script>