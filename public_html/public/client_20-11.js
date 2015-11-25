var socket = io.connect('http://voip91.com:8082');

function hitApi(uniqueId)
{
    $.ajax({
        url:"http://voip91.com/cutCurrentCall.php",
        type:"post",
        dataType:"jsonp",
        data:{"id_active_call":uniqueId}
        
    })
}
socket.on('userDetails',function(data){
    console.log(data.userName);
    console.log($('.user_name'));
    $('.user_name').html(data.userName);
    $('.name').html(data.name)
})
socket.on('message', function(data) {
 var i;
 var str = "";
// console.log(data);
for(i=0;i<data.length;i++)
{
    console.log(data[i].duration);
    var callduration = data[i].duration;
    callduration = (callduration > 59 ? ((callduration/60).toFixed(2).replace(".",":")) : ("00:"+callduration));

        
        
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
                        <a href="javascript:void(0)" class="btn btn-mini btn-danger alC">\
                            <div class="clear tryc" onclick="hitApi('+data[i].id_active_call+')">\
                                <span class="ic-16 stop"></span>\
                                <span>Stop</span>\
                            </div>\
                        </a>\
                        <span class="prov">'+data[i].call_type+'</span>\
                    </div>\
                </li>';
}
   $('#callshopWrap ul').html(str);
});

socket.on('greeting', function(data) {
console.log(data);
   document.write(data);
});
socket.on('dataget', function(data) {
console.log(data);
   document.write(data);
});
console.log("socket run");

