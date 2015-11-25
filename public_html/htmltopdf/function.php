<?php
include_once 'session.php';

if(!isset($_SESSION['userId'])){
    header("location:index.php");
}

?>

<html>
    <body>
        <div>
        <form id="data" enctype="multipart/form-data">
            Select template to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="submit">
        </form>
        </div>    
        
        <div>
            <h2>Template :</h2> 

            <textarea id='firstTemp' style="width: 850px; height: 650px;"></textarea>

        </div>
        <div id="allParameterDtl">
            

        </div>
        <div id="authKeyDiv">
           
        </div>
        
        <div>
            <input type='button' id='showParameter' onclick="showParameter();" value='showParameter'/>
        </div>


        <div id="nextProcessDiv" style="display: none">
            <input type='button' id='GenerateURL' onclick="generateURL();" value='GenerateURL'/>
            <input type='button' id='savePDF' onclick="saveTemplateInPdf(0);" value='savePDF'/>
            <input type='button' id='ExportPDF' onclick="saveTemplateInPdf(1);" value='ExportPDF'/>
          
        </div>

        <div>
            <h2>API :</h2>
            <div><span id="api"></span></div>
        </div>

        <div>
            <h2>Download PDF API :</h2>
            <div><span id="downloadApi"></span></div>
        </div>

        <script type="text/javascript" src="jquery-1.9.1.min.js"></script>
        <script>
         var template = <?php echo $_REQUEST['template']; ?>;
         gethtml(template);
            
                function generateURL() {
                    var str = window.location.hostname + "/htmltopdf/createPDF.php?";
                    $('#allParameterDtl input').each(function() {
                        
                        str += $(this).attr('name')+"="+$(this).val()+"&amp;";
                    })
                    
                    str += 'authToken='+$('#authkeyId').val();
                    $('#api').html(str);
                    
                    
                    
                    var downloadstr = window.location.hostname + "/htmltopdf/downloadpdf.php?pdfKey=*****************";
                    $('#downloadApi').html(downloadstr);
                    

                }


                function saveTemplateInPdf(type) {
                    var param = {};
                    $('#allParameterDtl input').each(function() {
                        param[$(this).attr('name')] = $(this).val();
                    })
                    
                    param['type'] = type;
                    
                    $.ajax({
                        type: "POST",
                        url: "tempController.php?action=saveTemplateInPdf",
                        dataType: 'json',
                        data: param,
                        success: function(data) {
                            if (type == 1)
                                window.location.href = "/htmltopdf/pdfFolder/" + data.fileName;
                            console.log(data.fileName);
                        }

                    });
                }

                

                function gethtml(template) {
                    if(template == '' || template== 'undefined'){
                        return 0;
                    }
                    

                    $.ajax({
                        type: "POST",
                        url: "tempController.php?action=gethtmlFromTemplate",
                        dataType: 'json',
                        data: {"template": template},
                        success: function(data) {
                            
                            if(data.status == "success"){
                               $('#firstTemp').val(data.template);
                            }

                        }

                    });

                }


                function showParameter() {
                    var html = $('#firstTemp').val();

                    $.ajax({
                        type: "POST",
                        url: "tempController.php?action=saveTemplate",
                        dataType: 'json',
                        data: {"html": html},
                        success: function(data) {
                            console.log(data.templateId);
                            console.log(html);
                            var res = html.match(/\<\%[a-zA-Z0-9]*\%\>/g);
                            console.log(res);
                            var str = '';
                            if(res != null){
                            $.each(res, function(index, value) {
                                value = value.replace(/[\<\%\>]*/gi, "");
                                str += value + ' : <input type="text" id="' + value + '" name="' + value + '"/></br>';
                            })
                            }
//                            console.log(data.templateId);
                            str += '<input type="hidden" id="htmlTempId" name="htmlTempId" value="'+data.templateId+'">';
                            $('#allParameterDtl').html(str);
                            
                            var authval = '<input type="hidden" id="authkeyId" name="authkeyId" value="'+data.authKey+'">';
                            $('#authKeyDiv').html(authval);
                            
                            $('#nextProcessDiv').show();
                        }

                    });



                }
                
                
                $("form#data").submit(function(){

                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        url: "tempController.php?action=uploadTemplate",
                        type: 'POST',
                        data: formData,
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            if(data.status == "success"){
                                console.log("sud");
                                console.log(data.template);     
                                $('#firstTemp').val(data.template);
                                 
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                    return false;
                });
                
                
                

        </script>
    </body>   
</html>
