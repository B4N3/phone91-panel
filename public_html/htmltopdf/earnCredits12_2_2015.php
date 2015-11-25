<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Earn Credits</title>
<script type="text/javascript" src="/js/ZeroClipboard.js"></script>
<?php 
 include_once 'config.php';
include_once('inc/head.php');
 
 if (!$funobj->login_validate() ) 
{
    $funobj->redirect("logout.php");
}

$totalbalance = $funobj->getTotalAmountEarned(  );


$userInfo = $funobj->getUserInformation($_SESSION['userId'], 1);
$currencyId = $funobj->getOutputCurrency($userInfo["tariffId"]);   
$currecncyName =  $funobj->getCurrencyName($currencyId);


?>

<style>
.mainBox{width:450px;}
h1 span{background:#aaa; color:#fff; margin-left:10px; padding:3px 10px; border-radius:6px; font-size:16px; position:relative; top:-3px;}
.friends{width:305px; margin:0 auto; padding:20px}
.friends img{margin-right:15px}
.ynf {font-size:25px; font-weight:lighter; float:left; margin-top:5px;}
.ynf span:first-child{color:#f7941e;}
.ynf span:last-child{color:#00aeef;}
.gets, .code, .refCode input{position:relative; top:-7px;}
.tenP{font-size:37px; line-height:34px;}
a.btn{margin:5px; padding:3px 10px 7px }
a.btn:hover, .icon-edit:hover{opacity:.8;}
.btn span{font-size:20px; position:relative; top:4px}
.btn.fb{background-color:#3B5B98}
.orr{padding:10px; position:relative; top:-26px; width:30px; margin:0 auto -30px; text-align:center; font-size:19px; background:#fafafa; color:#999;}
.code, .refCode input{font-size:20px; font-weight:100; text-align:center}
.icon-edit{font-size:35px; color:#708090; cursor:pointer;}
h4{color:#708090; text-align:left; width:200px; margin:0 auto;}
.icon-tick {color:#68bada; margin-right:5px; font-size:20px; position:relative; top:3px;}

@media only screen and (max-width: 600px) {
	.mainBox{width:100%;}
	.friends{padding:20px 10px}
	.friends img{margin-right:5px}
	h1 span{top:0;}
}
</style>
</head>

<body>
        
    
    <h1>My earnings <div class="btn btn-medium btn-blue mrT2 alC" ><?php echo $totalbalance." ".$currecncyName; ?></div> </h1> 
    <?php include_once('inc/backToMenu.php');
    $title = 'Get 10 % of first recharge on fisrt payment!';
    $summary = 'Sign up with this link on Utteru and get 10% extra recharge on first payment!';
    ?>
	<div class="mainBox">
    	<div class="clear friends ddLit">
        	<img src="<?php echo $CDNURL; ?>images/friends.png" class="fl"/>
            <div class="ynf">
            	<span> You </span> & <span> Your friend </span>
            </div>
            <div class="fl">
            	<span class="f15 gets"> gets </span>
                <b class="tenP mrR1"> 10% </b>
            </div>
            <span class="f12 fl"> extra on their <br>first recharge </span>
        </div>
        <?php 
	$token = dechex($_SESSION['userId']);
	$userId = $_SESSION['userId'];
	//get Referelcode
	$refCode = $funobj->getReferralCode($userId);
	if($refCode == 0)
	    $refCode = $userId;
	
	$longUrl = 'https://utteru.com/freeTalktime.php?token='.$token;
	$postData = array('longUrl' => $longUrl, 'key' => GOOGLE_PUBLIC_ACCESS_KEY);
	
	$shortUrlResponse = $funobj->getShortUrl($postData);
	
	if($shortUrlResponse != null)
	{
	    $shortUrl = $shortUrlResponse->id;
	}
	else
	{
	    $shortUrl = $longUrl;
	}
	
	//$link = 'https://twitter.com/home?status=Make%20international%20calls%20at%20low%20rates.%20Sign%20up%20on%20Utteru%20using%20this%20link:%20'.$shortUrl.'%20and%20get%2010%25%20extra%20on%20your%20first%20recharge.%20';//'utteru.com/signup.php?token='.dechex($_SESSION['userId']);
	$link = "https://twitter.com/home?status=Talk+to+your+friends+abroad+at+lowest+rates+with+www.utteru.com.Get+10%25+extra+talktime+on+1st+recharge+by+using+my+referral+ID+$refCode";
	
	
	
	$linkForGmail = urlencode('I thought you might be interested for international calling. So here\'s my suggestion to use Utteru for all your international calls.
It lets you make calls using options like access numbers, two-way calling and web calling. You can sign up using this link to get 10% extra on your 1st recharge.    '.$longUrl.'  Let me know if you like it. ');
	include_once 'gmailShareConf.php';
	
	$_SESSION['shareLink'] = $linkForGmail;
	
	$copyToClick = "Talk to your loved ones abroad at lowest rates!

Hey!

I\'ve been using UtterU for all my international calls, and it costs me really cheaper. I suggest you to try it once.

1. Sign up on UtterU (www.utteru.com)
2. Use my referral ID: $refCode
3. And get 10% extra talktime on your first recharge

Save more with Access numbers, Web calling & Mobile dialers

Get unlimited talktime validity
Amazing call quality
Connectivity without internet
";
	
	?>
        <div class="clear alC bdrT bdrB pd2 pdB4">
        	<h2 class="mrB1"> Share referral link</h2>
                <input type="hidden" id="urlData" value="<?php echo $copyToClick;?>"/>
		<a  href="javascript:;" onclick="fbShare('<?php echo $longUrl;?>');" class="btn btn-mini fb"><span class="icon-facebook"></span> facebook</a>
		<a target="_blank" href="<?php echo $link;?>" class="btn btn-mini btn-blue"><span class="icon-twitter"></span> twitter</a>
		<a href="<?php echo $loginUrl;?>" class="btn btn-mini btn-red"><span class="icon-envelope"></span> gmail</a>
		<a href="https://login.live.com/oauth20_authorize.srf?client_id=<?php echo OUTLOOK_SHARE_CLIENTID;?>&scope=wl.basic+wl.emails+wl.contacts_emails&response_type=code&redirect_uri=<?php echo OUTLOOK_SHARE_REDIRECT_URI;?>" style="background:#0072C6" class="btn btn-mini btn-red"><span class="icon-envelope"></span> Outlook</a>
        	<!--<a href="javascript:;" class="btn btn-mini btn-gold"><span class="icon-mob-app"></span> sms</a>-->
        	<a href="javascript:;" class="btn btn-mini btn-inverse" id="copyUrl"><span class="icon-trans-log"></span> copy to clipboard</a>
        </div>
        
        <div class="orr">Or</div>
        
        <div class="clear alC bdrB pd2 refCode pdB4">
        	<h2 class="mrB1">Unique referral code</h2>
            <div>
                <span class="code">alok</span>
                <input id="referalCode" placeholder="Enter your referral code." type="text" class="codeInpt dn" value="" onblur="saveReferalCode();" minlength="4" maxlength="20"/>
                <span class="icon-edit"></span>
            </div>
        </div>
        
        <div class="orr">Or</div>
        
        <div class="clear alC bdrB pd2">
        	<h3 class="mrB1 ddLit ligt">Your friends will also be able to enter any of your <span class="blue"> verified Emails or Numbers </span> while signing up</h3>
	<div id='emailNumber'>
	    </div>
        </div>
        
    </div>
</body>
</html>

<script type="text/javascript">
	$('.icon-edit').click(function(){
		$('.code').hide()
		$('.codeInpt').show()
		});
		
		
	function fbShare(shortUrl)
	{
	    var title = encodeURIComponent('Get 10% of first recharge on fisrt payment!');
	    var summary = encodeURIComponent('Sign up with this link on Utteru and get 10% extra recharge on first payment!');
	    var link ='http://www.facebook.com/sharer/sharer.php?s=100&u='+shortUrl;
	    console.log(link);
	    window.open(link,'sharer','toolbar=0,status=0,width=548,height=325');
	}
	
	
	function getReferalCode()
	{
	    
	    $.ajax({
		url:'/controller/paymentController.php',
		type:'POST',
		dataType:'json',
		data:{action:'getReferralCode'},
		success:function(data){
		    console.log(data);
		     $('.code').text(data.referralCode);
		     $('.codeInpt').val(data.referralCode);
		     
		     var str = '';
		      var regx = /^[0-9]{4,20}$/;
		     if(data.verifyData != undefined && data.verifyData != null)
		     $.each(data.verifyData,function(index,value){
			
	    
                         if(!regx.test(value) && value != '')
                         {
			    str+="<h4><span class='icon-tick'></span>"+value+"</h4>";
                         }else if(value != ''){
			     
				str+="<h4><span class='icon-tick'></span>+"+value+"</h4>";   
			    }
		     });
		     
		     if(str != '')
			$('#emailNumber').html(str);
		     
		}
		
	    });
	}
	
	getReferalCode();
	function saveReferalCode()
	{
	    
	     $('.code').show();
		    $('.codeInpt').hide();
	    if($('.code').text() == $('.codeInpt').val())
	    {
//		show_message('please change referal code to update!','error');
		return false;
	    }
            
	    
	    var referalCode = $('#referalCode').val();
	    
	   
	    var regx = /^[a-zA-Z][a-zA-Z0-9]{4,20}$/;
	    
	    if(!regx.test(referalCode))
	    {
		show_message('please enter a valid referal code,it should start with alphabat,minlength 4 and maxlength 20 !','error');
                $('.codeInpt').val($('.code').text());
		return false;
	    }
	    
	    $.ajax({
		url:'/controller/paymentController.php',
		type:'POST',
		dataType:'json',
		data:{action:'updateReferralCode',
		      referralCode:referalCode},
		success:function(data){
		    console.log(data);
		   
		    if(data.status =='success')
			$('.code').text(referalCode);
		    show_message(data.msg,data.status);
		    
		}
		
	    });
	}
        
        
//set path
ZeroClipboard.setMoviePath('ZeroClipboard.swf');
//create client
var clip = new ZeroClipboard.Client();
//event
clip.addEventListener('mousedown',function() {
	clip.setText(document.getElementById('urlData').value);
});
clip.addEventListener('complete',function(client,text) {
        show_message("Referral link successfully copied.","success");
	console.log('copied: ' + text);
});
//glue it to the button
clip.glue('copyUrl');

	
</script>