/*INCLUDE ALL THE REQUIRED MODULES  */

var globalID = {};
var nameArr = [];
var sqlResult = {};
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


server = http.createServer(app);
var io = require('socket.io').listen(server);

/*INITILIZE A CONNECTION-ID ARRAY*/
var connectArr = {};
var connectChainArr = {};

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
app.configure(function(){
    /*EXPRESS  BODY PARSER TO READ THE HTML POST VARIABLES AND BODY*/
    app.use(express.bodyParser());
    app.use(express.methodOverride());
    /*EXPRESS  ROUTE JUST LIKE HTACCESS FOR DIFFERENT PAGES*/
    app.use(app.router);
    /*EXPRESS  DECLARE THE PATH OF THE PUBLIC DIRECTORY AND MAKE IT STATIC*/
    //app.use(express.static(path.join(application_root,"voip91Local")));
    //app.use("/www/voip91Local",express.static(path.join(application_root,"www/voip91Local")));
    app.use(express.static(path.join(application_root,"public_html/public")));
//    app.use(express.static(path.join(application_root,"www/voip91Local")));
    //app.use("/www/voip91Local/public/");
    app.use("/js",express.static(path.join(application_root,"public_html/js")));
    app.use("/css",express.static(path.join(application_root,"public_html/css")));
    app.use("/images",express.static(path.join(application_root,"public_html/images")));
    /*EXPRESS  DECLARE THE ERROR HANDLER */
    app.use(express.errorHandler({
        dumpExceptions:true,
        showStack:true
    }));

});

app.set('jsonp callback', true);
/* SET THE VIEW DIRECTROY */
app.set('views', __dirname+'/public_html/');
//app.register('.html', require('jade'));
app.engine('html', require('ejs').renderFile);
//app.engine('php', phpnode);
/* SET JADE AS VIEW ENGINE */
app.set('view engine','html');
/* SET DEFAULT LAYOUT OPTION FALSE*/
app.set('view options',{
    layout: false
});

/* @DESC : SESSIONHANDLER FUNCTION IS TO HANDLE THE SESSION VARIABLE AND SET 
 *         SESSION ID IN GLOBAL VARIABLE 
 *         IT USES COOKIES.JS TO READ THE PHPSESSID FROM COOKIE AND READ THE 
 *         CORRESPONDING SESSION DATA FROM MEMCACHE 
 * */
function sessionHandler(req){ 
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




app.get('/',function(req,res){ 
    /* SESSIONHANDLER FUNCTION IS TO HANDLE THE SESSION VARIABLE AND SET IT IN GLOBAL VARIBALE 
     * DEFINED BELOW*/
    sessionHandler(req);
    getSetCache();
    console.log("session id :"+globalID.id);
    res.render('reseller-active-calls.html');
});

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
//        var chainIdEsc = sanitizer.sanitize(globalID.chainId); 
        var chainIdEsc = sanitizer.sanitize(connectChainArr[socket.id]); 
        console.log("chain Id::"+JSON.stringify(chainIdEsc));
        
        var sql = "select id_active_call,id_chain,dialed_number,call_start,call_type,status,TIMESTAMPDIFF(SECOND,call_start,now()) as duration  from 91_currentCalls where id_chain LIKE '"+chainIdEsc+"%'";
        
        
        connection.query(sql,function(error, rows,fields){
            //res.writeHead(200,{'Content-Type':'text/plain'});
            console.log("error SQL:"+error);
//            console.log("row:"+rows);
//            console.log("fields:"+fields);
//            return false;
            if(typeof rows !== 'undefined')
            {
            sqlResult[socket.id] = rows;
            
            
            $resultant = [];
            chainIdStr = "";
            for(i=0;i<sqlResult[socket.id].length;i++){
                if(!in_array(sqlResult[socket.id][i].id_chain.substr( 0, 8), $resultant))
                {
                    chainIdStr += "'"+sqlResult[socket.id][i].id_chain.substr( 0, 8)+"',";
                    $resultant[i] = sqlResult[socket.id][i].id_chain.substr( 0, 8);
                        
                }   
            }
            chainIdStr += "''";
//            chainIdStr[socket.id] = chainIdStr;
//            console.log("check :"+chainIdStr );
//            return false ;
//            console.log("check :"+chainIdStr[socket.id] );
//            console.log("chanin Id:"+"select name,chainId from 91_manageClient where chainId IN ("+chainIdStr[socket.id]+")" );
//            return false;
//            pool.getConnection(function(err, connection) {
               
                var chainIdStrEsc = sanitizer.sanitize(chainIdStr[socket.id]);
                connection.query("select name,chainId from 91_manageClient where chainId IN ("+chainIdStr+")",function(error, result,fields){
                    console.log(error);
                    var j;
                    for(j=0;j<result.length;j++)
                    {
                        var chainIdTemp = result[j].chainId;
                        nameArr[chainIdTemp] = result[j].name;
                    }
                    connection.release();
                });
//            });
        }
        });
    });
     
    console.log("result Id::"+sqlResult[socket.id]);
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

/* @DESC : SOCKET IO ON CONNECT EVENT THIS IS THE MAIN SCOPE OF THE THE SOCKET IO WHICH 
 *         IS EXECUTED WHHEN EVER A CLIENT CONNECTION IS ESTABLISHED WITH SERVER
 */
 
 
io.sockets.on('connection',function(socket){
    console.log("session id 2 :"+JSON.stringify(globalID));
    if(globalID !== "undefined")
    {
        connectArr[socket.id] = globalID.id;
        connectChainArr[socket.id] = globalID.chainId;
        var userdata = {};
        userdata['userName'] = globalID.username;
        userdata['name'] = globalID.name;
        socket.emit('userDetails',userdata);
        
        if(connectArr[socket.id] != "" && typeof connectArr[socket.id] !== "undefined"){ 
            getDataFromDb(socket,pool);
            var time = setInterval(function(data){	
                getDataFromDb(socket,pool);
            },5000,true);
        }
        socket.on('disconnect', function() {
            console.log("disconnected");
            clearInterval(time);
            delete connectArr[socket.id];
        });
    }
});
server.listen(8082);