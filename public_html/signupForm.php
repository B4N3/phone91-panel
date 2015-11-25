<?php
/** 
 *  @author :: Sameer Rathod
 *  @created ::
 *  @description ::
 */
?>

<div class="signupform form1">
	<div class="innerContainer mar0auto footerPages ">	
    <form class="mrB4" name="signup" id="signup" method="post" action="action_layer.php?action=signup">  
        <?php if(isset($_SESSION["signup_picture"])){?>
        <div class="mrT2 mrB2">
            <div class="fl pd userPhotos"><img src="<?php echo $_SESSION["signup_picture"];?>" title="image" id="loading_img" width="100" height="100"  /></div>
            <div class="fl pd lh25">
                <p>First Name:<b> <?php echo $_SESSION["signup_first_name"];?></b></p>         
                <p>Last Name:<b> <?php echo $_SESSION["signup_last_name"];?></b></p>
                <p>My Email: <b> <?php echo $_SESSION["signup_first_name"];?></b></p>
            </div>        
            <div class="cl"></div>
       </div>
        <?php }?> 
        
     <div class="signUpfields">  
		    <label class="fl db">Name:</label>
                    <input name="firstName" id="firstName" type="text" class="firstName fl" value=""/>
		       
             <span class="clr"></span>   
	</div>
        
     <div class="signUpfields">  
		    <label class="fl db">Choose Username:</label>
                    <input name="username" id="username" type="text" class="username fl" value="<?php echo $username;?>" onblur="check_user_exist(); return false;" onkeyup="check_user_exist()"/>
		    <div class="fl">
		    	<input name="check" id="check_btn" type="button" title="Check Availablity" class="small green awesome fltlt" onClick="check_user_exist(); return false;" value="Check Availablity"/>
                   </div>        
             <span class="clr"></span>   
	</div>
        
     <span class="clr"></span>
    
    <div class="signUpfields">   
    	<label class="mrT fl db">Choose your country:</label>
		<select tabindex="1"  name="location" id="location" class="uField valid">
<?php 
                foreach($country as $key =>$countryNames){                
                echo "<option value='$key'>$countryNames</option>";
                }?>    
</select>
	</div>
    <span class="clr"></span>
    
    
    <div class="signUpfields">   
   		 <label class="mrT fl db">Phone Number:</label>
	     <table cellpadding="0" cellspacing="0" border="0" class="fl">
	    	<tr>
                    <td><input name='code' value="code" type="text" id="code" style="width:100px" onkeyup="selectOption($(this).val())" readonly="" /></td>
	    		<td style="padding:0 0 0 5px;"><input type="text" name='mobileNumber' id='mobileNumber' onFocus="if (this.value == 'Phone number') { this.value = ''; }" value="Phone number" /></td>
	    	</tr>
	    	<tr>
	    		<td colspan="2"><div id="moberror"></div></td>
	     	</tr>
		</table>
         <span class="clr"></span>
    </div>
    
      
    <div class="signUpfields">         
    	<label class="mrT fl db">Email:</label>
        <input type="text" name='email' id='email' onFocus="if (this.value=='Email address') { this.value=''; }"  value="<?php echo $email;?>" class="fl" onkeyup="check_email_exist()" />
   		 <div id="emailerror"></div>
         <span class="clr"></span>
    </div>
     
     
     
    <div class="signUpfields">  
			<label class="mrT fl db">Choose Password:</label>
			<input type="password" name='password' id='password' value="<?php echo $password;?>" class="fl" />
            <span class="clr"></span>
    </div>
     
    
    
    <div class="signUpfields">  
			<label class="mrT db fl">Re-Enter Password:</label>
			<input type="password" name='repassword' id='repassword' value="<?php echo $password;?>" class="fl"/>
             <span class="clr"></span>		
     </div>
     
     
    <div class="signUpfields">   
		<label class="mrT db fl">Choose Currency:</label>
     	<select name="currency" id="currency">      
             <option value="1">AED</option>
            <option value="63">INR</option>
            <option value="147">USD</option>
            
            <?php //                        foreach ( $currencyArray as $key => $value) {

                        //echo '<option value="'.$value["currencyId"].'" >'.$value["currency"].'</option>';
                       // }
                       ?>
        </select>
        <div style="display: none"></div>
         <span class="clr"></span>
    </div>  
   
        
    <div class="signUpfields">   
    	<label class="mrT fl db">User:</label>
     	<select name="client_type" id="client_type" class="fl">
            <option value="3" selected>User</option>
            <option value="2">Reseller</option>
        </select>
         <span class="clr"></span>
    </div>
    
    
    
    <div class="f11 openRegu underLine mrT1 mrB leftSignUP">
    
		    <span>By signing up, you agree to the</span>
		    
		    <a href="/voipcall/terms.php" class="bluShade">Terms of Use</a>
		    
		    <span>and</span>
		    
		    <a href="http://phone91.com/voipcall/privacy.php" class="bluShade">Privacy Policy</a>
    </div>
    
    <input type="submit" class="large blue awesome crs whClr" value="I Agree &amp; Register" id="signupSubmit" onfocus="this.blur();"/>
    
    </form>
    </div>
</div>


