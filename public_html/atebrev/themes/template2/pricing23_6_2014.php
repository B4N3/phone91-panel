<?php
include_once(_MANAGE_PATH_."pricing.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="<?php echo $pricingMeta_mKeyword;?>" />
<meta name="description" content="<?php echo $pricingMeta_mDescription;?>" />
<title><?php echo $pricingMeta_title; ?></title>
<?php include_once(_THEME_PATH_.'/inc/head.php'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo _THEME_PATH_.'/styles/pricing-style.css'?>" />
</head>

<body>
	
<!--header-->
<?php include_once(_THEME_PATH_.'/inc/header.php'); ?>

<!--banner starts-->
	<div id="banner" style="background-image: url(<?php echo _MANAGE_PATH_.'/'.$welcomeImage;?>)" class="pricing">
    	<div class="wrapper">
        	<h1><?php echo $pricingbannerDetail_heading;?></h1>
            <h2 class="mrT2"><?php echo $pricingbannerDetail_subHeading;?></h2>
            <a id="getin" href="<?php echo $pricingbannerDetail_link;?>"><?php echo $pricingbannerDetail_text;?></a>
            
        </div>
    </div>
<!--banner ends-->

<!--container starts-->
	<div id="container">
    	<div class="wrapper">
        <!--pricing-->
        	<h3 class="mrB4">Pricing	
                    <div class="line"><span></span></div>
            </h3>
            
            <?php include_once("pricingInc.php");
            
            $priceInfoCount = count($detailAr);
            if($priceInfoCount > 0){
            ?>
			<!--payment starts--> 
			<div  style="padding-top:40px;"><h3  class="mrB3">Payment<div class="line"><span></span></div></h3>
        

        <!--payment HTML starts-->
        <div id="bnkDtlWrap">
            <table width="100%" cellspacing="0" class="bnkDtlTbl" id="bnkDtlTbl">
                <!--template data come here-->
                <thead>
                    <tr>
                        <th>Bank</th>
                        <th>IFSC Code</th>
                        <th class="third">Account Number</th>
                        <th class="last">Account Name</th>
                    </tr>
                </thead>
                <tbody>
            <?php for($i=0;$i<$priceInfoCount;$i++) { 
            $details = $detailAr[$i];
//            print_r($details);
            ?>
                    <tr>
                        <td><?php echo $details['BankName']; ?></td>
                        <td><?php echo $details['ifsc']; ?></td>
                        <td><?php echo $details['accountNo']; ?></td>
                        <td><?php echo $details['accountName']; ?></td>
                    </tr>
                    <?php } ?>        
                   
                </tbody>
            </table>
        </div>
        
                        </div>
            <?php } ?>
            <div class="cl bankDtl"></div>
            <div class="cl"></div>
            
        </div>
<!--wrapper div ends-->
    </div>
<!--container ends-->

<!---footer---->
<?php include_once(_THEME_PATH_.'/inc/footer.php'); ?>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>
<script type="text/javascript">
//var bankDetails = <?php // echo $detailAr; ?>;
//var str = '';
//$.each( bankDetails, function( key, value ) {
//  
//  str+='<div class="fl col3">\
//                    <div class="payment">\
//                        <h4>'+value.BankName+'</h4>\
//                        <p>IFSC CODE : '+value.ifsc+'</p>\
//                        <p>Account Number : '+value.accountNo+' </p>\
//                        <p>Account Name: '+value.accountName+'</p>\
//                    </div>\
//                </div> ';
//  
//});
//
//$('.bankDtl').html(str);
</script>
</body>
</html>

