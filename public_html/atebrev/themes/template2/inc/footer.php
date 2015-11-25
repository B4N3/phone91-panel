<?php include_once(_MANAGE_PATH_."main.php"); ?>
<?php include_once(_MANAGE_PATH_."generalData.php"); ?>
<!---footer starts---->
	<div id="footer">
        <?php if(!(empty($contact_address) && empty($contact_phoneNo) && empty($contact_email))){ ?>    
    	<p class="fheading">Get in touch</p>
        <p class="fsub mrT2">mauris lacus, cons equat in did, semper sed felis. Integer mauris lacus, consequat in luctus id </p>
        <ul id="cntct">
            <?php if(!empty($contact_address)){ ?>
        	<li><span class="c1"></span><?php echo $contact_address; ?></li>
                <?php }if(!empty($contact_phoneNo)){ ?>
            <li><span class="c2"></span><?php echo $contact_phoneNo; ?></li>
            <?php }if(!empty($contact_email)){ ?>
            <li><span class="c3"></span><?php echo $contact_email; ?></li>
            <?php } ?>
        </ul>
        <div class="cl"></div>
        <?php } ?>
    </div>
<!---footer ends---->
<!---subfooter starts---->
	<div id="subfooter">
    	<div class="wrapper">
            <p class="fl">© <?php echo $companyName; ?> 2013 All right reserved.</p>
            <ul id="social">
                <?php if(!empty($socialLinks_facebook)){ ?>
                <a href="<?php echo $socialLinks_facebook; ?>"><li class="s1">Facebook</li></a>
                <?php }if(!empty($socialLinks_linkedin)){ ?>
                <a href="<?php echo $socialLinks_linkedin; ?>"><li class="s3">LinkedIn</li></a>
                <?php }if(!empty($socialLinks_twitter)){ ?>
                <a href="<?php echo $socialLinks_twitter; ?>"><li class="s2">Twitter</li></a>
                <?php } ?>
                <!--<a href="javascript:;"><li class="s4"></li></a>-->
            </ul>
            <div class="cl"></div>
        </div>
    </div>
<!---subfooter ends---->
<script type="text/javascript">
/*===================== This function is used for dropdown menus========*/
function uiDrop(ths,target, auto){	
	if( $(target).is(':visible')){
		$(ths).removeClass('active');
		$(target).slideUp('fast');
	}
	else
	{
		$(ths).addClass('active');
		$(target).slideDown('fast');
	}
	$(target).mouseup(function(e){
		e.preventDefault();
		return false;
	});
	var platform = navigator.platform; 

 	if( platform == 'iPad'){
		$(document).unbind('touchstart');
		
		document.addEventListener('touchend', function(e) {
			if(auto == 'true'){
				$(target).slideUp('fast');
				e.preventDefault();
				return false;
			}
		}, false);
	}
	var userAgent = navigator.userAgent.toLowerCase();
    var isIphone = (userAgent.indexOf('iphone') != -1) ? true : false;
 
    if (isIphone) {
		$('#body').unbind('mouseup');	
		$('#body').mouseup(function(e){
			if(auto == 'true')
				$(target).slideUp('fast');
		});
	}
	else{
		$(document).unbind('mouseup');	
		$(document).mouseup(function(e){
			if(auto == 'true')
				$(target).slideUp('fast');
				
		});
	}
};
</script>