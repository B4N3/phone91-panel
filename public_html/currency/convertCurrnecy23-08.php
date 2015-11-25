<?php
#- author- sudhir pandey (sudhir@hostnsoft.com)
#- date- 02/07/2013
#- design of currency convert Api.
?>
<style>
.hrTab{float:left; max-width:200px;}
.hrTab li{border-left:3px solid transparent; padding:3px 10px; margin-bottom:10px; cursor:pointer;}
.hrTab li:hover{border-left:3px solid #dd4b39; background-color:#f5f5f5;}
.hrTab li.active{border-left:3px solid #009900; color:#009900;}
#tabWrap{margin:0 0 150px 200px;}
/*styles by Aadheesh*/
#tabWrap strong{font-size:24px}
#topHead H3{width:auto;display:inline-block;}
.bgDrk{background-color:#003010;}
.bgBlu{background-color:#6199DF;}
.bdrBlu{background-color:#4A86C6;}
.bdrRwit{border-right:1px solid #fff}
.redClr{color:#dd4b39;}
.os{overflow:scroll;}
pre{color:#999;}
pre.green{color:#008800;}
.btnRed{background-color:#003010;cursor:pointer;} 
.btnRed:hover{background-color:#D1352A;}
.note2{background-color:#EFEFEF; border-left:5px solid #999999;}
.dn{display:none;}

</style>

<div id="keyGen">
    <div id="topHead" class="">
     <h3 class="pd1 pdR2 mrL1">Currency Convert Api</h3>
     <!--<h3 class="fr pd1 pdR2 mrL1 bdrR whClr">Back</h3></a>-->
     
    </div>   
    
    <div class="pdL2 pdR2 pdT1 pdB1">    	
    
<div >
      
    <div class="">
        
        <hr>
                <div>
            <h3>Input Parameter</h3>
             <hr>
            <table cellspacing="12" cellpadding="0">
                <tr>
                    <td>
                       From :  
                    </td>
                    <td>
                        <input type='text' name='from' id="from"/>
                    </td>
                </tr>
                <tr>
                    <td>
                       To :  
                    </td>
                    <td>
                        <input type='text' name='to' id="to"/>
                    </td>
                </tr>
                <tr>
                    <td>
                       Amount :  
                    </td>
                    <td>
                        <input type='text' name='amount' id="amount"/>
                    </td>
                </tr>
            </table>
             <div>
             <input type="button"  value="Genrate API" id="Genrateapi" name="" class="grnbtn">
             <input type="button"  value="Call API" id="Callapi" name="" class="grnbtn">
             </div>
        </div> 
        <div>
            <div class="bdr pd1 os bgGry mrT" id="creatapi" placeholder="API"><pre class="green" ></pre></div>
            <h3>Output : </h3>
            <div id="Response"><pre class="green" ></pre></div>
         </div> 
        <hr>
        <div class="fs3 mrT1 rglr mrB">Parameters</div>
        <b>Required : </b> From ,To , Amount .<br />
        <!--<b>Optional : </b> Date and Time .-->
        
        <div>
            <table class="cmntbl fs4 mrT2" width="100%" cellspacing="0" cellpadding="0" border="1">
                <tr>
                    <th>
                        Parameter Name
                    </th>
                    <th>
                        Value
                    </th>
                    <th>
                        Description
                    </th>
                    
                </tr>
                <tr>
                    <td>From</td>    
                    <td>string</td>
                    <td>Currency code who change amount from another currency (ex : USD,INR,EUR,AED). </td>
                </tr>    
                <tr>
                    <td>To</td>    
                    <td>sting</td>
                    <td>Currency code for resulted amount (ex : USD,INR,EUR,AED).</td>
                </tr> 
                <tr>
                    <td>Amount</td>    
                    <td>Integer</td>
                    <td>Amount who convert from one currency to another currency. </td>
                </tr> 
                
           </table>
            
        </div>  
        
        <div class="sample mrT3">
             <div class="fs3">Sample API :</div>
             <div class="bdr pd1 os bgGry mrT"><pre>http://run.taskb.in/phone91/currency/index.php?from=<-currency code->&to=<-another currency code->&amount=<-amount-></pre></div>
        </div>
        
        <br/>
        <div>
            <h3>Response :</h3>
            <hr>
            <b>Success : </b> amount of convert currency .<br />
            <!--<b>Error : </b>
            <table class="cmntbl fs4 mrT2" width="30%" cellspacing="0" cellpadding="0" border="0">
                <tr><th> Common : </th></tr>
                <tr><td> 101 Email, User id and Contact all are Blank.</td></tr>
                <tr><td> 102 Email id is not valid</td></tr>
                <tr><td> 103 user id is not valid</td></tr>
                <tr><td> 104 Contact Number is not valid.</td></tr>
                <tr><td> 105 User has no Permission for Attendance.</td></tr>
                <tr><td> 106 Unable To Connect Database.</td></tr>
                <tr><th> More : </th></tr>
                <tr><td> 107 Attendance Type is Blank.</td></tr>
                <tr><td> 108 Attendance Type is not Numeric.</td></tr>
                <tr><td> 109 Attendance Type is not valid.</td></tr>
                <tr><td> 110 Special character is not allow in Reason.</td></tr>
                <tr><td> 111 Leave Reason is Blank.</td></tr>
                <tr><td> 112 Half Day Reason is Blank.</td></tr>
                <tr><td> 113 Reason should not be greater than 500 character</td></tr>
                           
            </table>-->
            <br />
        </div>
   
        
    </div>
    
    

       
</div>
    </div>    
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    url="http://run.taskb.in/phone91/currency/index.php";
    
    $('#Genrateapi').click(function(){
        var from=$('#from').val();
        var to=$('#to').val();
        var amount=$('#amount').val();
        apiurl='?from='+from+'&to='+to+'&amount='+amount;
        $('#creatapi pre').text(url+apiurl);
        
    });
    $('#Callapi').click(function(){
        var from=$('#from').val();
        var to=$('#to').val();
        var amount=$('#amount').val();
        newurl = url;
        var data='from='+from+'&to='+to+'&amount='+amount;
        $.ajax({ type: "POST",url: newurl,data: data,
                success: function(text){
                  $('#Response pre').text(text);
                }
                });
       
    });
            
 })


</script>
