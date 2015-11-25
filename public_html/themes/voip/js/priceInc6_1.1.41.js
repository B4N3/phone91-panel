 /*  * 
  *  @author :: Sameer Rathod
  *  @created ::
  *  @description ::
  */
 
 var term;
 function renderBankDetails(bankObj)
 {
     if(bankObj != null)
     {
         var str = "";
         console.log(bankObj);
         $.each(bankObj,function(key,val){
             
             str += '<li>\
            	<div class="payment pdR3">\
                    <h4 class="fwN ligt">'+val.BankName+'</h4>\
                    <p>IFSC CODE : '+val.ifsc+'</p>\
                    <p>Account Number : '+val.accountNo+' </p>\
                    <p>Account Name: '+val.accountName+'</p>\
                    </div>\
            </li>';
         })
         $('#bankDetailsUl').html(str);
     }
     
 }
 
function autocompleteValue(request,response){
    
    $.ajax({
                type: "GET",
                dataType: 'jsonp',
                url: "https://voip91.com/searchRate.php",
                data: "term="+request.term,
                success: function(data){
                    response( $.map( data, function( key,item ) {
                        return {
                        label: key,
                        value: key
                               }
                        }));
                    }
                        
    })
    
    
 
} 
function searchPrice(tariffId){
   
//    var term = $("#search").val();
    if(term == '' || term == null){
        $('#stcnt').show();						
        $('#internationalCall').html('');	
    }
    
        $("#search").autocomplete({
        source: function(request,response){
            autocompleteValue(request,response);
        },
        width: 490,
        matchContains: true,
        max: 25, 
        delay:100, 
        scroll: true,
        scrollHeight: "auto", 
        minChars: 2, 
        multiple: false, 
        mustMatch: false, 
        autoFill: true, 
        selectFirst: true,
        select: function( event, ui ) {   
       
        term = $("#search").val();
        console.log(event);
        var currency = tariffId;
        console.log(currency);
        $.ajax({
                        type: "GET",
                        dataType: 'jsonp',
                        url: "http://"+window.location.host+"/searchRate.php",
                        data: "action=loadDetails&term="+term+"&currency="+tariffId,
                        success: function(data){
//                            console.log(data);
                               pricingData(data,term);
                        }                 
                });


    }
    })
    ._renderItem = function( ul, item ) {
    console.log(item);
    return $(  )
    .appendTo( ul );
    };		


    }



function pricingData(data,term){
    var count = 0;
    var firstvalue = '';
    var rate = data.currency +"/min";
    switch(data.currency)
        {
        case "USD":
            rate = "cent/min";
            break;
        case "INR":
            rate = "paise/min";
            break;
        case "AED":
            rate = "fils/min";
            break;

        }
var html ='<div class="wrapper">\
            <div class="clear">\
            <span class="flag-48 BR"></span>\
            <p class="restxt">Approx prices for <strong>'+term+'</strong> in <span>('+data.currency+')</span></p>\
            </div>\
            <h4>For mobile/landline/others <strong id="firstValue"></strong></h4>\
            <p>The tables below contain all voice pricing for the country. The price to make calls a call may vary based on the destination of the call</p>\
            <div id="table-wrapper">\
                                                <div id="table-scroll">\
                                                        <table id="prctbl" width="100%" border="0" cellpadding="0" cellspacing="0">\
            <thead>\
            <tr>\
                                                        <th><span class="text" style="text-align:left">City</span></th>\
                                                        <th><span class="text  themeBg">Prices</span></th>\
                                                        <!-- <th><span class="text">Start Now</span></th>-->\
            </tr>\
            </thead>\
            <tbody>';

        $.each(data, function(key, item ) {
            if(key != "currency"){
                html +='<tr>\
                <td class="first">'+key+'</td>\
                <td>'+item+' '+rate+'</td>\
                <!-- <td><a class="tbbtn" href="http://phone91.com/signup.php">Start</a></td> -->\
                </tr>';
                if(count == 0){
                    firstvalue = item + ' ' + rate;
                }
                count = (count + 1);  
            }
        })           
    html +='</tbody></table>\
                                                </div>\
                                        </div>\
    </div>';

    if(count >= 1)
    {

        $('.showhideDiv').hide();
        $('#result-container').html(html);
        $('#result-container').show();
        $('#firstValue').html(firstvalue);
    }else
    {
        $('#st-container').show();
        $('#result-container').html("No Result Found..");
        $('#result-container').show();
    }

}



