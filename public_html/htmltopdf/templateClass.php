<?php
include_once 'session.php';
class templateClass
{
//    function getFirstTemplate($param)
//    {
//        
//        if(isset($param['companyName'])){
//            $companyName = $param['companyName'];
//        }else
//            $companyName = "walkover";
//        
//        if(isset($param['amount'])){
//            $amount = $param['amount'];
//        }else
//            $amount = "500";
//        
//        if(isset($param['currency'])){
//            $currency = $param['currency'];
//        }else
//            $currency = "INR";
//        
//        
//        $str = '<html>
//<head>
//    <title>Print Invoice</title>
//    <style>
//        *
//        {
//            margin:0;
//            padding:0;
//            font-family:Arial;
//            font-size:10pt;
//            color:#000;
//        }
//        body
//        {
//            width:100%;
//            font-family:Arial;
//            font-size:10pt;
//            margin:0;
//            padding:0;
//        }
//         
//        p
//        {
//            margin:0;
//            padding:0;
//        }
//         
//        #wrapper
//        {
//            width:180mm;
//            margin:0 15mm;
//        }
//         
//        .page
//        {
//            height:297mm;
//            width:210mm;
//            page-break-after:always;
//        }
// 
//        table
//        {
//            border-left: 1px solid #ccc;
//            border-top: 1px solid #ccc;
//             
//            border-spacing:0;
//            border-collapse: collapse; 
//             
//        }
//         
//        table td 
//        {
//            border-right: 1px solid #ccc;
//            border-bottom: 1px solid #ccc;
//            padding: 2mm;
//        }
//         
//        table.heading
//        {
//            height:50mm;
//        }
//         
//        h1.heading
//        {
//            font-size:14pt;
//            color:#000;
//            font-weight:normal;
//        }
//         
//        h2.heading
//        {
//            font-size:9pt;
//            color:#000;
//            font-weight:normal;
//        }
//         
//        hr
//        {
//            color:#ccc;
//            background:#ccc;
//        }
//         
//        #invoice_body
//        {
//            height: 149mm;
//        }
//         
//        #invoice_body , #invoice_total
//        {   
//            width:100%;
//        }
//        #invoice_body table , #invoice_total table
//        {
//            width:100%;
//            border-left: 1px solid #ccc;
//            border-top: 1px solid #ccc;
//     
//            border-spacing:0;
//            border-collapse: collapse; 
//             
//            margin-top:5mm;
//        }
//         
//        #invoice_body table td , #invoice_total table td
//        {
//            text-align:center;
//            font-size:9pt;
//            border-right: 1px solid #ccc;
//            border-bottom: 1px solid #ccc;
//            padding:2mm 0;
//        }
//         
//        #invoice_body table td.mono  , #invoice_total table td.mono
//        {
//            font-family:monospace;
//            text-align:right;
//            padding-right:3mm;
//            font-size:10pt;
//        }
//         
//        #footer
//        {   
//            width:180mm;
//            margin:0 15mm;
//            padding-bottom:3mm;
//        }
//        #footer table
//        {
//            width:100%;
//            border-left: 1px solid #ccc;
//            border-top: 1px solid #ccc;
//             
//            background:#eee;
//             
//            border-spacing:0;
//            border-collapse: collapse; 
//        }
//        #footer table td
//        {
//            width:25%;
//            text-align:center;
//            font-size:9pt;
//            border-right: 1px solid #ccc;
//            border-bottom: 1px solid #ccc;
//        }
//    </style>
//</head>
//<body>
//<div id="wrapper">
//     
//    <p style="text-align:center; font-weight:bold; padding-top:5mm;">INVOICE</p>
//    <br />
//    <table class="heading" style="width:100%;">
//        <tr>
//            <td style="width:80mm;">
//                <h1 class="heading">'.$companyName.'</h1>
//                <h2 class="heading">
//                    123 Happy Street<br />
//                    CoolCity - Pincode<br />
//                    Region , Country<br />
//                     
//                    Website : www.website.com<br />
//                    E-mail : info@website.com<br />
//                    Phone : +1 - 123456789
//                </h2>
//            </td>
//            <td rowspan="2" valign="top" align="right" style="padding:3mm;">
//                <table>
//                    <tr><td>Invoice No : </td><td>11-12-17</td></tr>
//                    <tr><td>Dated : </td><td>01-Aug-2011</td></tr>
//                    <tr><td>Currency : </td><td>'.$currency.'</td></tr>
//                </table>
//            </td>
//        </tr>
//        <tr>
//            <td>
//                <b>Buyer</b> :<br />
//                Client Name<br />
//            Client Address
//                <br />
//                City - Pincode , Country<br />
//            </td>
//        </tr>
//    </table>
//         
//         
//    <div id="content">
//         
//        <div id="invoice_body">
//            <table>
//            <tr style="background:#eee;">
//                <td style="width:8%;"><b>Sl. No.</b></td>
//                <td><b>Product</b></td>
//                <td style="width:15%;"><b>Quantity</b></td>
//                <td style="width:15%;"><b>Rate</b></td>
//                <td style="width:15%;"><b>Total</b></td>
//            </tr>
//            </table>
//             
//            <table>
//            <tr>
//                <td style="width:8%;">1</td>
//                <td style="text-align:left; padding-left:10px;">Software Development<br />Description : Upgradation of telecrm</td>
//                <td class="mono" style="width:15%;">1</td><td style="width:15%;" class="mono">'.$amount.'</td>
//                <td style="width:15%;" class="mono">'.$amount.'</td>
//            </tr>         
//            <tr>
//                <td colspan="3"></td>
//                <td></td>
//                <td></td>
//            </tr>
//             
//            <tr>
//                <td colspan="3"></td>
//                <td>Total :</td>
//                <td class="mono">'.$amount.'</td>
//            </tr>
//        </table>
//        </div>
//        <div id="invoice_total">
//            Total Amount :
//            <table>
//                <tr>
//                    <td style="text-align:left; padding-left:10px;">'.$amount.'</td>
//                    <td style="width:15%;">'.$currency.'</td>
//                    <td style="width:15%;" class="mono">'.$amount.'</td>
//                </tr>
//            </table>
//        </div>
//        <br />
//        <hr />
//        <br />
//         
//    </div>
//     
//    <br />
//     
//    </div>
//        
//</body>
//</html>';     
//        
//return $str;
//    }
//    
    
    
    function saveTemplateInPdf($param,$isApi){
        
        $html = $this->gethtmlTemplate($param);
        
        $fileName = date('dmYHis').$this->generateRandomString(4);
        
        include("MPDF60/mpdf.php"); 
        $mpdf=new mPDF('c','A4','','' , 0 , 0 , 0 , 0 , 0 , 0); 
        $mpdf->SetDisplayMode('fullpage');
//        $mpdf->list_indent_first_level = 0;  // 1 or 0 - whether to indent the first level of a list
//        $stylesheet = '<style>'.file_get_contents('pdf.css').'</style>'; // external css
//        $mpdf->WriteHTML($stylesheet,1);
        $mpdf->WriteHTML($html);
//        if($param['type'] == 1){
//            $mpdf->Output();
//        }else
            $mpdf->Output('pdfFolder/'.$fileName.'.pdf','F');
        
        if($isApi == 1){
            return $fileName;
        }
        return $fileName.".pdf";
        
    }
    
    
    function generateRandomString($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    function saveTemplate($param,$userId){
        
        if($userId == '' || $userId == null){
            return json_encode(array("status"=>"error","message"=>"You have no permission, Please contact to provider."));
        }
        
        $templateName = $this->generateRandomString(5).date('His');
        $myfile = fopen("htmlTemplates/".$templateName.".html", "w") or die("Unable to open file!");
        fwrite($myfile, $param['html']);
        fclose($myfile);
        
        $conn = $this->dbConnect();
        $str = "INSERT INTO `userHtmlTemplate` (userId, templateId) VALUES ($userId,$templateName)";
        mysql_query($str);
        
        
        
        return $templateName;        
    }
    
    function gethtmlTemplate($param){
        
        $fileName = "htmlTemplates/".$param['htmlTempId'].".html";
        $myfile = fopen($fileName, "r") or die("Unable to open file!");
        $str = fread($myfile,filesize($fileName));
        fclose($myfile);
        
        foreach($param as $key=>$value){
           $str = str_replace("<%".$key."%>", $value, $str);
        }
                
        return $str;
        
    }
    
    
    function uploadTemplate($file){
        
        //if file already exists
        if (file_exists("htmlTemplates/" . $file["fileToUpload"]["name"])) {
            return json_encode(array("status"=>"error","message"=>"file already exists"));
        }
        else {
            #- finding extension of file
            $extension = (explode(".", $file["fileToUpload"]["name"]));
            #- if file extension is not csv return
            if($extension[1] != 'html')
            return json_encode(array("msgtype"=>"error","msg"=>"Only HTML Files Are allowed."));
            #new file name 
            $newFileName = $extension[0].time().".".$extension[1];

            

            move_uploaded_file($file["fileToUpload"]["tmp_name"], "htmlTemplates/" . $newFileName);
            
            $fileName = "htmlTemplates/" . $newFileName;
            $myfile = fopen($fileName, "r") or die("Unable to open file!");
            $str = fread($myfile,filesize($fileName));
            fclose($myfile);
            
            return json_encode(array("status"=>"success","template"=>$str));
            
       }
    }
    
    function login($param){
       // session_start(); 
        //starting the session for user profile page
        $db = $this->dbConnect();
        if(!empty($param['userName'])) 
        {
          $query = mysql_query("SELECT * FROM userLogin where userName = '".$param['userName']."' AND password = '".$param['password']."'") or die(mysql_error());
          $row = mysql_fetch_array($query) or die(mysql_error());
          
          if(!empty($row['userName']) && !empty($row['password'])) 
              {
              
              $_SESSION['userName'] = $row['userName']; 
              $_SESSION['userId']=$row['userId'];
              
               return json_encode(array("status"=>"success","message"=>"login successfully."));
              }else { 
                  return json_encode(array("status"=>"error","message"=>"SORRY... YOU ENTERD WRONG ID AND PASSWORD... PLEASE RETRY...")); 
              }
        }
    }
    
    function dbConnect(){
        
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'htmlToPdf');
        define('DB_USER','pdfuser');
        define('DB_PASSWORD','J1glF5XbJ7FJ1');
        $con=mysql_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysql_error()); 
        $db=mysql_select_db(DB_NAME,$con) or die("Failed to connect to MySQL: " . mysql_error());
        return $db;

    }
    
    
    function generateAuthkey($param,$userId){
        
        if($userId == '' || $userId == null){
            return json_encode(array("status"=>"error","message"=>"You have no permission to update auth key."));
        }
        
        if($param['authkey'] == '' || $param['authkey'] == null){
            return json_encode(array("status"=>"error","message"=>"Please enter valid auth key."));
        }
        
        $conn = $this->dbConnect();
        $sql = "UPDATE userLogin SET authKey='".$param['authkey']."' WHERE userId='".$userId."'";
        
        
        if(mysql_query($sql)) {
            return json_encode(array("status"=>"success","message"=>"Auth key updated successfully."));
        } else {
            return json_encode(array("status"=>"error","message"=>"Auth key Not updated."));
        }
    }
    
    
    function getallTemplate($userId){
        
        $db = $this->dbConnect();
        
        if($userId == '' || $userId == null){
            return json_encode(array("status"=>"error","message"=>"You have no permission get template."));
        }
        
        $query = mysql_query("SELECT * FROM userHtmlTemplate where userId = '".$userId."'") or die(mysql_error());
        $templates = array();
        while($row = mysql_fetch_array($query)){
               
                $data['templateId'] = $row['templateId']; 
                $templates[]=$data;
        }
            
        return json_encode(array("status"=>"success","message"=>"get all template","templates"=>$templates));
        
         
    }
    
    function gethtmlFromTemplate($param,$userId){
        
        $db = $this->dbConnect();
        
        if($userId == '' || $userId == null){
            return json_encode(array("status"=>"error","message"=>"You have no permission to get template."));
        }
        
        $query = mysql_query("SELECT * FROM userHtmlTemplate where userId = '".$userId."' and templateId='".$param['template']."'") or die(mysql_error());
        
        $num_rows = mysql_num_rows($query);
        
        if($num_rows > 0){
            
            $fileName = "htmlTemplates/" . $param['template'].".html";
            $myfile = fopen($fileName, "r") or die("Unable to open file!");
            $str = fread($myfile,filesize($fileName));
            fclose($myfile);
            
            return json_encode(array("status"=>"success","template"=>$str));
            
        }
            
        return json_encode(array("status"=>"error","message"=>"you have no permission to get template."));
    }
    
    function getUserAuthKey($userId){
        
         if($userId == '' || $userId == null){
            return 0;
        }
        $authKey = 0;
         $query = mysql_query("SELECT * FROM userLogin where userId = '".$userId."'") or die(mysql_error());
         $row = mysql_fetch_array($query) or die(mysql_error());
         $authKey = $row['authKey']; 
            
         return $authKey;
    }
    
}


?>
