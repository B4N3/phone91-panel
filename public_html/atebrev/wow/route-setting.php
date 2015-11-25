<!--Trabsactional-->
<div id="tabs" class="mngClntFnction">
    <ul>
            <li><a href="#tabs-1" title="Transactional"><span class="ic-40 tranLog"></span></a></li>
            <li><a href="#tabs-2" title="Edit Fund"><span class="ic-40 editfund"></span></a></li>
            <!--<li><a href="#tabs-3" title="Add SIP"><span class="ic-40 addsip"></span></a></li>-->
			<li><a href="#tabs-3" title="Settings"><span class="ic-40 setting"></span></a></li>
             <li><a href="#tabs-4" title="Latest info"><span class="ic-40 latestinfo"></span></a></li>
   </ul>
   
	<!--1st Tabs Content-->
    <div id="tabs-1">
            <div class="tablflip-scroll">
      		   <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize" id="clientTrstable">
                <thead>
                    <tr>
                        <th width="10%">Date</th>
                        <th width="15%">A/C Manager</th>
                        <th width="10%">Type</th>
                        <th width="5%" class="alR">Amount</th>
                        <th width="5%" class="alR">Balance</th>
                        <th width="30%">Description</th>
                        <th width="5%" class="alR">Debit</th>
                        <th width="5%" class="alR">Credit</th>
                        <th width="20%" class="alR">Closing Balance</th>
                    </tr>
                </thead>
          		<tbody>
                  <?php for($i = 1; $i <= 2; $i++) 
				  {
    				echo'
					  <tr class="even">
								<td>13 Feb 2011</td>
								<td>Shubhendra Agraw</td>
								<td>Voip</td>
								<td class="alR">10</td>
								<td class="alR">10.000000</td>
								<td>Sign Up Transaction</td>
								<td class="alR"><span class="debit">0</span></td>
								<td class="alR">0</td>
								<td class="alR closeBalance">0</td>
              		  </tr>
						<tr class="odd">
							 <td>13 Feb 2011</td>
							 <td>Shubhendra Agraw</td>
								<td>Voip</td>
								<td class="alR">10</td>
								<td class="alR">10.000000</td>
								<td>Sign Up Transaction</td>
								<td class="alR"><span class="debit">0</span></td>
								<td class="alR">0</td>
								<td class="alR closeBalance">0</td>
						</tr>';
						} ?>
                        <tr class="zerobal">
                		    <td colspan="100%"></td>
               			 </tr>
                           <?php for($i = 1; $i <= 3; $i++) 
						  {
							echo'
							  <tr class="even">
										<td>13 Feb 2011</td>
										<td>Shubhendra Agraw</td>
										<td>Voip</td>
										<td class="alR">10</td>
										<td class="alR">10.000000</td>
										<td>Sign Up Transaction</td>
										<td class="alR"><span class="debit">0</span></td>
										<td class="alR">0</td>
										<td class="alR closeBalance">0</td>
							  </tr>
								<tr class="odd">
									 <td>13 Feb 2011</td>
									 <td>Shubhendra Agraw</td>
										<td>Voip</td>
										<td class="alR">10</td>
										<td class="alR">10.000000</td>
										<td>Sign Up Transaction</td>
										<td class="alR"><span class="debit">0</span></td>
										<td class="alR">0</td>
										<td class="alR closeBalance">0</td>
								</tr>';
								} ?>
                           	 <tr class="zerobal">
                             	 <td colspan="100%"></td>
                             </tr>
								   <?php for($i = 1; $i <= 2; $i++) 
                                  {
                                    echo'
                                      <tr class="even">
                                                <td>13 Feb 2011</td>
                                                <td>Shubhendra Agraw</td>
                                                <td>Voip</td>
                                                <td class="alR">10</td>
                                                <td class="alR">10.000000</td>
                                                <td>Sign Up Transaction</td>
                                                <td class="alR"><span class="debit">0</span></td>
                                                <td class="alR">0</td>
                                                <td class="alR closeBalance">0</td>
                                      </tr>
                                        <tr class="odd">
                                             <td>13 Feb 2011</td>
                                             <td>Shubhendra Agraw</td>
                                                <td>Voip</td>
                                                <td class="alR">10</td>
                                                <td class="alR">10.000000</td>
                                                <td>Sign Up Transaction</td>
                                                <td class="alR"><span class="debit">0</span></td>
                                                <td class="alR">0</td>
                                                <td class="alR closeBalance">0</td>
                                        </tr>';
                                        } ?>
            </tbody>
        </table>
           </div>
        <div class="clear mrT1">
        	<div class="actionDiv">
            	<p class="mrB">Type</p>
                       	<input type="text" id="type" name="type" class="isInput150">
                       <div id="transotherType" class="dn fields">
                               <label>Enter Type</label>
                                <input type="text" id="transTypeOther" name="transTypeOther" />
                      </div>
                    <input type="hidden" id="toUser" value="32019" name="toUser">
            </div>
            
            <div class="actionDiv">
            	<p class="mrB">Description</p>
                <input type="text" id="description" name="description" class="isInput250"/>
            </div>

            <div class="actionDiv mDevice">
            	<p class="mrB">Add/Reduce</p>
                <div class="clear" id="sporow">
                	<span class="funder">
                        <label class="ic-60 enable cp" for="changefunder" onclick="toggleState($(this),'Trans');"></label>
                        <input type="checkbox" value="add" checked="checked" style="display:none" id="changefunderTrans">
                    </span>
                    
                    <input type="text" placeholder="Amount" id="transAmount" name="transAmount" class="mrL">
                    <select name="currency" class="currency">
                            <option value="147">USD</option>
                            <option value="63">INR</option>
                            <option value="48">GBP</option>
                            <option value="1">AED</option>
                    </select>
                </div>
            </div>
            
        </div>
    </div>
    <!--//1st Tabs Content-->
    
    <!--2nd Tabs Content-->
    <div id="tabs-2" class="pd15">
    	<form class="formElemt" id="editFundform">
            <div class="fields">
                            <p>Current  Balance</p>
                            <h3 class="userBalance">545.58 <span>USD</span></h3>
                        </div>
            <div class="fields">   
                                     <label>Add/Reduce Fund</label>
                                    <span>
                                            <label class="ic-60 enable cp" for="changefunder" onclick="toggleState($(this),'EditFund');"></label>
                                             <input type="checkbox" value="add" checked="checked" style="display:none" name="changefunderEditFund" id="changefunderEditFund"/>
                                    </span>
                                  <input type="text" name="fundAmount" id="fundAmount" placeholder="Amount"  class="mrL">
                         </div>
			<div class="fields">
                                        <label>Amount currency</label>
                                        <input type="text" class="small" />
                                        <select id="currency" name="currency" class="small">
                                                <option value="147">USD</option>
                                                <option value="63">INR</option>
                                                <option value="48">GBP</option>
                                                <option value="1">AED</option>
                                        </select>
                        </div>
       		<div class="paymMode">
                             <div class="fields minSpac">
                                    <label>Payment Type</p></label>
                                    <p id="paymentType" class="clear btnlbl">
                                           <input type="radio" id="advance" name="pType" value="prepaid" onchange="showNext('partialWrap',false);" checked="checked"  class="ui-helper-hidden-accessible"/>
                                           <label for="advance" title="Advance" class="first">Advance</label>
                                            <input type="radio" id="partial" name="pType" value ="partial" onchange="showNext('partialWrap',true);" class="ui-helper-hidden-accessible"/>
                                            <label for="partial" title="Partial">Partial</label>
                                            <input type="radio" id="credit" name="pType"  value ="postpaid" onchange="showNext('partialWrap',false);" class="ui-helper-hidden-accessible"/>
                                            <label for="credit" title="Credit">Credit</label>
                                    </p>
                            </div>
                            
                            <div class="dn clear" id="partialWrap">
                                   <div class="fields ifparticl">
                                            <p class="fl particleText">if Partial</p>
                                            <div class="fl">
                                                 <label>Partial Amount</label>
                                                  <input type="text" class="small" />
                                                <select id="currency" name="currency" class="small">
                                                        <option value="147">USD</option>
                                                        <option value="63">INR</option>
                                                        <option value="48">GBP</option>
                                                        <option value="1">AED</option>
                                                </select>
                                             </div>
                                    </div>
                                   <div class="fields">
                                           <label>Currency<p></p></label>
                                            <select name="currency">
                                                <option>USD</option>
                                                <option>INR</option>
                                                <option>GBP</option>
                                            </select>
                                    </div>
                            </div>
                            <div id="cashMemoBank">
                            <div class="fields ">
                                    <label>Type (Cash, Memo, Bank)</label>
                                    <select id="fundPaymentType" name="fundPaymentType">
                                            <option value="Cash">Cash</option>
                                            <option value="Memo">Memo</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Other">Other</option>
                                    </select>
                            </div>
                            
                            <div id="otherPaymentType" class="dn fields">
                                    <label>Enter Type</label>
                                   <input type="text" name="otherPaymentType">
                            </div>
                            </div>    
                            <div class="fields">
                                    <label>Description</label>
                                     <textarea name="fundDescription" id="fundDescription" class="rn desc"></textarea>
              		    	</div>  
                            <input type="submit" title="Done" value="Done" id="save" name="Done" class="mrT btn btn-medium btn-primary">
              	  </div>
        </form>
    </div>
    <!--//2nd Tabs Content-->
   
    <!--3rd Tabs Content--> 
    <div id="tabs-3"  class="pd15">
            <div class="leftSetform">
			<form class="formElemt settingform">
                <div class="add clear oh mrB1">				
                        <span class="ic-12 lgrayArr db fl"></span>
                        <label class="db fl fwd">Panel Setting</label>
              </div>
                <div class="fields ">
                    <label>Diverted Route</label>
                    <select>
                        <option>route1</option>
                        <option>route2</option>
                        <option>route3</option>
                        <option>route4</option>
                    </select>
                </div>
                <div class="fields ">
                    <label>Backup Route</label>
                    <select>
                        <option>route1</option>
                        <option>route2</option>
                        <option>route3</option>
                        <option>route4</option>
                    </select>
                </div>                
                <div class="fields ">
                    <label>Call Limit</label>
                   <input type="text"/>
                </div>
                <div class="fields ">
                    <label>Bandwidth Limit</label>
                   <input type="text"/>
              </div>            
                
				<div class="fields ">
                    <label>Prefix</label>
                   <input type="text"/>
              </div>
			  <div class="fields ">
                    <label>Route/Dialplan</label>
                   <input type="text"/>
              </div>               
                <div class="fields ">
                    <label>Tariff/Plan</label>
                   <select>
                        <option>tariff1</option>
                        <option>tariff2</option>
                        <option>tariff3</option>
                        <option>tariff4</option>
                        <option>tariff5</option>
                    </select>
              </div>
                <input type="submit" class="mrT btn btn-medium btn-primary" name="delete" id="delete" value="Delete" title="Delete">
                <input type="submit" class="mrT btn btn-medium btn-primary" name="Done" id="save" value="Done" title="Done">
				</form>
            </div>
            
            <div class="rightSetForm">
               <div class="add clear oh">
        	<span class="ic-12 lgrayArr db fl"></span>
            <input type="checkbox" class="fl db"/>
            <label class="db fl fwd">Add API'S</label>
          </div>
    	
         <?php for($i = 1; $i <= 10; $i++) 
		  {
			echo'
			<div class="clear">
					<div class="addsipInput">
							 <input type="text" placeholder="192.198.122.100.48" id="search">
							 <span title="Delete" class="ic-24 delete cp"></span> 
				   </div>
				    <p class="arBorder secondry fl cp sucsses cp" title="Add">
						<span class="ic-16 add "></span>
              	   </p>
			 </div>
			   ';
			}
	   	?>
        <input type="submit" class="mrT1 btn btn-medium btn-primary" name="Done" id="save" value="Done" title="Done">
    		</div>
    <!--//4rth Tabs Content--> 
    </div>
    <!--5th Tabs Content--> 
    <div id="tabs-4">
    	 <div class="rightSetForm formElemt">
                <div class="add clear oh mrB1">
                        <span class="ic-12 lgrayArr db fl"></span>
                        <label class="db fl fwd">General Setting</label>
              </div>
                <div class="fields ">
                    <label>Contact No.</label>
                   <input type="text" placeholder="+19 - 893073345" class="fl" />
				   <p title="Add me as Account Manager" class="arBorder secondry fl cp primary cp">
                        	<span class="ic-16 add "></span>
                        </p>
              </div>
              
                <div class="fields">
                    <label>Email</label>
                   <input type="text" placeholder="jsm@gmail.com" class="fl" />
				   <p title="Add me as Account Manager" class="arBorder secondry fl cp primary cp">
                        	<span class="ic-16 add "></span>
                        </p>
              </div>
			 
			  <div class="add clear oh mrB2">					
					<div class="fields ">
						<label>User Name</label>
						<input type="text"/>
					</div>
					<div class="fields ">
						<label>Password</label> 
						<input type="text"/>
					</div>
				</div>
				
              
               <div class="add clear oh mrB2">
			   			<div class="fields">
							<span class="ic-12 lgrayArr db fl"></span>
							<label class="db fl fwd mrB">Account Manager</label>
							<input type="text" placeholder="Shubhendra Agrawal" class="fl"/>
						</div>
						<div class="fields">
							<label>Account Manager Email</label>
							<input type="text" placeholder="jsm@gmail.com" class="fl"/>
						</div>
						<div class="fields">
							<label>Account Manager Skype ID / MSN ID</label>
							<input type="text" placeholder="Skype ID / MSN ID" class="fl"/>
						</div>
						<div class="fields">
							<label>Account Manager Contact</label>
							<input type="text" placeholder="Contact No" class="fl"/>
						</div>
              </div>
			  
			   <div class="add clear oh mrB2">
                        <div class="fields">
							<span class="ic-12 lgrayArr db fl"></span>													
							<label class="db fwd mrB">Support</label>
							
							<label>Support Email</label>
							<input type="text" placeholder="jsm@gmail.com" class="fl"/>
						</div>
						<div class="fields">
							<label>Support Skype ID / MSN ID</label>
							<input type="text" placeholder="Skype ID / MSN ID" class="fl"/>
						</div>
						<div class="fields">
							<label>Support Contact</label>
							<input type="text" placeholder="Contact No" class="fl"/>
						</div>
              </div>
			  	
				
               <input type="submit" class="mrT btn btn-medium btn-primary" name="Done" id="save" value="Done" title="Done">
            </div>
    </div>
    <!--//5th Tabs Content--> 

  </div>
 <!--//Trabsactional-->
<script type="text/javascript">

 $(function() {
	$( "#tabs" ).tabs({
		create: function(event, ui) {console.log($('.ui-tabs-selected a',this).text())},
		select: function(event, ui) {
		console.log(ui.tab.innerText)}
	});
});

function showNext(id,status){
    if(status)
	{
		$( "#"+id ).show()
	}
    else
	{
        $( "#"+id ).hide()
	}
}
$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});

function toggleState(ths,type)
{
    ths.toggleClass('disable');
    if($('#changefunder'+type).val() == "reduce")
    {
       $('#changefunder'+type).val("add");
    }
    else
    {
        $('#changefunder'+type).val("reduce");
    }
}
$(function() {
	$( "#paymentType, #BatchType" ).buttonset();
});
 </script>
 