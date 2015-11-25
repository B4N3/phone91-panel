<!--New Updates  Wrapper-->
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>


<div class="setContainer">
    <!--<h2 class="headSetting">News &amp; Updates</h2>-->
    <div class="whiteWrapp">
            <p class="mrB1 semi">Updates on</p>
            <div class="clear resboxrow">
                  <input name="facebook" id="fb_updates" type="checkbox" value="facebook" onclick="updateNews($(this))" title="Facebook"/>
                  <label for="fb" title="Facebook">Facebook</label>
            </div>
            <div class="clear resboxrow">
                  <input name="google" id="google_updates" type="checkbox" value="google" onclick="updateNews($(this))" title="Google +"/>
                  <label for="gplus" title="Google +">Google +</label>
            </div>
            <div class="clear resboxrow">
                  <input name="news" id="acc_news" type="checkbox" value="news" onclick="updateNews($(this))" title="News"/>
                  <label for="news" title="News">News</label>
            </div>
            <div class="clear resboxrow">
                  <input name="sms" id="acc_sms" type="checkbox" value="sms" onclick="updateNews($(this))" title="SMS" />
                  <label for="sms" title="SMS">SMS</label>
            </div>
            <p class="mrT1 mrB1 mrT3 semi">Monthly Newsletter</p>
            <div class="clear resboxrow">
                  <input name="email" id="acc_emails" type="checkbox"  value="email" onclick="updateNews($(this))" title="Email"/>
                  <label for="email" title="Email">Email</label>
            </div>
    </div>
  </div>

<!--//New Updates  Wrapper-->
<script type="text/javascript"> 
dynamicPageName('News and Updates') 
slideAndBack('.slideLeft','.slideRight');  		
                function updateNews(ths)
                {
                    var value = "";
                    if(ths.is(':checked'))
                        value = 1;
                    else
                        value = 0;
                  $.ajax({
                        url:"controller/settingController.php",
                        type:"POST",
                        dataType:"JSON",
                        data:{"call":"updateNewsSetting","type" : ths.val(),"value":value},
                        success:function(msg)
                        {
                            show_message(msg.msg,msg.status);
                        }
                    })  
                }
                $.ajax({
                    url:"controller/settingController.php",
                    type:"POST",
                    dataType:"JSON",
                    data:{"call":"getNewsPageDetails"},
                    success:function(msg)
                    {   
                        if(msg.facebook == 1 )
                            $("#fb_updates").prop("checked",true);                        
                        if(msg.google ==1)
                            $("#google_updates").prop("checked",true);
                        if(msg.news == 1)
                            $("#acc_news").prop("checked",true);
                        if(msg.sms == 1 )
                            $("#acc_sms").prop("checked",true);
                        if(msg.email == 1 )
                            $("#acc_emails").prop("checked",true);
                    }
                })
</script>
