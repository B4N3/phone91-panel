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
    var teriff = $('#tariff').val();
    $.ajax({
                type: "GET",
                url: "/searchRate.php",
                dataType: 'json',
                data: "term="+request.term+"&currency="+teriff,
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
function searchPrice(){
     var teriff = $('#tariff').val();
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
            
       teriff = $('#tariff').val();
       console.log(teriff);
       term = $("#search").val();
        
        
       
        $.ajax({
                        type: "GET",
                        url: "/searchRate.php",
                        dataType: 'json',
                        data: "action=loadDetails&term="+term+"&currency="+teriff,
                        success: function(data){
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
        case "NZD":
            rate = "cent/min";
            break;
        }
var html ='<div class="wrapper">\
            <div class="clear">\
            <span class="flag-48 BR"></span>\
            <p class="restxt">Approx prices for <strong>'+term+'</strong> in <span>('+data.currency+')</span></p>\
            </div>\
            <h4 class="ligt">For mobile/landline/others <strong id="firstValue"></strong></h4>\
            <p>The tables below contain all voice pricing for the country. The price to make calls a call may vary based on the destination of the call</p>\
            <div id="table-wrapper">\
				<table class="priceTable" width="100%" border="0" cellpadding="0" cellspacing="0">\
				<thead>\
					<tr>\
						<th>City</span></th>\
						<th class="themeBg">Prices</th>\
						<th>Start Now</th>\
					</tr>\
				</thead>\
				</table>\
				 <div id="table-scroll">\
                        <table id="prctbl" width="100%" border="0" cellpadding="0" cellspacing="0">\
            <tbody>';

        $.each(data, function(key, item ){
			var start='<td><a class="tbbtn" href="/signUpWLabel.php">Start</a></td>';
			if(isPanel == 'panel-pricing.php')
				start = ''
            if(key != "currency"){
                
                if(item >= 100){
                    item = item/100;
                    var newrate = data.currency;
                }else
                    var newrate = rate;
                
                
                html +='<tr>\
                <td class="first">'+key+'</td>\
                <td>'+item.toFixed(2)+' '+newrate+'</td>\
				'+start+'\
                </tr>';
                if(count == 0){
                    firstvalue = item.toFixed(2) + ' ' + newrate;
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
 var teriff = $('#tariff').val();
$.ajax({

            type: "GET",
            url: "/searchRate.php",
            dataType: 'json',
            data: "action=loadDetail&term="+term+"&currency="+teriff,
            success: function(data){
                 var regex=/^[0-9]+$/;
                if (term.match(regex))
                {
                 var term2=term;   
                }else
                 var term2=data[0];
             if(term2 != 'No result found.' ) 
                $("#search").val(term2);
            $.ajax({
                type: "GET",
                url: "/searchRate.php",
                dataType: 'json',
                data: "action=loadDetails&term="+term2+"&currency="+teriff,
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

            showprice();
            },2000)
    });
    

$('#stcnt div aside div a').click(function(){
    $('#search').val($(this).attr('id'));
    searchByName($(this).attr('id'));

});

