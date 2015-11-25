<!-- shubhendra-->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=143595829002232";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- shubhendra-->
<div id="footer">
    <script>
    function feedback_submit()
    {
        <?php
        $randomCaptcha = rand('100', '990');
        $_SESSION['captcha'] = $randomCaptcha;
        ?>
       var mail = $('#user_email').val();
       var msg = $('#msg').val();
	   if(mail.length==0 || msg.length==0)
		{
			alert("Empty Fields");
				   return;
		}
       actionss='feedbak';
               $.ajax({
                   url:'../action_layer.php?action='+actionss+'&mailid='+mail+'&msg='+msg+'&magic=<?php echo $_SESSION['captcha'];?>',
               success: function(msg){
                   if(msg == 'Success')
                   show_message('Submitted Successfully', 'success');
               else
                   show_message('Error', 'error');
               }
           });
               }
    </script>
<div class="centerinner">
    	<div id="fleft">
        	<div id="feedback">
            <h4>FEEDBACK</h4>
            <form id="feedbak" action="" method="post">
            <label>Email ID</label>
            <input name="name_fbk" id="user_email" type="text" class="rounded5" />
            <label>Feedback</label>
            <textarea name="msg_fbk" id="msg" cols="" rows="5" class="rounded5"></textarea>
            <input name="" id="quickmsgsmt" type="button" name="submit_feedbak" class="small blue awesome" value="Submit" onclick="feedback_submit()"/>
            </form>
            </div>
        </div>
<div id="fright">
<div id="contact">
            <h4>Contact Us</h4>
           <li> <p><strong>GTalk ID</strong>: support@phone91.com</p> </li>
           <li> <p><strong>Mail</strong>: support@phone91.com</p>       </li>     
			<br />
            </div>
            <div id="followus">
            <h4>Follow Us</h4>            
			<ul>
            
          <!--   <li><a href="https://www.facebook.com/phone91"><span class="follow fb"></span></a></li>
            <li><a href="https://twitter.com/phone91"><span class="follow tw"></span></a></li>
            <!-- <li><a href="https://whozzat.com/c/phone91.com"><span class="follow wh"></span></a></li> -->
            <div class="clf"></div>
            
            <div class="fb-like" data-href="https://facebook.com/phone91" data-send="true" data-width="250" data-show-faces="true" data-font="verdana"></div>
            
            
            </ul>
            
            </div>        
</div>
<div id="fmiddle">
<h4>DOWNLOADS</h4>
<ul>
<li>
<h5><a href="http://www.google.com/talk/">Google <img src="/images/gtalk.png" /></a></h5>
</li>
</ul>
</div>
<div class="clf"></div>
</div>
	<div id="fbottom">
		<div class="centerinner">
			<?php include('links.php');?>
			<div class="clf"></div>
		</div>
	</div>
</div>
<ul class="ln social-icon-wrap scicons">
    <li class="sc twit">
    	<a target="_blank" href="https://twitter.com/phone91">Twitter</a>
    </li>
    <li class="sc fb">
    	<a target="_blank" href="http://www.facebook.com/phone91">Facebook</a>
    </li>
    <li class="sc in">
    	<a target="_blank" href="http://www.linkedin.com/company/phone91/">Linkedin</a>
    </li>
    <li class="sc yt">
    <a target="_blank" href="http://youtu.be/VG2OnN_mMVo">Youtube</a>
    </li>
    <li class="sc mail">
    	<a href="mailto:support@phone91.com">Mail</a>
    </li>
</ul>
<script type="text/javascript">
if($(window).width() <= 1053)$('.scicons').removeClass('social-icon-wrap').addClass('bottom-fixed');
	else $('.scicons').removeClass('bottom-fixed').addClass('social-icon-wrap');
$(window).resize(function(){
	if($(window).width() <= 1053)$('.scicons').removeClass('social-icon-wrap').addClass('bottom-fixed');
	else $('.scicons').removeClass('bottom-fixed').addClass('social-icon-wrap');
});
	page = 'home';		
</script>
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?110vjyu71p8RnSoWZGCU5NK7YkTlmQ6F';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<?php require("analyticstracking.php") ?>
