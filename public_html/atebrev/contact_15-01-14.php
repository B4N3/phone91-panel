<?php
/**
 * @author Rahul Chordiya <rahul@hostnsoft.com>
 * @updated Sameer Rathod <sameer@hostnsoft.com>
 * @Design Lovey Gorahpuriya <lovey@hostnsoft.com>
 * @since 07 Aug 2013
 * @last update 5-9-2013
 * @details Firest Page of user login Contains phonebook and two way calling with recent call log
 */

include_once("classes/phonebook_class.php");
include_once("googleContactSync.php");

if (!$funobj->login_validate() ) {
    $funobj->redirect("index.php");
}
#get all contact detail 
$userid = $_SESSION['userid'];

//if ($funobj->getConfirmNumber($_SESSION["userid"]) == 0) {
//    echo "<script>window.location.href='#!setting.php|phone.php'</script>";
////    exit();
//}

$funobj->checkVerifyNoEmail($_SESSION['id']);

$pbookobj = new phonebook_class();
error_reporting(-1);
#add first contact no into (mongo) phonebook table 
$pbookobj->addMeContact($userid);
extract($pbookobj->getAllContact($userid));
$currency = $funobj->getCurrencyName($_SESSION['currencyId']);
# check user has confirm mobile no or not if yes then go to current page otherwise confirm mobile number .  


//echo json_encode($allcontact);
?>
<script type="text/javascript">
    // get all the contact of user in json format 
    currency = '<?php echo $currency; ?>';
    allcontact = <?php echo json_encode($allcontact); ?>;
</script>

<!--Left Section-->
<div id="leftsec" class="cntLeft slideLeft">
	<!--Column Inner Section-->
	<div class="innerSection"> 
		<input class="linp" type="text" placeholder="Search Contact" id="searchContact"/>
		<a class="btn btn-large btn-primary btn-block clear" href="javascript:void(0);" id="addNewCnt" title="Add New Contact">
			<div class="clear tryc tr1"> 
				<span class="ic-24 addW"></span> 
				<span>Add New Contact</span> 
			 </div>
		</a>
	  <?php if (count($allcontact) < 1) { ?>
			<div class="box-sync mrB2">
				  <h2 class="h2 fwN">Sync Contacts</h2>
				  <div class="mrT2"> <a class="btn btn-medium btn-danger" href="<?php echo $loginUrl; ?>" title="Gmail">Gmail</a></div>
				<!--<div class="mrT2"> <a class="btn btn-medium btn-info" href="javascript:void(0);" title="Outlook">Outlook</a></div>-->
	  </div>
	  <?php } ?>
	  <!--Add List-->
	  <ul class="cntList ln">
		<?php  foreach ($allcontact as $cont) { ?>
		  <li class="clear default"  contactId="<?php echo (string) $cont['contact_id']; ?>" >
			  <div class="cntInfo slideAndBack" onclick="$('#dest').val('<?php echo $cont['contactNo']; ?>');">
					<div class="innerCol">
						  <h3 class="h3 ellp fwN"><?php echo $cont['name']; ?></h3>
						  <div class="clear fpinfo"> <i class="ic-16 call"></i>
							 <label><?php echo $cont['contactNo']; ?></label>
						  </div>
					</div>
		   </div>
		   <div class="cntAct  fixed">
				<div class="edtsiWrap">
					 <a class="btn btn-medium btn-primary btn-block clear alC" style="cursor:inherit; background:inherit"  href="javascript:void(0);" >
						  <span class="ic-32 edit"></span> 
					 </a> 
				 </div>
				<div class="hoveredtsiWrap"> 
						<a class="btn btn-medium btn-primary btn-block clear alC" onclick="showContactEdit(this);" contactId="<?php echo (string) $cont['contact_id']; ?>" 
						href="javascript:void(0);">
							  <span class="ic-32 edit"></span> 
						 </a> 
				</div>
			  </div>   
			</li>
		<?php } ?>
	  </ul>
	  <!--//Add List-->
	  <!--Add Contact Dialouge Box-->
	  <div id="add-contact-dialog" class="dn" title="Add New Contact">
		<form id="contact_detail">
		  <div id="add-cnt-inner">
			<div class="clear">
			  <div class="col-1-3">
				<div class=" add-cnt-form">
				  <div class="clear row">
					<div class="child">
					  <p class="mrB">Name</p>
					  <div class="">
						<input type="text" class="name" name="name[]" />
					  </div>
					</div>
					<div class="child">
					  <p class="mrB">Contact</p>
					  <div class="">
						<input type="text" class="contact" name="contact[]" />
					  </div>
					</div>
					<div class="child">
					  <p class="mrB">Email</p>
					  <div class="">
						<input type="text" class="email" name="email[]"/>
					  </div>
					</div>
					<div class="child ie">
					  <p class="mrB">&nbsp;</p>
					</div>
				  </div>
				 <a onclick="addMoreRow();" class="cp addmorelink themeLink" title="Add More">Add More</a>
				  <div class="mrT2 "> 
						<a class="btn btn-medium btn-primary isInput15" onclick="addcontact();" href="javascript:void(0);" title="Add">Add</a>
				  </div>
				</div>
			  </div>
			  <div class="col-3-4 ">
				<div class="syncontact">
				  <h3 class="h3 fwN">Sync Contacts</h3>
				  <div class="mrT2"> <a class="btn btn-medium btn-danger isInput45 alC  gmail" alC href="<?php echo $loginUrl; ?>" title="Gmail">Gmail</a></div>
				  <!--<div class="mrT2"> <a class="btn btn-medium btn-info isInput45 alC" href="javascript:void(0);" title="Outlook">Outlook</a></div>-->
				</div>
			  </div>
			</div>
		  </div>
		</form>
	  </div>
	  <!--//Add Contact Dialouge Box-->
 </div>
 <!--//Column Inner Section-->
</div>
<!--//Left Section-->

<!--Mid Section-->
<div id="midsec" class="cntMid slideRight">	
	<!--Column Inner Section-->
	<div class="innerSection">             	
		<a href="javascript:dynamicPageName('Contacts');" class="back btn btn-medium btn-primary hidden-desktop backPhone recCallAff fl" title="Back">Back</a>
		<a href="javascript:$('#recentCallsWrp').toggle();" class="hidden-desktop btn btn-medium btn-primary fl" title="Back">Recent Calls</a>
		<div class="cl"></div>
		
		<div id="recentCallsWrp" class="hidden-tablet"></div>	
		
		<h2 class="h2 fwN mrB2 hidden-tablet">Two way calling</h2>
		 <!--<div id="response" class="error_red"></div>-->
		 <div class="box-twoWay">
				<div id="response" class="mrB1 tTc"> </div>
				<p class="mrB">Your Number</p>
				<input type="text" id="source" class="isInput35Fix"/>
			   <p class="mrT2 mrB">Destination Number</p>
				<input type="text" id="dest" onkeyup="callcostKeyup();" class="isInput35Fix"/>
				<input class="btn btn-medium btn-primary mrR1 mrT2" type="button" onclick="clicktocall();" name="call" id="call" value="Call" title="Call"/>
				<!--<input class="mrT2 btn btn-medium btn-primary" type="button" onclick="showcosts();" name="showcost" id="showcost" value="see costs" />-->
				<label id="callrateDtl"></label>  
			   <div class="notewrap mrT2 clear"> 
						<span class="ic-32 notif"></span> 
						<span class="noteinfo">Your phone will ring first. Wait for a few seconds, then the destination number will ring. <br />Charges will be applicable for two calls - one for the destination number and one for your number.</span> 
			   </div>
		 </div>
	</div>
	<!--Column Inner Section-->
</div>
<!--//Mid Section-->

<!--Right Section-->
<div id="rightsec" class="cntRight hidden-tablet ">
	   <h2 class="h2 fwN mrB2">You can also call via</h2>
		<p class="viamsg"></p>
		<div class="box-sync">
			<h3 class="h3 fwN mrB1">Skype</h3>			
			<ul class="howPoints">
				<li>1. Go to the <strong>'Settings'</strong> option in your Phone 91 account and select <strong>'Add IM account'</strong>.</li>
				<li>2. Add your <strong>Skype ID</strong>.</li>
				<li>3. Log in to your Skype account with the Skype ID you have added and add <strong>'voip91.com'</strong> to your contacts. </li>
				<li>4. You will be added immediately. Press the <strong>'call'</strong> button. </li>
				<li>5. Once connected, go to the dialpad and enter the <strong>destination number</strong> where you wish to call. </li>
				<li>6. What else? Enjoy talking!</li>
				<li><a href="" class="db themeLink clrTrns">See how</a></li>
			</ul>
			<h3 class="h3 fwN mrB1">Gtalk</h3>
			<ul class="howPoints">
				<li>1. Go to the <strong>'Settings'</strong> option in your Phone 91 account and select <strong>'Add IM account'</strong>.</li>
				<li>2. Add your <strong>Gtalk ID</strong>.</li>
				<li>3. Log in to your Gtalk account with the Gmail ID you have added and add <strong>'phone@phone91.com'</strong> to your contacts. </li>
				<li>4. Start a chat with Phone 91 and type <strong>'Register &lt;your Phone 91 username&gt; &lt;your Phone 91 password&gt;'</strong></li>
				<li>5. Now enter the <strong>destination Country code and number</strong> where you wish to call.</li>
				<li>6. Press the <strong>call button on top</strong> and start sharing!</li>
				<li><a href="" class="db themeLink clrTrns">See how</a></li>				
			</ul>
		</div>
			<!--<h3 class="h3 fwN mrB1">Talk for Android</h3>
			<h3 class="h3 fwN mrB1">Nimbuzz</h3>
			<h3 class="h3 fwN mrB1">Iphone by vtok</h3>-->
		</div>
</div>
<!--//Right Section-->

<div id="edit-contact-wrap-dialog" class="dn"> </div>

<script type="text/javascript">
dynamicPageName('Contacts')
slideAndBack('.slideLeft','.slideRight');
$('.slideAndBack').click(function(){
	dynamicPageName('Two way Calling');
})

    $(function() {
        $('#addNewCnt').click(function() {
            $("#add-contact-dialog").dialog({modal: true, resizable: false, width: 800, height: 400, 'title':'Add New Contact'});
        })

        $('#searchContact').quicksearch('.cntList li');
    });

    //console.log(allcontact);
    $.ajax({
        //function fetches the recent call list of the user 
        // return type is json which is iterated 3 times to get only three recent calls 
        url:"controller/userCallLog.php?call=recentCall",
        type:"POST",
        dataType:"JSON",
        success:function(msg)
        {
            if(msg != null)
            {
            var str ="";
            var i = 0; // loop counter
            //.each api to iterate thought the response json 
			str+='<h2 class="h2 fwN mrB2" id="recentCallHead" >Recent Calls</h2>\
				<ul class="recCallList ln mrB2" id="recentCallUl">';
				
            $.each(msg,function(key,value){
                if(value.record != "")
                {
                var callerIdName ="Unknown"; // Name of the caller 
                var calledName = "Unknown";  // Name of the contact to which the call is done 
                
                // allcontact is a global variable which consist of all the contact numbers of user in json format 
                // this is use to get the name to which the source and destination number is assoiciated if the number is 
                // not found in the user contacts then by default it will show the name unknown to the user
               
                    $.each(allcontact,function(k,v)
                    {
                        if(v.contactNo == value.record.caller_id)
                            callerIdName = v.name;
                        else if(v.contactNo == value.record.called_number)
                            calledName = v.name;
                    });

				str +='<li class="clear">\
                    <div class="col-3">\
						<h3 class="h3 ellp fwN pdB">'+callerIdName+'</h3>\
						<div class="clear fpinfo">\
							<i class="ic-16 call"></i>\
							<label>'+value.record.caller_id+'</label>\
							<input type="hidden" name="callFrom" id ="callFrom'+value.record.uniqueId+'" value="'+value.record.caller_id+'"/> \
						</div>\
                    </div>\
                    <div class="col-3">\
                        <h3 class="h3 ellp fwN pdB">'+calledName+'</h3>\
                        <div class="clear fpinfo">\
                            <i class="ic-16 call"></i>\
                            <label>'+value.record.called_number+'</label>\
                        <input type="hidden" name="callTo" id ="callTo'+value.record.uniqueId+'" value="'+value.record.called_number+'"/> \
                        </div>\
                    </div>\
                    <div class="col-3">\
                        <div class="TPlan"><span class="font28">'+value.balance+'</span> '+currency+'/Min</div>\
                        <div class="hoverCall">\
                            <a class="btn btn-medium btn-primary btn-block clear alC" href="javascript:void(0);">\
                                <div class="clear tryc tr1">\
                                    <span class="ic-24 callW"></span>\
                                    <span onclick="recallApi(\''+value.record.uniqueId+'\')">Recall</span>\
                                </div>\
                            </a>\
                        </div>\
                    </div>';                            
                                        //check to iterate only three times 
                    if(i == 2)
                    {
                        return false;
                    }
                        i++;
                    }
                })
				str+='</li></ul>';
                $('#recentCallsWrp').html(str);
                //$('.recCallAff').after('<h2 class="h2 fwN mrB2" id="recentCallHead" >Recent Calls</h2>');
            }
        }
                                          
    });
                                        
            function recallApi(Id)
            {
                // function is called when user click on recall button from the recent call list 
                // it will call the click to call api
                $("#source").val($('#callFrom'+Id).val());
                $("#dest").val($('#callTo'+Id).val());
                callcostKeyup();
                clicktocall();
            }
            function getDefaultNumber()
            {
                $.ajax({
                    url:"controller/userCallLog.php?call=getDefaultNumber",
                    type:"POST",
                    dataType:"JSON",
                    success:function(msg)
                    {
                        
                        $("#source").val(msg);
                    }
                })
            }
            $(document).ready(function(){
                getDefaultNumber();
            })


</script>