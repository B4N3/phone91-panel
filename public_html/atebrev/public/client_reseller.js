//var activeCall = io.connect('http://'+window.location.hostname+':8082');
var activeCall = io.connect('http://'+window.location.hostname+":8085/activeCall");
//var counter = io.connect('http://'+window.location.hostname+":8083/counter");


function secondsToHms(d) {
d = Number(d);
var h = Math.floor(d / 3600);
var m = Math.floor(d % 3600 / 60);
var s = Math.floor(d % 3600 % 60);
return ((h > 0 ? h + ":" : "") + (m > 0 ? (h > 0 && m < 10 ? "0" : "") + m + ":" : "0:") + (s < 10 ? "0" : "") + s); 
}

function callBack(response)
{
    console.log(response);
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
//counter.on('item',function(){
//    console.log("check coutner");
//    counter.emit('here is the code');
//})

activeCall.on('userDetails',function(data){
    console.log(data.userName);
    console.log($('.user_name'));
    $('.user_name').html(data.userName);
    $('.name').html(data.name)
})
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

        
        
        str +='<li onclick="">\
                    <h3 class="clear">\
                    <span class="ellp">'+data[i].resName+'</span>\
                    <!--<i class="ic-24 edit"></i>-->\
                    </h3>\
                    <p class="tm">'+callduration+'</p>\
                    <h3 class="mrT1">'+data[i].dialed_number+'</h3>\
                    <p class="dt">'+new Date(data[i].call_start).toUTCString()+'</p>\
                    <div class="line"></div>\
                    <div class="clear">\
                        <a href="javascript:void(0)" onclick="hitApi('+data[i].id_active_call+')" class="btn btn-mini btn-danger alC stopACBtn">\
                                <span class="ic-16 stop fl"></span><span class="stopLbl">Stop</span>\
                        </a>\
                        <span class="callType">'+data[i].call_type+'</span>\
                    </div>\
                </li>';
}
   $('#callshopWrap ul').html(str);
});

activeCall.on('greeting', function(data) {
   document.write(data);
});
activeCall.on('dataget', function(data) {
   document.write(data);
});
console.log("activeCall run");

$(window).on('hashchange',function(){ 
    if(window.location.hash != "#!reseller-active-call.php" )
    {        
        activeCall.socket.disconnect();
    }
});

