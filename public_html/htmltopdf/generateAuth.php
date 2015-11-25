<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Auth Generate</title>
  <style>
input {
  font-family: 'Lucida Grande', Tahoma, Verdana, sans-serif;
  font-size: 14px;
}

input[type=text], input[type=password] {
  margin: 5px;
  padding: 0 10px;
  width: 200px;
  height: 34px;
  color: #404040;
  background: white;
  border: 1px solid;
  border-color: #c4c4c4 #d1d1d1 #d4d4d4;
  border-radius: 2px;
  outline: 5px solid #eff4f7;
  -moz-outline-radius: 3px; // Can we get this on WebKit please?
  @include box-shadow(inset 0 1px 3px rgba(black, .12));

  &:focus {
    border-color: #7dc9e2;
    outline-color: #dceefc;
    outline-offset: 0; // WebKit sets this to -1 by default
  }
}


.lt-ie9 {
  input[type=text], input[type=password] { line-height: 34px; }
}
  </style>
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>


<body>
  <section class="container">
    <div class="login">
      <h1>Generate Authentication key : </h1>
      <p><input type="text" name="authkey" value="" id="authkey" placeholder="AuthKey"><input type="button" id="referce" onclick="makeid()" value="G"/></p>
         <input type="button" name="commit" onclick="generateAuth();" value="Generate">
    </div>


  </section>
    
    <div>
        <span>Select Template : </span>
        <div id="templateDiv">
            
            
        </div>
    </div>


    <script type="text/javascript" src="jquery-1.9.1.min.js"></script>
    <script>
        
    function generateAuth(){
        var authKey = $('#authkey').val();
         $.ajax({
                        type: "POST",
                        url: "tempController.php?action=generateAuthkey",
                        dataType: 'json',
                        data: {"authkey":authKey},
                        success: function(data) {
                            
                            if(data.status == "success"){
                                alert("Auth key successfully Updated.");
                            }
                            
                        }
        
    })
    }
    
    makeid();
    function makeid()
    {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < 15; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        $('#authkey').val(text);    
       
    }
    
    
    function getAllTemplate(){
        
         $.ajax({
                        type: "POST",
                        url: "tempController.php?action=getAllTemplate",
                        dataType: 'json',
                        success: function(data) {
                            
                           if(data.status == "success"){
                               var str = '';
                               $.each(data.templates,function (key,value){
                                   console.log(value.templateId);
                                   str +='<span><input type="radio" name="template" value="'+value.templateId+'">'+value.templateId+'<br></span>';
                                   
                               })
                               str +='<div><input type="button" id="next" name="next" value="Next>>" onclick="showHtmltoPdf();"/></div>';
                               $('#templateDiv').html(str);
                           }
                            
                        }
        
    })
    }
    
    
    getAllTemplate();
    
    
    function showHtmltoPdf(){
        var template = $("input[name=template]:checked").val();
        window.location.href="function.php?template="+template;
    
    }
    
    
    </script>

</body>
</html>
