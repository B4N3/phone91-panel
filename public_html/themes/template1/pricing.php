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

<title><?php echo $pricingMeta_title;?></title>

<?php include_once(_THEME_PATH_.'/inc/head.php'); ?>

<link rel="stylesheet" type="text/css" href="<?php echo _THEME_PATH_.'/css/pricing-style.css'?>" />

</head>

<body>

<?php include_once(_THEME_PATH_.'/inc/header.php'); ?>

<div id="banner-wrap" class="pr">

    <!--banner conditional code-->

    <div id="all-banner" class="banner" style="background-image: url('<?php echo _MANAGE_PATH_.'/'.$welcomeImage;?>')">

    <!--[if !IE]>

    <svg xmlns="http://www.w3.org/2000/svg" id="svgroot" viewBox="" width="100%" height="450">

    <defs>

     <filter id="filtersPicture">

       <feComposite result="inputTo_38" in="SourceGraphic" in2="SourceGraphic" operator="arithmetic" k1="0" k2="1" k3="0" k4="0" />

       <feColorMatrix id="filter_38" type="saturate" values="0" data-filterid="38" />

    </filter>

    </defs>

    <image filter="url(&quot;#filtersPicture&quot;)" x="0" y="0" width="100%" height="450px" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="http://localhost/msg91-panel-template/images/banner-1.jpg" />

    </svg>

    <![endif]-->

    <!--banner conditional code end here-->

    <div id="all-banner-wrap"  class="home-banner-wrap">

        <div class="wrapper alC clear">

            <h1><?php echo $pricingbannerDetail_heading;?></h1>

            <h2 class="ligt"><?php echo $pricingbannerDetail_subHeading;?></h2>

            <a class="btn orange alC bgTrns mrR3" href="javascript:void(0);">Login</a>

            <a class="btn blue alC bgTrns" href="<?php echo $pricingbannerDetail_link?>"><?php echo $pricingbannerDetail_text?></a>

        </div>

    </div>

    </div>

</div><!--//Banner-->

<div id="container" class="wrapper">

	<h3 class="clear fwN pr"><p><span></span></p>Pricing</h3>

    <?php include_once("pricingInc.php"); 
    
    $priceInfoCount = count($detailAr);
            if($priceInfoCount > 0){
    ?>

    <div class="clear mrT2">

    	<h3 class="clear pr"><p><span></span></p>Payment</h3>
        
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
                        <td style="text-align:center"><?php echo $details['BankName']; ?></td>
                        <td style="text-align:center"><?php echo $details['ifsc']; ?></td>
                        <td style="text-align:center"><?php echo $details['accountNo']; ?></td>
                        <td style="text-align:center"><?php echo $details['accountName']; ?></td>
                    </tr>
                    <?php } ?> 
                   
                </tbody>
            </table>
        </div>
        <!--//END OF payment HTML-->
        
     


    </div><!--//Clear-->
 <?php } ?>
</div><!--//Container-->



<?php include_once(_THEME_PATH_.'/inc/footer.php'); ?>

<script type="text/javascript" src="js/jquery-ui-1.10.3.custom.min.js"></script>

<script type="text/javascript">
//
//var bankDetails = <?php // echo _BANK_DETAIL_; ?>;
//
//var str = '';
//
//$.each( bankDetails, function( key, value ) {
//
//  str+=' <li>\
//
//                    <div class="payment pdR3">\
//
//                        <h4 class="fwN ligt">'+value.BankName+'</h4>\
//
//                        <p>IFSC CODE : '+value.ifsc+'</p>\
//
//                        <p>Account Number : '+value.accountNo+' </p>\
//
//                        <p>Account Name: '+value.accountName+'</p>\
//
//                    </div>\
//
//                </li>';
//
//  
//
//});
//
//$('#bankDetailsUl').html('');
//
//$('#bankDetailsUl').append(str);

</script>

</body>

</html>