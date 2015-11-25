<?php	include('../config.php');
if(isset($_REQUEST['submit']))
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Long distance calling | Call Overseas | Long distance calls</title>
<meta name="keywords" content="Call overseas, calling overseas, long distance calling, long distance calls" />
<meta name="description" content="Long distance calling is very cheap, if you want to call overseas with VoIP, it cost very less. Better quality and best rates for calling overseas and long distance calls. " />

<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]--> 
<!--[if !IE]><!--><link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /><!--<![endif]--> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
$(".register").colorbox();//initilisation of colorbox
});
</script>
<script type="text/javascript" src="../js/jcom.js"></script>
</head>
<body>
<div id="main">
<div id="header">
<div class="centerinner">
<div id="logo"></div>
<?php include_once("../inc/login_header.php") ?>
</div>
<div class="clf"></div>
</div>
<div id="middle">
<div class="centerinner">
<div id="midleft">
<?php include('../links.php');?>
</div>

<div id="midright">
<h1>Supported Dialers</h1>
	<div class="mcol">   
<P>Phone 91 proffers endless options to its users to stay connected with their near ones. Nimbuzz is one of them. Being aware of the latest technology, we provide you multiple options to make VOIP calls. Phone 91 provides its own dialers as well as it supports all the open source dialers. 
</P>

<h2>Nimbuzz</h2>
<p>Nimbuzz lets you make voice calls and video calls from your phone. And what’s best is that calling from Nimbuzz is FREE. Nimbuzz supports 2G, 3G and GPRS internet to fulfill all the requirements to make international calls.</p>

<p>Latest version of Nimbuzz can be downloaded from the below link:  <a href="http://www.nimbuzz.com/en/get/voip-and-chat-on-mobile">Dwonload Link</a></p>

<h2>Nimbuzz settings for VOIP calling:</h2>

<p>Simply follow the below mentioned steps to keep in touch with your near ones who are at a place far away from you:</p>

<strong>Step 1:</strong>
<p>Install "Nimbuzz" in your phone and open the "Nimbuzz" application.</p>
<img src="img/nimbuzz1.jpg" alt="nimbuzz" width="300" />
<br />

<strong>Step2:</strong>
<p>On the rightmost side you will see the "More" option. Select it, and then touch the “Add an IM account” option.</p>
<img src="img/nimbuzz2.png" alt="nimbuzz" width="300" />
<br />

<strong>Step3:</strong>
<p>Now choose the type of IM account you want to create. For example, you may select the "Google talk" option.</p>
<img src="img/nimbuzz3.png" alt="nimbuzz" width="300" />
<br />

<strong>Step4:</strong>
<p>Sign in to Gtalk with your Username and Password.</p>
<img src="img/nimbuzz4.png" alt="nimbuzz" width="300" />
<br />

<strong>Step5:</strong>
<p> Once you have signed in, go to <strong>"More"</strong> option on the top.</p>
<img src="img/nimbuzz41.png" alt="nimbuzz" width="300" />
<br />

<strong>Step6:</strong>
<p>Now select the <strong>"Add Contact"</strong> option.</p>
<img src="img/nimbuzz5.png" alt="nimbuzz" width="300" />
<br />

<strong>Step7:</strong>
<p>Proceed further by selecting <strong>"Nimbuzz"</strong> and choosing <strong>"Google Talk"</strong></p>
<img src="img/nimbuzz6.png" alt="nimbuzz" width="300" />
<br />

<strong>Step8:</strong>
<p> Now add a new contact by typing phone@phone91.com in "Enter your friend’s username" option and then hit <strong>"Add to contacts"</strong> 
<img src="img/nimbuzz7.png" alt="nimbuzz" width="300" /> 
</p><br />

<strong>Step9:</strong>
<p>Hurray! Phone 91 will now appear in your chat list. Select <strong>"Phone"</strong> from the contact list.</p>
<img src="img/nimbuzz8.png" alt="nimbuzz" width="300" />
<br />

<strong>Step10:</strong>
<p>Now type <strong>"Register &lt;your username&gt; &lt;your password&gt;"</strong> in your chat with Phone 91. </p>
<img src="img/nimbuzz9.png" alt="nimbuzz" width="300" />
<br />

<strong>Step11:</strong>
<p>Have a second’s patience, and you would be successfully registered. Type the destination number you want to call along with the Country code.</p>
<img src="img/nimbuzz10.png" alt="nimbuzz" width="300" />
<br />

<strong>Step12:</strong>
<p>Now touch the topmost icon shown in the picture.</p>
<img src="img/nimbuzz11.png" alt="nimbuzz" width="300" />
<br />

<strong>Step13:</strong>
<p>And finally touch the “Call on Google Talk” option. Hence done! Start making calls to your family/friends right away. </p>
<img src="img/nimbuzz12.png" alt="nimbuzz" width="300" />
<br />

</div>
</div>

<div class="clf"></div>
</div>
</div>
<?php include_once('../inc/footer.php');?>
</div><!--end main-->
</div><script type="text/javascript" src="../js/jquery.form.js"></script> 
</body>
</html>