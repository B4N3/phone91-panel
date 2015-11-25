 /*  * 
  *  @author :: Sameer Rathod
  *  @created ::
  *  @description ::
  */
 
 var term;
 var teriff;
 var globalTimeout = null;
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
      teriff = tariffId;
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
            
        teriff = 84;//$('#teriffPlan').val();
       console.log(teriff);
        term = $("#search").val();
        console.log(event);
        var currency = tariffId;
        console.log(window.location.host);
        $.ajax({
                        type: "GET",
                        dataType: 'jsonp',
                        url: "http://"+window.location.host+"/searchRate.php",
                        data: "action=loadDetails&term="+term+"&currency="+teriff,
                        success: function(data){
//                            console.log(data);
                               pricingData(data,term);
                        }                 
                });


    }
    })
    ._renderItem = function( ul, item ) {
    
    return $(  )
    .appendTo( ul );
    };		


    }



function pricingData(data,term){	
	var isPanel = window.location.hash.substr(2).split('|')[1];
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
                                                        <th><span class="text">Start Now</span></th>\
            </tr>\
            </thead>\
            <tbody>';

        $.each(data, function(key, item ){
			var start='<td><a class="tbbtn" href="http://phone91.com/signup.php">Start</a></td>';
			if(isPanel == 'panel-pricing.php')
				start = ''
            if(key != "currency"){
                html +='<tr>\
                <td class="first">'+key+'</td>\
                <td>'+item+' '+rate+'</td>\
				'+start+'\
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


/**

*@author Ankit Patidar <ankitpatidar@hostnsoft.com>

*@since 21/12/2013

*@param string item  

*/

function searchByName(item)

{

    $.ajax({

			type: "GET",

			url: "/searchRate.php",

                        dataType: 'json',

			data: "action=loadDetails&term="+item,

			success: function(data){

                            pricingData(data,item);

                   }                 

		});

}



$('.seachIcons').click(function(){

   

    showprice();

    

    

});

function showprice(){

 var term = $("#search").val();

$.ajax({

			type: "GET",

			url: "/searchRate.php",

                        dataType: 'json',

			data: "action=loadDetail&term="+term,

			success: function(data){

                           

                          var term2=data[0];

                           

                          $("#search").val(term2);

                        $.ajax({

                            type: "GET",

                            url: "/searchRate.php",

                            dataType: 'json',

                            data: "action=loadDetails&term="+term2,

                            success: function(data){

                            pricingData(data,term2);

                           

                         

                        }

});



                        }

})

}

$('#search').keyup(function(e){

    if(e.keyCode == 13)

    {

         showprice();

    }

});

$('#search').keyup(function(){



if(globalTimeout != null) clearTimeout(globalTimeout);

            globalTimeout=setTimeout(function(){



            // $('#search').val($('#ui-id-1 li a')text());    

                

               

            showprice();



            },2000)



    });
    
    $('#stcnt div aside div a').click(function(){

    

        $('#search').val($(this).attr('id'));

        searchByName($(this).attr('id'));

    

});

