<!--Quick Serach-->
<div class="quicKseachsec  subPageSrch">
    <div class="quickSearch">
             <span title="Search" class="ic-16 search icon"></span> 
             <input type="text" placeholder="Search Route" id="search">
            <div class="replaceBttn fl">
                <p class="arBorder cmniner secondry fl cp primary" title="Add">
               		 <span class="ic-16 add "></span>
               </p>
           </div>
    </div>
    <label  class="searchAdd dn cmnClssBtn">
          <input type="text" class="fl" placeholder="" id="search">             
          <input type="submit" name="" title="Add" class="btn btn-medium btn-primary clear" value="Add">
    </label>
</div>
<!--//Quick Serach-->                    

<div class="inner">
<!--Left Section-->
<div class="slideLeft contentHolder" id="leftsec">
    <ul class="mngClntList">
          <li onclick="window.location.href='#!route.php|route-index.php?clientId=31995'" class="active">
				<div class="tariff">
							<h3 class="blackThmCrl">Route1</h3>
							<p>Lovey Gorakhpuriya</p>
							<p class="clear mrT1">
									<span class="fl">Bal:  <span class="font15 grnThmCrl">2300</span></span>
                                    <span class="fr"><span class="ic-12 arrowClient"></span>Excellent Route</span>
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
		if($('#chnage'+type).val() == "uncheck")
			{
					$('#chnage'+type).val("check");
			}
			else
				{
					$('#chnage'+type).val("uncheck");
				}
		}
		
</script>

<script>
	  jQuery(document).ready(function ($) {
		"use strict";
		$('#leftsec, .scrolll ').perfectScrollbar();
	  });
	  current();
	  toggleAddClose ();
</script>