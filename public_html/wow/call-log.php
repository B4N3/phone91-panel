<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
    <div class="quickSearch">
             <span title="Search" class="ic-16 search icon"></span> 
             <input type="text" id="search" placeholder="Lovey Gorakhpuriya" class="calllog"/>
              <span>
                    <label class="ic-60 enableUser cp" for="enableUser" onclick="toggleState($(this),'EditFund');"></label>
                    <input type="checkbox" value="add" checked="checked" style="display:none" name="" id=""/>
            </span>
    </div>
    
    <label class="showLabel">
        <div class="fl ">
            <span class="fl f14">Showing</span>
             <p class="mrL1 mrR1 fl">
                 <span class="fl blackThmCrl">100</span>
             </p>
             <span class="fl">results by</span>
             <p class="mrL1	 mrR1 fl">
                 <span class="fl blackThmCrl">latest</span>
                 <span class="ic-12 dowpdn fl mrL"></span>
             </p>
              whose balance is less than
        </div>
        <p class="fl showInfo"> 
            <span class="fl">1000</span>
            <span class="ic-12 dowpdn fl mrL"></span>
        </p>
       <p title="Add" class="arBorder fl cp sucsses">
              <span class="ic-12 add "></span>
       </p>
  </label>
<!--//Quick Serach-->
</div>
<!--//Quick Serach-->                    

<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList">
          <li onclick="window.location.href='#!call-log.php|call-log-setting.php?clientId=31995'" class="active">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl">Manoj Jain </span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                  <div class="usrDescr">
                            <p class="uname">
                                    Manoj Jain
                            <h3 class="yelloThmCrl">manojjain223</h3>
                            <span>+19893073345</span>
                           <p class="acMan"> <span>A/c M:</span> Shubhendra Agrawal</p>
                           </p>
                             <span class="funder">
                                <label onclick="toggleState($(this),'Trans');" for="chnageCall" class="ic-32 grnEnabl cp"></label>
                                <input type="checkbox" id="" style="display:none" checked="checked" value="check" />
                            </span>
							<p class="textSip">SIP</p>
                   </div>
          </li>
          <li onclick="window.location.href='#!call-log.php|call-log-setting.php?clientId=31995'">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl">Manoj Jain </span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                   <div class="usrDescr">
                            <p class="uname">
                                    Manoj Jain
                            <h3 class="blueThmCrl">manojjain223</h3>
                            <span>+19893073345</span>
                           <p class="acMan"> <span>A/c M:</span> Shubhendra Agrawal</p>
                           </p>
                            <span class="funder">
                                <label onclick="toggleState($(this),'Trans');" for="chnageCall" class="ic-32 grnEnabl cp"></label>
                                <input type="checkbox" id="" style="display:none" checked="checked" value="check" />
                            </span>
                             <p class="textSip">SIP</p>
                   </div>
          </li>
          <li  onclick="window.location.href='#!call-log.php|call-log-setting.php?clientId=31995'">
                  <div class="linkCont">
                  	 <span class="ic-16 link"></span>	
                     <div class="showLinksCont dn">
                            <span class="blackThmCrl">Manoj Jain </span>
                           <span class="blueThmCrl"> manojjain223 < sagar457 < sudhir25 < phone91  manojjain223  sanksBG4 hardeep779 shubhendra585 < < <</span>
                       </div>
                   </div>
                   <div class="usrDescr">
                            <p class="uname">
                                    Manoj Jain
                            <h3 class="grnThmCrl">manojjain223</h3>
                            <!--IF Varified then grnThmCrl will come-->
                            <span class="grnThmCrl">+19893073345</span>
                           <p class="acMan"> <span>A/c M:</span> Shubhendra Agrawal</p>
                           </p>
                       	  <span class="funder">
                                <label onclick="toggleState($(this),'Trans');" for="chnageCall" class="ic-32 grnEnabl cp"></label>
                                <input type="checkbox" id="" style="display:none" checked="checked" value="check" />
                            </span>
                          <p class="textSip">SIP</p>
                   </div>
          </li>
      </ul>
    <!-- Define Nature of Client-->
      <div class="naturClint">
      	  <p class="grnThmCrl">
        		<span class="themeBgGrn"></span>
                Premium Clients
          </p>
          <p class="blueThmCrl">
        		<span class="themeBgBlue"></span>
                Normal Clients
          </p>
          <p class="yelloThmCrl">
          		<span class="themeBgYello"></span>
                Idle Clients
          </p>
      </div>
       <!-- //Define Nature of Client-->
</div>
<!--//Left Section-->    

<!--Right  Section-->   
<div class= "slideRight" id="rightsec">
</div>
<!--//Right Section-->

</span>

<script type="text/javascript">
$(document).ready(function()
{
			$('.slideLeft ul li, .reserrlerBtn').click(function() {
					if ( $(window).width() <1024) {
						$('.slideRight').animate({"right": "20px"}, "slow");
						$('.slideLeft').fadeOut('fast');
					}
			});
		});

	function toggleState(ths,type)
	{
		ths.toggleClass('redDisabl');
		if($('#chnageCall'+type).val() == "uncheck")
			{
					$('#chnageCall'+type).val("check");
			}
			else
				{
					$('#chnageCall'+type).val("uncheck");
				}
		}
		
</script>

<script>
	  jQuery(document).ready(function ($) {
		"use strict";
		$('#leftsec, .scrolll ').perfectScrollbar();
	  });
	  current ();
	 
	  function toggleState(ths,type)
		{
			ths.toggleClass('disableUser');
			if($('#enableUser'+type).val() == "reduce")
			{
			   $('#enableUser'+type).val("add");
			}
			else
			{
				$('#enableUser'+type).val("reduce");
			}
		}
</script>
