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
express = require("express"),
url = require("url"),
mysql = require('mysql'),
app = express(),
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
    
//    fs.readFile(__dirname + "/public_html/index.php", function(err, data){
//        if(err){
//            res.writeHead(500);
//            return res.end("Error loading index.html");
//        }else{
//            res.writeHead(200);
//            res.end(data);
//        }
//    });
    
    console.log(req);
    //Using php session to retrieve important data from user
    var cookieManager = new co.cookie(req.headers.cookie); 
     memcache = new Memcached("localhost:11355");
//    client.connect(); 
//    console.log(memcache);
console.log(cookieManager.get("PHPSESSID"));
    memcache.get("sessions/"+cookieManager.get("PHPSESSID"), function(error, result){
        
console.log("err:"+result);
console.log("error : "+error);
        if(typeof(error)==="undefined"){
            var session = JSON.parse(result);            
        console.log("result::"+result);    
	if(session != null)
            {
                if(session.id != null || session.id != "")
		{
                    globalID  = session;
console.log("session_id:::"+session);
		}
            }
        }
        
    });
    
}



server = http.createServer();



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

/*EXPRESS  SETTING & CONFIGURATION*/

//var env = process.env.NODE_ENV ;
//app.configure(function(){
//if(env)
{
    /*EXPRESS  BODY PARSER TO READ THE HTML POST VARIABLES AND BODY*/
//    app.use(express.bodyParser());
//    app.use(express.methodOverride());
    /*EXPRESS  ROUTE JUST LIKE HTACCESS FOR DIFFERENT PAGES*/
//    app.routes();
    /*EXPRESS  DECLARE THE PATH OF THE PUBLIC DIRECTORY AND MAKE IT STATIC*/
    //app.use(express.static(path.join(application_root,"voip91Local")));
    //app.use("/www/voip91Local",express.static(path.join(application_root,"www/voip91Local")));
 /*   app.use(express.static(path.join(application_root,"public_html/public")))
//    app.use(express.static(path.join(application_root,"www/voip91Local")));
    //app.use("/www/voip91Local/public/");
    app.use("/js",express.static(path.join(application_root,"public_html/js")));
    app.use("/css",express.static(path.join(application_root,"public_html/css")));
    app.use("/images",express.static(path.join(application_root,"public_html/images")));
    /*EXPRESS  DECLARE THE ERROR HANDLER */
//    app.use(express.errorHandler({
//        dumpExceptions:true,
//        showStack:true
//    }));

};

//app.set('jsonp callback', true);
/* SET THE VIEW DIRECTROY */
//app.set('views', __dirname+'/public_html/');
//app.register('.html', require('jade'));
//app.engine('html', require('ejs').renderFile);
//app.engine('php', phpnode);
/* SET JADE AS VIEW ENGINE */
//app.set('view engine','html');
/* SET DEFAULT LAYOUT OPTION FALSE*/
//app.set('view options',{
//    layout: false
//});

/* @DESC : SESSIONHANDLER FUNCTION IS TO HANDLE THE SESSION VARIABLE AND SET 
 *         SESSION ID IN GLOBAL VARIABLE 
 *         IT USES COOKIES.JS TO READ THE PHPSESSID FROM COOKIE AND READ THE 
 *         CORRESPONDING SESSION DATA FROM MEMCACHE 
 * */



//app.get('/activeCall',function(req,res){ 
//    /* SESSIONHANDLER FUNCTION IS TO HANDLE THE SESSION VARIABLE AND SET IT IN GLOBAL VARIBALE 
//     * DEFINED BELOW*/
//    sessionHandler(req);
//    getSetCache();
//    console.log("session id :"+globalID.id);
//    res.sendfile(__dirname+'/public_html/reseller-active-calls.html');
//});
//
//app.get('/counter',function(req,res){ 
//    /* SESSIONHANDLER FUNCTION IS TO HANDLE THE SESSION VARIABLE AND SET IT IN GLOBAL VARIBALE 
//     * DEFINED BELOW*/
//    sessionHandler(req);
//    getSetCache();
//    console.log("session id :"+globalID.id);
//    res.sendfile(__dirname+'/public_html/reseller-active.html');
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
                    console.log("query Run");
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
            console.log(data);
            
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


server.listen(8083);
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



function getCounter(socket,pool)
{
    var chainIdEsc = sanitizer.sanitize(connectChainArrTwo[socket.id]); 
        console.log("chain Id::"+chainIdEsc);
        
        var sql = "select count(*) as count from 91_currentCalls where id_chain LIKE '"+chainIdEsc+"%'";
//     sqlResult2[socket.id] = ""; 
//console.log(socket.id);
//if(sqlResult2[socket.id] !== undefined){
//sqlResult2[socket.id] = "";
//console.log(pool);
  pool.getConnection(function(err, connection) { 
      console.log(err);
        connection.query(sql,function(error, rows,fields){
            //res.writeHead(200,{'Content-Type':'text/plain'});
            console.log("error SQL:"+error);
            console.log("rows"+JSON.stringify(rows));
//            if(typeof rows !== "undefined")
                sqlResult2[socket.id] = rows;
                console.log("internal"+sqlResult2[socket.id]);
                socket.emit('item',rows);
                connection.release();
//            console.log("row:"+rows);
        })
    });
//    return sqlResult2[socket.id];
console.log("socket"+sqlResult2[socket.id]);
//if(sqlResult[socket.id] !== undefined)
{
   
    
}
        
//        }
    
}






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
        
        if(connectArrTwo[socket.id] != "" && typeof connectArrTwo[socket.id] !== "undefined"){ 
//            getCounter(socket,pool);
            time2 = setInterval(function(data){	
                getCounter(socket,pool);
                
            },5000,true);
        }
//        socket.on('disconnect', function() {
//            console.log("disconnected");
//            clearInterval(time2);
//            delete connectArrTwo[socket.id];
//        });
    }

    
  });


