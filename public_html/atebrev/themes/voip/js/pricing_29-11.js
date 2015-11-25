
function searchPrice(){
    var term = $("#search").val();
    if(term == '' || term == null){
	    $('#stcnt').show();						
	    $('#internationalCall').html('');	
    }
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
	select: function( event, ui ) {    
		$.ajax({
			type: "GET",
			url: "/searchRate.php",
                        dataType: 'json',
			data: "action=loadDetails&term="+ui.item.value,
			success: function(data){
                           
                          if(ui.item.value != "No result found."){ 
                           var html ='<div class="oh"><img src="/images/flags/in.png" width="48" height="48" class="fl db">\
                                <div class="fl fs2 imgspace mrL1">Rates for '+ui.item.value+'<span class="prices">'+data.currency+'/min</span></div></div>';
                             $.each( data, function(key, item ) {
                              if(key != "currency"){
                                html +='<div class="col4 fl"><aside class="pd1 mrR1 mrB1 bgWh taC">\
                                <div class="title"> '+key+'</div>\
                                <div class="numb">'+item+'</div>\
                                </aside>\
                                </div>';
                              }
                             })
                             $('#stcnt').hide();
                             $('#internationalCall').html(html);	
                          }
//				var flag = flag;
//
//				for(i in cobj)
//				{
//					if($.trim(cobj[i].country).toLowerCase() == $.trim(ui.item.value).toLowerCase())
//					{
//						flag = cobj[i].iso2.toLowerCase();
//					}							
//				}
//                               
//
//				var rates = $.parseJSON('{"rates":'+data+',"flag":"'+flag+'","country":"'+ui.item.value+'"}');
//				$('#stcnt').hide();
//				jd({tmpl:'#rate',obj:rates},function(html){							
//					$('#internationalCall').html(html);	
//				});
			}                 
		});
		

    }
    })
._renderItem = function( ul, item ) {
    return $( "<a>" + item.label + "<br>" + item.value + "</a>" )
    .appendTo( ul );
};
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



