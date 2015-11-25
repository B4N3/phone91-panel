/*INCLUDE ALL THE REQUIRED MODULES  */

var time;
var time2;
var globalID = {};
var nameArr = [];
var sqlResult = {};
var sqlResult2 = {};
var currArr = {};
var chainIdStr = "";
var application_root = __dirname,
//express = require("express"),
url = require("url"),
mysql = require('mysql'),
//app = express(),
path = require("path"),
http = require("http"),
Memcached = require("memcached"),
sanitizer = require("sanitizer"),
co = require("./cookie.js");
fs = require('fs');
require('date-utils');

//phpnode = require('./index.js')({
//    bin:"C:\\wamp\\bin\\php\\php5.3.10\\php.exe"
//});


function sessionHandler(req,res){ 

//    fs.readFile(__dirname + "/public_html/reseller-active-call.php", function(err, data){
//        if(err){
//            res.writeHead(500);
//            return res.end("Error loading index.html");
//        }else{
//            res.writeHead(200);
//            res.end(data);
//        }
//    });
    
    //console.log(req);
    //Using php session to retrieve important data from user
    var cookieManager = new co.cookie(req.headers.cookie); 
     memcache = new Memcached("localhost:11355");
//    client.connect(); 
//    console.log(memcache);
//console.log(cookieManager.get("PHPSESSID"));
    memcache.get("sessions/"+cookieManager.get("PHPSESSID"), function(error, result){
        
//console.log("err:"+result);
//console.log("error : "+error);
        if(typeof(error)==="undefined"){
            var session = JSON.parse(result);            
     //   console.log("result::"+result);    
	if(session != null)
            {
                if(session.id != null || session.id != "")
		{
                    globalID  = session;
//console.log("session_id:::"+session);
		}
            }
        }
        
    });
    
}



server = http.createServer();

var vikas = require('fs');
vikas.writeFile("/home/voicepho/testvikas", "Hello World");

/*INITILIZE A CONNECTION-ID ARRAY*/
var connectArr = {};
var connectArrTwo = {};
var connectChainArr = {};
var connectChainArrTwo = {};

/*INITILIZE THE CONNECTION VARIABLE WITH DB CREDENTIAL*/
var pool = mysql.createPool({
    host:"localhost",
    user:"voip91_userNode",
    password:"-p]&T})z@T4BUA$0mp",
    database:"voip"
});


//var MySQLPool = require("mysql-pool").MySQLPool;
//var pool = new MySQLPool({
//  poolSize: 4,
//  user:"root",
//  password:"",
//  database:"voip91_switch"
//});


function getSetCache()
{
    var today = Date.today();
    var yesterday = Date.today();
    today = today.toYMD();
    yesterday = yesterday.toYMD();
    var checkExist = fs.exists(today+'.json',function(exist){
    if(!exist)
    {
         pool.getConnection(function(err, connection) {
                var sql = "select currencyId,currency from 91_currencyDesc";
                connection.query(sql,function(error, rows,fields){
                 //   console.log("query Run");
                   var currData = [];
                   for(i=0;i<rows.length;i++){
                       currData[rows[i].currencyId] = rows[i].currency;
                   }
                      fs.writeFile(today+".json",currData,'utf8');
                      currArr = currData;
                });
              
            });
       
    }
    else
    {
        fs.readFile(today+".json",'utf8',function(err,data){
            currArr = data;
           // console.log(data);
            
        });
    }
    });
    
    fs.exists(yesterday+'.json',function(exist){
        if(exist)
            fs.unlinkSync(yesterday+'.json');
    });
}


//server.on('request', function(req, res){
////  res.writeHead(200, {'content-type': 'text/html'});
//  console.log(req);
//  sessionHandler(req);
//  getSetCache();
////  res.end(sockFile);  
//});


server.listen(8085);
var io = require('socket.io').listen(server);


io.set('authorization', function(handshake, callback) {
  var cookies =  new co.cookie(handshake.headers.cookie);
  memcache = new Memcached("localhost:11355");
  memcache.get('sessions/' + cookies.get('PHPSESSID'), function(error, result) {
    if (error) {
      callback(error, false);
    } else if (result) {
      handshake.session = JSON.parse(result);
      callback(null, true);
    } else {
      callback('Could not find session ID ' + cookies.PHPSESSID + ' in  memcached', false);
    }
  });
});






function in_array (needle, haystack, argStrict) {
    /* 
    // +   original by: Sameer
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    */
    var key = '',
    strict = !! argStrict;

    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }

    return false;
}

/* @DESC : getDataFromDb FUNCTION IS FETCH THE DATA FROM MYSQL DB AND EMIT IT TO
 *         THE CLIENT ON MESSAGE EVENT
 *         IT USES MYSQL MODULE TO CONNECT AND QUERY THE DATABASE 
 * */
function getDataFromDb(socket,pool)
{
    $resultant = new Array();
    $chainIdArray = {};
    var i;
    var str="";
     
    pool.getConnection(function(err, connection) {
        fs.writeFile("/home/voicepho/nodeError","pool error ");
        console.log("pool error "+ err );
//        var chainIdEsc = sanitizer.sanitize(globalID.chainId); 
        var chainIdEsc = sanitizer.sanitize(connectChainArr[socket.id]); 
       // console.log("chain Id::"+JSON.stringify(chainIdEsc));
       
        var sql = "select id_active_call,id_chain,dialed_number,call_start,call_type,status,route,TIMESTAMPDIFF(SECOND,call_start,now()) as duration  from 91_currentCalls where id_chain LIKE '"+chainIdEsc+"%'";
        
        
        connection.query(sql,function(error, rows,fields){
            //res.writeHead(200,{'Content-Type':'text/plain'});
            fs.writeFile("/home/voicepho/nodeError","error SQL:"+error+"Q:"+sql);
           fs.writeFile("/home/voicepho/nodeError","row:"+rows);
            console.log("error SQL:"+error);
            console.log("row:"+rows);
//            console.log("fields:"+fields);
//            return false;
console.log("type "+ typeof rows);
            if(typeof rows !== undefined)
            {
            sqlResult[socket.id] = rows;
            
            
            $resultant = [];
            chainIdStr = "";
            console.log("scket length"+sqlResult[socket.id].length);
            if(sqlResult[socket.id].length < 1 || typeof sqlResult[socket.id] === undefined)
                return false;
            
            for(i=0;i<sqlResult[socket.id].length;i++){
                if(!in_array(sqlResult[socket.id][i].id_chain, $resultant))
                {
                    chainIdStr += "'"+sqlResult[socket.id][i].id_chain+"',";
                    $resultant[i] = sqlResult[socket.id][i].id_chain;
                        
                }   
            }
      // dont delte the delow section the problem of undefined is solved by this becos the chain id was truncated 
//            $resultant = [];
//            chainIdStr = "";
//            for(i=0;i<sqlResult[socket.id].length;i++){
//                if(!in_array(sqlResult[socket.id][i].id_chain.substr( 0, 8), $resultant))
//                {
//                    chainIdStr += "'"+sqlResult[socket.id][i].id_chain.substr( 0, 8)+"',";
//                    $resultant[i] = sqlResult[socket.id][i].id_chain.substr( 0, 8);
//                        
//                }   
//            }



            chainIdStr += "''";
//            chainIdStr[socket.id] = chainIdStr;
//            console.log("check :"+chainIdStr );
//            return false ;
//            console.log("check :"+chainIdStr[socket.id] );
//            console.log("chanin Id:"+"select name,chainId from 91_manageClient where chainId IN ("+chainIdStr[socket.id]+")" );
//            return false;
//            pool.getConnection(function(err, connection) {
               console.log("chain"+chainIdStr);
                fs.writeFile("/home/voicepho/nodeError","chain"+chainIdStr);
               if(chainIdStr != ""){
                var chainIdStrEsc = sanitizer.sanitize(chainIdStr[socket.id]);
                connection.query("select name,chainId,userName from 91_manageClient where chainId IN ("+chainIdStr+")",function(error, result,fields){
                 //   console.log(error);
                 fs.writeFile("/home/voicepho/nodeError","91_manageClient"+error);
                    var j;
                    nameArr = [];
                    for(j=0;j<result.length;j++)
                    {
                        var chainIdTemp = result[j].chainId;
                        nameArr[chainIdTemp] = result[j].userName;
                      //  console.log(nameArr);
                    }
                    connection.release();
                });
                
            }
//            });
        }
        });
    });
     
    //console.log("result Id::"+sqlResult[socket.id]);
//    return false;

if(sqlResult[socket.id] !== undefined)
{
    for(i=0;i<sqlResult[socket.id].length;i++)
    {  
        sqlResult[socket.id][i].resName = nameArr[sqlResult[socket.id][i].id_chain];
        
    }
//    console.log("result Id::"+JSON.stringify(resName));
//    return false;
    socket.emit('message',sqlResult[socket.id]);
}
}

function getCounter(socket,pool)
{
    var chainIdEsc = sanitizer.sanitize(connectChainArrTwo[socket.id]); 
        console.log("chain Id::"+chainIdEsc);
        fs.writeFile("/home/voicepho/nodeError","chain Id::"+chainIdEsc);
        var sql = "select count(*) as count from 91_currentCalls where id_chain LIKE '"+chainIdEsc+"%'";

  pool.getConnection(function(err, connection) { 
      //console.log(err);
      fs.writeFile("/home/voicepho/nodeError","getcounter:"+err);
        connection.query(sql,function(error, rows,fields){
            fs.writeFile("/home/voicepho/nodeError","error"+sql);
            //res.writeHead(200,{'Content-Type':'text/plain'});
           // console.log("error SQL:"+error);
           // console.log("rows"+JSON.stringify(rows));
//            if(typeof rows !== "undefined")
                sqlResult2[socket.id] = rows;
               // console.log("internal"+sqlResult2[socket.id]);
               
                connection.release();
//            console.log("row:"+rows);
        })
    });
//    return sqlResult2[socket.id];
//console.log("socket"+sqlResult2[socket.id]);
 socket.emit('item',sqlResult2[socket.id]);


    
}



/* @DESC : SOCKET IO ON CONNECT EVENT THIS IS THE MAIN SCOPE OF THE THE SOCKET IO WHICH 
 *         IS EXECUTED WHHEN EVER A CLIENT CONNECTION IS ESTABLISHED WITH SERVER
 */
 
 
 io.of('/activeCall').on('connection',function(socket){
//     console.log(socket.headers)
//     sessionHandler(socket);
     globalID = socket.handshake.session;
  //  console.log("session id 2 :"+JSON.stringify(globalID));
    console.log("session id 2 :"+JSON.stringify(globalID.id));
    if(globalID !== "undefined")
    {
        console.log("connectArr "+JSON.stringify(connectArr));
        connectArr[socket.id] = globalID.id;
        connectChainArr[socket.id] = globalID.chainId;
        var userdata = {};
        userdata['userName'] = globalID.username;
        userdata['name'] = globalID.name;
        socket.emit('userDetails',userdata);
        
        if(connectArr[socket.id] != "" && typeof connectArr[socket.id] !== undefined){ 
            getDataFromDb(socket,pool);
            time = setInterval(function(data){	
                getDataFromDb(socket,pool);
            },5000,true);
        }
        socket.on('disconnect', function() {
            console.log("disconnected");
            clearInterval(time);
            delete connectArr[socket.id];
        });
    }
    else
        console.log("gobal id undefined");
});

/* @DESC : SOCKET IO ON CONNECT EVENT THIS IS THE MAIN SCOPE OF THE THE SOCKET IO WHICH 
 *         IS EXECUTED WHHEN EVER A CLIENT CONNECTION IS ESTABLISHED WITH SERVER
 */
 
 
io.of('/counter')
  .on('connection', function (socket) {
  globalID = socket.handshake.session;
    console.log("session id 3 :"+JSON.stringify(globalID));
    console.log(socket.id);
    
    if(globalID !== "undefined")
    {
        connectArrTwo[socket.id] = globalID.id;
        connectChainArrTwo[socket.id] = globalID.chainId;
        
        if(connectArrTwo[socket.id] != "" && typeof connectArrTwo[socket.id] !== undefined){ 
            socket.on('connect', function() {
                getCounter(socket,pool);
            });
            
            
            time2 = setInterval(function(data){	
                getCounter(socket,pool);
                
            },5000,true);
        }
        socket.on('disconnect', function() {
            console.log("disconnected");
            clearInterval(time2);
            delete connectArrTwo[socket.id];
        });
    }

    
  });



