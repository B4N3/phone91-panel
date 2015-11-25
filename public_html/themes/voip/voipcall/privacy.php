<?php	include('../config.php');
if(isset($_REQUEST['submit']))
{
	$userid=$funobj->sql_safe_injection($_REQUEST['uname']);
	$pwd=$funobj->sql_safe_injection($_REQUEST['pwd']);
	$funobj->login_user($userid,$pwd);
	exit();	
}	
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" id="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,initial-scale=1.0" >

<title>About Phone91.com |International calling with Phone91</title>
<meta name="keywords" content="Phone91 is a leading International call provider. Phone91 provides various medium for making cheap international calls and long distance calls." />
<meta name="description" content="cheap international calls,  long distance calls." />
<!--[if IE]><link rel="stylesheet" type="text/css" href="css/phone91v2_ie.css" /><![endif]-->
<!--[if !IE]><!--><!-- COMMENT on 15 april <link rel="stylesheet" type="text/css" href="../css/phone91v2.css" /> --><!--<![endif]-->
<!-- <script type="text/javascript" src="js/html5.js"></script> -->
<!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery.colorbox.js"></script>
<script language="javascript" type="text/javascript">
	$(document).ready(function(){
	$(".register").colorbox();//initilisation of colorbox
	});
</script>
<script type="text/javascript" src="../js/jcom.js"></script>
<?php include_once('../inc/incHead.php'); ?>
</head>
<body>
<!-- Header -->
<?php include_once('../inc/incHeader.php'); ?>
<!-- //Header --> 

<!-- Features -->
<div class="mainFeaturesWrapper">
  <section id="featuresWrap" class="noBanner">
    <section class="innerBanner pr">
    	  <h1 class="mianHead">
                    <div>Privacy Policy</div>
                    <span>Know about Phone91's Privacy</span>
     	</h1>
    	<div class="met_short_split"><span></span></div>
      <div class="cl db pa backLinks">
        <?php include_once("../inc/login_header.php") ?>
      </div>
      <span class="clr"></span> </section>
  </section>
</div>
<!-- Features --> 

<!-- Container -->
<section id="container">
  <section class="innerContainer  footerPages">
  
    <h3>WALKOVER WEB SOLUTIONS LTD. offers its services via the website "www.Phone91.com".</h3>
     <h3>WALKOVER WEB SOLUTIONS LTD. is responsible for the processing of your Personal Data, as defined below.</h3>
     
     <br/> <br/>
     
        <div class="desCript">
       	      <h3 class="innerHeadIcn">Definitions</h3>    
	             <ul class="listIng">
			    	<li>For the purposes of this policy:</li>
				    <li><span>"Personal Data" means:</span>
				      	<ul class="normalList mrB0">
				   			 <li class="lh30">Name, address, telephone number</li>
				  			 <li  class="lh30">IP-address</li>
			                 <li  class="lh30">Payment Data</li>
			                 <li  class="lh30">Call Data Records</li>
			   		    </ul>
			        </li>
			        <li><strong>"Services"</strong> means all communication services provided by WALKOVER WEB SOLUTIONS LTD.</li>
				</ul>
       	 	</div>
     
     	 	<div class="desCript">
       	     	 <h3 class="innerHeadIcn">Collection of the Personal Data and use of collected Personal Data</h3>
                  	<ul class="listIng">
				          <li>WALKOVER WEB SOLUTIONS LTD. collects, uses and stores your Personal Data in accordance with the Luxembourg Law of 2 August
                          	 2002 on the Protection of Persons with regard to the Processing of Personal Data, as modified.</li>
				          <li>WALKOVER WEB SOLUTIONS LTD. uses your Personal Data for the provision of Services and billing purposes.</li>
				          <li>WALKOVER WEB SOLUTIONS LTD. may use your Personal Data to improve their Services.</li>
				          <li>WALKOVER WEB SOLUTIONS LTD. may use your Personal Data to defect misuse of its system and / or a customer account.</li>
				          <li>WALKOVER WEB SOLUTIONS LTD. may use the Personal Data to provide you with information relating to your account.</li>
				          <li>WALKOVER WEB SOLUTIONS LTD. may use the Personal Data for marketing purposes, unless you object to this. Thus, you may 
                         	at any time and without charge, contact WALKOVER WEB SOLUTIONS LTD. at the above-mentioned address to stop any use of your 
                            Personal Data for advertising or solicitation purposes.</li>
				          <li>WALKOVER WEB SOLUTIONS LTD. and any partner involved in providing the Services will store your Personal Data no 
                         longer than the time necessary to provide Services and in any case no longer than the maximum period permitted by the local laws,
                          rules and regulations on Personal Data protection.</li> 
   					</ul>
	             </div>
			</div>
                
		     <div class="desCript">  	  
                 <h3 class="innerHeadIcn">Disclosure and sharing of your Personal Data</h3>
                  	 <ul class="listIng">
					      	<li>WALKOVER WEB SOLUTIONS LTD. ensures the confidentiality of your Personal Data and will never disclose them 
                            	to third parties without your consent, apart from the partners involved in providing the Services.</li>
					         <li>However, these partners involved in providing the Services will only receive the Personal Data required to 
                             perform Services. WALKOVER WEB SOLUTIONS LTD. and its partners are prohibited from using your Personal Data for any other 
                             purposes.</li>
					         <li>Your Personal Data can be transmitted and stored in Luxembourg and in Switzerland, offering an adequate 
                             level of protection.</li>
					         <li>By using the Services provided by WALKOVER WEB SOLUTIONS LTD., you agree that your Personal Data can be 
                             transmitted to partners in Members States of European Union or in countries providing adequate protection for 
                             the provision of the Services.</li>
					         <li>Personal Data may additionally be communicated to any employee of WALKOVER WEB SOLUTIONS LTD. or any partner involved 
                             in providing the Services. The communication to these third parties is limited to data necessary for the performance 
                             of their tasks for the same purposes as the one of WALKOVER WEB SOLUTIONS LTD..</li>       
      				</ul>	
    	   </div>   

       	   <div class="desCript">  	  
				  <h3 class="innerHeadIcn">Security of your Personal Data</h3>
				  <ul class="listIng">
						<li>WALKOVER WEB SOLUTIONS LTD. uses standard security technologies and procedures to ensure the protection of 
                        your Personal Data against unauthorized access, use, disclosure or destruction.</li>
						<li>WALKOVER WEB SOLUTIONS LTD. takes security measures, such as technical and organizational measures 
                        against unauthorised or unlawful access to your Personal Data and against accidental loss or destruction of, 
                        or damage to your Personal Data.</li>
						<li>Any sensitive information, such as your credit card number are protected by encryption. The encrypted 
                        communication is established using Secure Sockets Layer (SSL) technology.</li>
						<li>Indeed, SSL provides the secure exchange of data between two computers in order to ensure the confidentiality, 
                        integrity of exchanged information and authentication by recognition of the identity of the program, the person 
                        or company with which the Personal Data is exchanged.</li>				
					</ul>
      		</div>
   
   			<div class="desCript">  	  
       	      <h3 class="innerHeadIcn">Access to your Personal Data</h3>
			  <ul class="listIng">
						<li>You can request free access to your Personal Data processed and stored by WALKOVER WEB SOLUTIONS LTD..</li>
						<li>Should you wish to access to, update, rectify your Personal Data or object at any time, for compelling and 
                        legitimate reasons relating to your special situation, the processing of any data on you, you may make a request in 
                        writing to the address indicated below:
						<br/>(Company No.:7348545)<br />
						145-157 St John Street<br />
						London - EC1V 4PY<br />
						England<br />						
						</li>
				</ul>
		     </div>
    
        <div class="desCript">  	  
       	      <h3 class="innerHeadIcn">Cookies</h3>
              		 <ul class="listIng">
	  					 <li>WALKOVER WEB SOLUTIONS LTD. draws your attention to the fact that during the time of the connection to 
	                     the <strong>"www.phone91.com"</strong> site, a cookie can be automatically installed.</li>
    				</ul>
        </div>
  
  </section>
</section>
<!-- //Container --> 

<!-- Footer -->
<?php //include_once('../inc/footer.php');?>
<?php include_once('../inc/incFooter.php');?>
<!-- //Footer --> 
<!--  Accordians -->