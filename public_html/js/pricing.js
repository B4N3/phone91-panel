
var globalTimeout =null;




function searchPrice(){
    
    var term = $("#search").val();
    if(term == '' || term == null){
	    $('#stcnt').show();						
	    $('#internationalCall').html('');	
    }

//code for search key up
  

	$("#search").autocomplete({
	source: "/searchRate.php",
	width: 490,
	matchContains: true,
	max: 25, 
	delay:100, 
	scroll: true,
	scrollHeight: "auto", 
	minChars: 2, 
	multiple: false, 
	mustMatch: false, 
	autoFill: false, 
	selectFirst: true,
	select: function( event, ui ){    
		$.ajax({
			type: "GET",
			url: "/searchRate.php",
                        dataType: 'json',
			data: "action=loadDetails&term="+ui.item.value,
			success: function(data){
                            pricingData(data,ui.item.value);
                   }                 
		});
		

    }
    })
._renderItem = function( ul, item ) {
    return $( "<a>" + item.label + "<br>" + item.value + "</a>" )
    .appendTo( ul );
};
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

 $('#search').keyup(function(){

if(globalTimeout != null) clearTimeout(globalTimeout);
            globalTimeout=setTimeout(function(){

            // $('#search').val($('#ui-id-1 li a')text());    
                
               
            showprice();

            },1500)

    });
    
   $('#search').keyup(function(e){
    if(e.keyCode == 13)
    {
         showprice();
    }
});

function showprice(){
 var term = $("#search").val();
$.ajax({
			type: "GET",
			url: "/searchRate.php",
                        dataType: 'json',
			data: "action=loadDetail&term="+term,
			success: function(data){
                            var regex=/^[0-9]+$/;
                            var countryName = data[0];
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
                            data: "action=loadDetails&term="+term2,
                            success: function(data){
                            term2=countryName;    
                            pricingData(data,term2);
                           
                         
                        }
});

                        }
})
}


function pricingData(data,term2){
     if(term2 != "No result found."){ 
        var html ='<div class="oh">\
        <div class="fl fs2 imgspace mrL1">Rates for '+term2+'<span class="prices">('+data.currency+')</span></div></div>';
        var count = 0;

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

            $.each(data, function(key, item ) {
            if(key != "currency"){
            html +='<div class="col4 fl"><aside class="pd1 mrR1 mrB1 bgWh taC">\
            <div class="title"> '+key+'</div>\
            <div class="numb">'+item+'</div>\
            <div class="numC">'+rate+'</div>\
            </aside>\
            </div>';
            count = (count + 1);      
            }
            })
            if(count >= 1){
            $('#stcnt').hide();
            $('#internationalCall').html(html);
            }else
        {
            $('#stcnt').hide();
            $('#internationalCall').html("No Result Found..");

        }
        }
}
function searchByCountry(val){
    $.ajax({
	    type: "GET",
	    url: "/searchRate.php",
	    data: "action=loadDetails&term="+val,
	    success: function(data){
		    var flag;

		    for(i in cobj)
		    {
			    if($.trim(cobj[i].country).toLowerCase() == val)
			    {
				    flag = cobj[i].iso2.toLowerCase();
			    }							
		    }

		    var rates = $.parseJSON('{"rates":'+data+',"flag":"'+flag+'","country":"'+val+'"}');
		    $('#stcnt').hide();
		    jd({tmpl:'#rate',obj:rates},function(html){							
			    $('#internationalCall').html(html);	
		    });
	    }                 
    });
}

$('#stcnt div aside div a').click(function(){
    
        $('#search').val($(this).attr('id'));
        searchByName($(this).attr('id'));
    
});



