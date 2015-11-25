//var express = require('express');
//var app = express();
var fs =require('fs');

var PeerServer = require('peer').PeerServer;
var server = new PeerServer({host:'voice.phone91.com',port: 9007,path:'/'
,
ssl: {
    key: fs.readFileSync('/etc/nginx/ssl/voice.phone91.com/server.key'),
    certificate: fs.readFileSync('/etc/nginx/ssl/voice.phone91.com/server.crt')
    }

});
//console.log(server);
var connected = [];

server.on('connection',function(id){
   //console.log(id);
   connected.push(id);
console.log(connected);
   fs.writeFile("/home/voicepho/public_html/beta/chat/online.js",JSON.stringify(connected),'utf8');
});

server.on('disconnect',function(id){
  // console.log(id);
   	var i = connected.indexOf(id);
	if(i != -1) {
		connected.splice(i, 1);
	}
	fs.writeFile("/home/voicepho/public_html/beta/chat/online.js",JSON.stringify(connected),'utf8');
});

//app.get('/connected-people', function (req, res) {
//  return res.json(connected);
//});
