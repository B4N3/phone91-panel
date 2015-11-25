var counter = io.connect('http://'+window.location.hostname+":8085/counter");

counter.on('item',function(data){
    console.log("check coutner");
    console.log(data[0]);
    $('#activeCallCounter').html(data[0].count);
})
