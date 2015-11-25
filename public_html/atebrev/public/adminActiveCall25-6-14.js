
var activeCall = io.connect('http://'+window.location.hostname+":8085/activeCall");

function secondsToHms(d) {
d = Number(d);
var h = Math.floor(d / 3600);
var m = Math.floor(d % 3600 / 60);
var s = Math.floor(d % 3600 % 60);
return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s); 
}


function hitApi(uniqueId)
{
    $.ajax({
        url:"http://"+window.location.hostname+"/cutCurrentCall.php",
        type:"post",
        dataType:"jsonp",
        jsonpCallback:"callBack",
        data:{"id_active_call":uniqueId},
        
        
    })
}

activeCall.on("connect",function(){
activeCall.on('message', function(data) {
    console.log("dfklasj;dfja");
 var i;
 var str = "";
 console.log(data);
for(i=0;i<data.length;i++)
{
    console.log(data[i]);
    var callduration = data[i].duration;
//    console.log("reseller"+data[i].resName);
//    console.log("call duration"+(callduration/60).toFixed(2).split("."));
    callduration = secondsToHms(callduration);

        str +='<div class="acBox">\
    	<div class="in">\
            <h3>'+data[i].resName+'</h3>\
            <p><span class="redThmClr">'+callduration+' </span> '+data[i].call_type+'</p>\
        </div>\
        <div class="in clear">\
        	<div class="fl">\
                <h3>'+data[i].dialed_number+'</h3>\
                <p>'+new Date(data[i].call_start).toUTCString()+'</p>\
            </div>\
            <button type="button" onclick="hitApi('+data[i].id_active_call+')" class="btn"><span></span>Stop</button>\
        </div>\
    </div>';
}
   $('#activeCallWrap').html(str);
})

});

$(window).on('hashchange',function(){ 
    if(window.location.hash != "#!active-calls.php" )
    {        
        activeCall.socket.disconnect();
    }
});
