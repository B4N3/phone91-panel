<?php
//Include Common Configuration File First
include_once('config.php');
if (!$funobj->login_validate() || !$funobj->check_reseller()) {
    $funobj->redirect("index.php");
}
?>
<script src="http://cdnjs.cloudflare.com/ajax/libs/socket.io/0.9.16/socket.io.min.js"></script>
<!--Reseller Active Calls Wrapper-->
<div id="resellerWrap" class="Wrap">
	<!--Inner Wrapper-->
        <div class="clear inner" id="callshopWrap">
        <div id="resellerListWrap" class="">
            <ul class="cmnli-callshop clear ln" id="allCalls">
<!--            	<li class="group">
                    <p class="resName ellp">Lovey Gorakhpuriya (5)</p>
                    <p class="resNo">989352165</p>
                    <p class="resTime">5/8/2011 8:31:58 PM</p>
                    <p class="resTimeInfo">
                    		<span>43:33</span>min 
                            <span class="sep">| </span>
                            <span>0.025</span>USD/min
                    </p>
                    <p class="dataInfo">
                    	<i class="ic-16 profit"></i>
                    	<label><span class="profitLabel">Profit</span>  0.012 USD/min</label>
                    </p>
                    <div class="actWrap">
                    	<a class="btn btn-mini btn-danger clear alC" href="javascript:void(0)">
                            <div class="clear tryc" title="Stop">
                                <span class="ic-16 stop"></span>
                                <span>Stop</span>
                            </div>
                        </a>
                        <p class="provInfo">Skype</p>
                    </div>
                   <div class="callCount"></div>
                   <div class="callCountHover dn" title="5 calls">5 calls</div>
                </li>-->
            <?php // for($i = 1; $i <= 19; $i++)
//			echo'
//            	<li>
//                	<p class="resName ellp">Sudhir Pandey (2)</p>
//                    <p class="resNo">989352165</p>
//                    <p class="resTime">5/8/2011 8:31:58 PM</p>
//                    <p class="resTimeInfo">
//							<span>43:33</span>min 
//                            <span class="sep">| </span>
//                            <span>0.025</span>USD/min
//					</p>
//                    <p class="dataInfo">
//                    	<i class="ic-16 profit"></i>
//                    	<label><span class="profitLabel">Profit</span>  0.012 USD/min</label>
//                    </p>
//                    <div class="actWrap">
//                    	<a class="btn btn-mini btn-danger clear alC" href="javascript:void(0)">
//                            <div class="clear tryc" title="Stop">
//                                <span class="ic-16 stop"></span>
//                                <span>Stop</span>
//                            </div>
//                        </a>
//                        <p class="provInfo">Skype</p>
//                    </div>
//					<div class="callCount"></div>
//                   <div class="callCountHover dn" title="5 calls">5 calls</div>
//                </li>';
			?>
            </ul>
        </div>
        <!--Grouped Right Content-->
<!--        <div id="groupContent" class="slideRight">
        	Grouped Right Inner Content
                <div class="minGPcnt" id="callshopWrap">
           			 <a href="javascript:void(0);" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
                    <div class="box-dtl clear">
                            <h3 class="ellp font22">Ankita Chaddha</h3>
                            <p>5 <span class="f12">calls</span></p>
                    </div>
                    <ul class="ln resList">
							<?php // for($i = 1; $i <= 5; $i++)
//                            echo'
//								<li class="">
//										<p class="resNo grayColor">989352165</p>
//										<p class="resTime">5/8/2011 8:31:58 PM</p>
//										<p class="resTimeInfo">
//												43:33min  &nbsp; &nbsp;  0.025 USD/min
//										</p>
//										<p class="dataInfo">
//												<i class="ic-16 profit"></i>
//												<label><span class="profitLabel">Profit</span>  0.012 USD/min</label>
//										</p>
//										<div class="actWrap">
//												<a class="btn btn-mini btn-danger clear alC" href="javascript:void(0)">
//													<div class="clear tryc" title="Stop">
//														<span class="ic-16 stop"></span>
//														<span>Stop</span>
//													</div>
//												</a>
//												<p class="provInfo">Skype</p>
//										</div>
//								</li>';
                            ?>
                    </ul>        
        </div>-->
        	<!--//Grouped Right Inner Content-->
        </div>
        <!--//Grouped Right Content-->
        <div class="clear"></div>
    	<!--<div id="pagiwrap">
        		pagination come in this div
        </div>-->
    </div>
    <!--//Inner Wrapper-->
</div>
<!--//Reseller Active Calls Wrapper-->

<script type="text/javascript" src="public/client_reseller.js"></script>
<script type="text/javascript">
var _W, _H, _header, _lH, _lM, modH, _lW;
function Set(){
	_W = $(window).width(); //retrieve current window width
	_H = $(window).height(); //retrieve current window height
	_head = $('#header').outerHeight(true);//retrieve current header height
	_lH = _H - _head; //retrieve left height
	modH = _lH-100;
	_lW = _W - $('#resellerListWrap').outerWidth(true);
	$('#resellerWrap').css({height:_lH});
	$('#resellerListWrap, #resellerWrap #groupContent').css({height:modH});
	$('#resellerWrap #groupContent').css({width:_lW-70});
}
$(function() {
	Set();
});
$(window).resize(function() {
	Set();
});

//function getcall()
//{
//    $.ajax({
//        url:"controller/activeCallController.php",
//        type:"post",
//        dataType:"JSON",
//        data:{call:"getActiveCalls"},
//        success:function(response){
//            console.log(response);
//            var str = "";
//            $.each(response,function(key,value){
//            
//            str +='<li class="group">\
//                	<p class="resName ellp">'+value.name+'</p>\
//                    <p class="resNo">'+value.number+'</p>\
//                    <p class="resTime">'+value.starttime+'</p>\
//                    <p class="resTimeInfo">\
//                    		<span>'+value.starttime+'</span>min \
//                            <span class="sep">| </span>\
//                            <span>0.025</span>USD/min</p>\
//                    <!--<p class="dataInfo">\
//                    	<i class="ic-16 profit"></i>\
//                    	<label><span class="profitLabel">Profit</span>  0.012 USD/min</label>\
//                    </p>-->\
//                    <div class="actWrap">\
//                    	<a class="btn btn-mini btn-danger clear alC" href="javascript:void(0)">\
//                            <div class="clear tryc" title="Stop">\
//                                <span class="ic-16 stop"></span>\
//                                <span>Stop</span>\
//                            </div>\
//                        </a>\
//                        <p class="provInfo">Skype</p>\
//                    </div>\
//                   <div class="callCount"></div>\
//                   <div class="callCountHover dn" title="5 calls">5 calls</div>\
//                </li>'
//            })
//            
//            $('#allCalls').html(str);
//        }
//    })
//}
//$(document).ready(function(){
//
//    getcall();
//})
</script>

<script type="text/javascript">
$(document).ready(function()
{
			$('.slideLeft').click(function() {
				if ( $(window).width() <1024) {
					if ( $(window).width() < 400) {
						$('.slideRight').animate({"right": "15px"}, "slow");
						$('.slideLeft').fadeOut('fast');
					}
					else
					 {
							 if ( $(window).width() > 400) {
							$('.slideRight').animate({"right": "20px"}, "slow");
							$('.slideLeft').fadeOut('fast');
					}
					}
				}
			});

			$('.back').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "-757px"}, "slow");
						$('.slideLeft').fadeIn('fast');
				}
			});
	});
</script>