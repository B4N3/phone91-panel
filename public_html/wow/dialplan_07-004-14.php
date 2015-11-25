<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
    <div class="quickSearch">
             <span class="ic-16 search icon" title="Search"></span> 
             <input type="text" id="search" placeholder="Dialplan/Prefix/Countries" />
            <div class="replaceBttn fl">
                <p title="Add" class="arBorder cmniner secondry fl cp primary">
               		 <span class="ic-16 add "></span>
               </p>
           </div>
    </div>
    <label class="searchAdd dn cmnClssBtn">
          <input type="text" id="search" placeholder="" class="fl" />             
          <input type="submit" value="Add" class="btn btn-medium btn-primary clear" title="Add" name="">
    </label>
</div>
<!--//Quick Serach-->

<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList dialplan">
				<li onclick="window.location.href='#!dialplan.php|dialplan-setting.php?clientId=31995'" class="active">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>Country Wise</p>
							<p class="clear mrT1">
								No. of Prefix: <span class="font15 blackThmCrl">251</span>
							</p>
					  </div>
        	  </li>
              
              <li onclick="window.location.href='#!dialplan.php|dialplan-setting.php?clientId=31995'">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>Shubhendra Agrawal</p>
							<p class="clear mrT1">
								Bal: <span class="font15 orngThmClr ">15502</span>
							</p>
					  </div>
        	  </li>
              
              
              <li onclick="window.location.href='#!dialplan.php|dialplan-setting.php?clientId=31995'">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>Shubhendra Agrawal</p>
							<p class="clear mrT1">
								Bal: <span class="font15 redThClr">15502</span>
							</p>
					  </div>
        	  </li>
              
              
              <li onclick="window.location.href='#!dialplan.php|dialplan-setting.php?clientId=31995'">
					<div class="tariff">
							<h3 class="blackThmCrl">Testplan</h3>
							<p>UnKnown</p>
							<p class="clear mrT1">
								Bal: <span class="font15 orngThmClr">23444</span>
							</p>
					  </div>
        	  </li>

      </ul>
</div>
<!--//Left Section-->    

<!--Right  Section-->   
<div class= "slideRight" id="rightsec">
</div>
<!--//Right Section-->


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
		if($('#changefunder'+type).val() == "uncheck")
			{
					$('#changefunder'+type).val("check");
			}
			else
				{
					$('#changefunder'+type).val("uncheck");
				}
		}
		
	jQuery(document).ready(function ($) {
		"use strict";
		$('#leftsec, .scrolll ').perfectScrollbar();
	});
	current();
	toggleAddClose ();
</script>