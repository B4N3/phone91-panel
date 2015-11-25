<!--Dial plan Setting-->
<!--For Add below code will visible-->
<label  class="searchAdd">
          <input type="text" class="fl" placeholder="Add Country" id="">             
          <input type="submit" name="" title="Add" class="btn btn-medium btn-primary clear" value="Add">
    </label>
<!--//For Add below code will visible-->
 
 <div class="tablflip-scroll dialplanTbl">
   <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize grayTabl bdrNon">
            <thead>
                <tr>
                    <th width="18%">User Prefix</th>
                    <th width="18%">Route</th>
                    <th width="18%">Prefix</th>
                    <th width="46%">&nbsp;</th>
                </tr>
            </thead>
            
          <tbody>
          	<tr class="addState">
                 <td colspan="100%">
                 	<span class="fl db mrR1 mrT1">United State Of America  </span>
                     <p class="arBorder fl cp sucsses" title="Add">
                        <span class="ic-12 add "></span>
                    </p>
                </td>
            </tr>
		   <?php for($i = 1; $i <= 6; $i++) 
            {
            echo'
                <tr class="even">
                   <td><input type="text" placeholder="001" class="isInput120"/></td>
                   <td>
				   		<select name="" class="isInput150">
							<option>100</option>
							<option>122</option>
						</select>
				   </td>
                   <td>
				   		<input type="text" placeholder="001" class="isInput120 fl"/>
				  		<div class="fr mrL2">
						<span class="ic-24 delete cp fl" title="Delete"></span>
						 <span class="ic-24 edit cp fl mrT mrR1 db" title="Edit"></span>
						 </div>
					</td>
                   <td>&nbsp;</td>
                </tr>';}
        ?>
        
        <tr class="addState">
                 <td colspan="100%">
                 	<span class="fl db mrR1 mrT1">India </span>
                     <p class="arBorder fl cp sucsses" title="Add">
                        <span class="ic-12 add "></span>
                    </p>
                </td>
            </tr>
		   <?php for($i = 1; $i <= 6; $i++) 
            {
            echo'
                <tr class="even">
                   <td><input type="text" placeholder="001" class="isInput120"/></td>
                   <td>
				   		<select name="" class="isInput150">
							<option>100</option>
							<option>122</option>
						</select>
				   </td>
                   <td>
				   		<input type="text" placeholder="001" class="isInput120 fl"/>
				  		<div class="fr mrL2">
						<span class="ic-24 delete cp fl" title="Delete"></span>
						 <span class="ic-24 edit cp fl mrT mrR1 db" title="Edit"></span>
						 </div>
					</td>
                   <td>&nbsp;</td>
                </tr>';}
        ?>
       </tbody>
    </table>
    
    <div id="demo2"></div>
</div>
<!--//Dial plan Setting-->

<script type="text/javascript">
$("#demo2").paginate({
				count 		: 50,
				start 		: 5,
				display     : 10,
				border					: false,
				text_color  			: '#304254',
				background_color    	: '#F5F6F7',	
				text_hover_color  		: '#fff',
				background_hover_color	: '#304254'
			});
</script>
 