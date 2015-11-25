<style>
    #fundtransfer input, #password-dialog input{width:100%;}
    #fundtransfer .pr span{position:absolute; top:6px; right:10px;}
    #fundtransfer .urBox{border:1px solid #68bada;}
    .yellBox{background-color:#fff5d8; border:1px solid #fce39b; color:#a39671; width:300px}
</style>

<?php
//Include Common Configuration File First
include_once('config.php');

if (!$funobj->login_validate() ) 
{
    $funobj->redirect("index.php");
}

?>

<!--Change Password  Wrapper-->
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>

<!--Inner Container-->
    <div class="setHead clear">
    	<h3>Fund Transfer</h3>
    </div>

	<div class="whiteWrapp mrT2 mrR2 fl" style="width:300px">
            <form id="fundtransfer">
                    <p class="mrB">TRANSFER TO<sup>*</sup></p>
                    
                  
                    
                    <div id = "userInfoError" class="red" style="display:none;"> Please Enter Valid User Information. </div>
                    <input type="text" placeholder="Username or mobile no." name="transferTo" id="transferTo" onblur="getUserCurrencyAndRate()" />
                    
                    <input type ="hidden" name="recCurrency" id="recCurrency" >
                    <input type ="hidden" name="recRate" id="recRate" >
                    <input type="hidden" name="confirmAmount" id="confirmAmount">
                    <input type="hidden" name="receiverId" id="receiverId">
                    
                    <p class="mrT2 mrB">TRANSFER AMOUNT<sup>*</sup></p>
                        <div class="pr">
                            <div id = "amountError" class="red" style="display:none;"> Please Enter Valid Amount. </div>
                            <input type="text" placeholder="Enter amount" name="amount" id="amount" />
                        <span><?php echo $_SESSION['currencyName']; ?></span>
                        </div>
                    <div class="urBox mrT2 pd1">USER WILL RECEIVE <span class="blue"  id="receivingAmt"> 0 <?php echo $_SESSION['currencyName']; ?> </span></div>
                    <input class="mrT2 btn btn-medium btn-blue clear" type="button" name="transfer" id="transfer" value="TRANSFER"/>
            </form>
	 </div>
     
        <div class="yellBox pd1 mrT2 fl">Transfer funds from one account to 
            another by filling in the Username or
            Mobile no. of other phone91 user. The 
            amount entered will be converted into
            user's currency and will be automatically
            transferred  when you click transfer
        </div>

<!--password conformation popup-->
<div id="password-dialog" class="dn">
	<div class="pd2">
            <form  action="javascript:void(0);" id="addNumber"  />
        		<p class="mrB alC">PLEASE ENTER YOUR PASSWORD BEFORE TRANSFERRING AMOUNT<sup>*</sup></p>
                 <div id = "passwordError" class="red" style="display:none;"> Please Enter Valid Password. </div>        
                 <input class="mrT1" type="password" placeholder="********" name="password"  id="password" />
                <input class="mrT2 btn btn-medium btn-blue clear" type="submit" name="transfer" id="finalTransfer" value="CONFIRM TRANSFER" onclick="transferFund()"/>
        </form>
    </div>
</div>


<!--//Change Password  Wrapper-->
<script type="text/javascript">
   
$('#amount').on('keyup keypress blur onchange onclick onfocus', function(event) {
   
   updateAmount();
   
});
   
 function transferFund()
 {
    var receiverId = $('#receiverId').val(); 
    var password = $('#password').val(); 
    var amount = $('#amount').val(); 
    var confirmAmount = $('#confirmAmount').val(); 
    
//    console.log(password);
//    console.log(password.length);
//    if(password.length < 8 )
//    {
//       $('#passwordError').show();
//       $('#password').addClass('error');
//       return;
//    }
//    else
//    {
//        $('#passwordError').hide();
//        $('#password').removeClass('error');
//    }

    $.ajax({
     url:"action_layer.php?action=transferFund",
     type:"POST",
     data:"amount="+amount+"&receiverId="+receiverId+"&confirmAmount="+confirmAmount+"&password="+password,
    dataType:"JSON",
     success:function(data)
     {
        show_message(data.msg, data.status);
        
            if(data.status == 'success')
            setTimeout($(function(){location.reload();}), 1200); 
     } 
    });
    
    
 }
        
function getUserCurrencyAndRate()
{

    var transferTo = $('#transferTo').val(); 



    $.ajax({
        url:"action_layer.php?action=getUserCurrencyAndRate&userInfo="+transferTo,
        type:"POST",

        dataType:"JSON",
            success:function(data)
            {
               console.log(data.content);
               console.log('nidhi');
                if(typeof(data.content) != "undefined") 
                {
                    $('#recCurrency').val(data.content.currency);
                    $('#recRate').val(data.content.rate);
                    $('#receiverId').val(data.content.userId);
                     
                    var amount = $('#amount').val();

                    if(amount.length > 0)
                    {
                        var newAmt = parseInt(amount)*data.content.rate;   
                        
                        if(newAmt == 'NaN')
                         newAmt = 0;
                     
                     //toFixed(2)
                         newAmt = newAmt.toFixed(2);
                        $('#receivingAmt').text(newAmt+'  '+data.content.currency);
                        $('#confirmAmount').val(newAmt);
                       
                       
                        
                    }
                     $('#userInfoError').hide();
                     $('#transferTo').removeClass('error');
                    
                }
                else
                {
                    $('#userInfoError').show();
                    $('#transferTo').addClass('error');
                  
                }
            } 
        })
}


function updateAmount()
{
    var amount = $('#amount').val();
    
    var accAmount = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
    var recCurrency = $('#recCurrency').val();
    
    if(!accAmount.test(amount))
    {
        $('#amountError').show();
        $('#amount').addClass('error');
        $('#receivingAmt').text("0 "+recCurrency);
        $('#confirmAmount').val("0");
        return;
    }
    else
    {
         $('#amountError').hide();
         $('#amount').removeClass('error');
    }
    
    var recRate = $('#recRate').val();
   
    if(amount.length > 0)
    {
       var newAmt = parseInt(amount)*recRate;   

        if(!accAmount.test(newAmt) || recRate.length < 1 )
        {
            $('#userInfoError').show();
            $('#transferTo').addClass('error');
            return;
        }
        else
        {
             $('#userInfoError').hide();
             $('#transferTo').removeClass('error');
        }
       newAmt = newAmt.toFixed(2);
       $('#receivingAmt').text(newAmt+'  '+recCurrency);
       $('#confirmAmount').val(newAmt);
    }

}

//password popup
$('#transfer').click(function() 
{
    var amount = $('#amount').val();
    var confirmAmount =  $('#confirmAmount').val();
    
    var accAmount = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
    
    var userInfo = $('#transferTo').val(); 
    
    if(userInfo.length <= 0  )
    {
       $('#userInfoError').show();
       $('#transferTo').addClass('error');
       return;
    }
    else
    {
         $('#userInfoError').hide();
         $('#transferTo').removeClass('error');
         
    }
    
    if(!accAmount.test(amount))
    {
        $('#amountError').show();
        $('#amount').addClass('error');
        return;
    }
    else
    {
        $('#amountError').hide();
        $('#amount').removeClass('error');
       
    }
    
    var recRate = $('#recRate').val();
    
    //console.log(recRate);
    //return;
    
    if(!accAmount.test(recRate))
    {
        $('#userInfoError').show();
       $('#transferTo').addClass('error');
       return;
    }
    else
    {
        $('#userInfoError').hide();
        $('#transferTo').removeClass('error');
       
    }
    
    
    
    
    $("#password-dialog").dialog({modal: true, resizable: false, width: 350, height: 300, 'title':'Password Conformation'});
})  
    
   
    
    

</script>

 