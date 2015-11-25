<!--Account Manager Edit Funds-->
<div class="tablflip-scroll">
  <table width="100%" cellspacing="0" cellpadding="0" border="0" id="clientTrstable" class="cmntbl  boxsize">
    <thead>
      <tr>
        <th width="15%">A/c Manager Name</th>
        <th width="10%" class="alC">Time</th>
        <th width="10%" class="alC">Fund</th>
        <th width="10%" class="alC">Credit Fund</th>
        <th width="10%" class="alC noBorder">Client Name</th>
        <th width="45% noBorder">&nbsp;</th>
      </tr>
    </thead>
    
    <tbody>
      <?php
      for ($i=0; $i<=5; $i++)
	  {
		    echo'
				<tr class="even">
					<td>Lovey Gorakhpuriya</td>
					<td class="alC">13 Feb 2011</td>
					<td class="alC">-</td>
					 <td class="alC blueThmCrl">+50 USD</td>
					<td class="alC noBorder blueThmCrl">Don\'t Know</td>
					<td class="noBorder">&nbsp;</td>
				 </tr>
				<tr class="odd">
					<td>Lovey Gorakhpuriya</td>
					<td class="alC">13 Feb 2011</td>
					<td class="alC redThClr">-121222</td>
					 <td class="alC blueThmCrl">+50 USD</td>
					<td class="alC noBorder blueThmCrl">Don\'t Know</td>
					<td class="noBorder">&nbsp;</td>
			  </tr>'; }
      ?>
      <tr class="zerobal">
        <td colspan="100%"></td>
      </tr>
      </tbody>
  </table>
</div>
<!--//Account Manager Edit Funds-->
