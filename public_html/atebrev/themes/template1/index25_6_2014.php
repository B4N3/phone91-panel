<?php
include_once(_MANAGE_PATH_."home.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $homeMeta_mKeyword;?>" />
<meta name="description" content="<?php echo $homeMeta_mDescription;?>" />
<title><?php echo $homeMeta_title; ?></title>
<?php 

//var_dump(dirname(__FILE__). _THEME_PATH_);
include_once(_THEME_PATH_. '/inc/head.php'); ?>
</head>

<body>
<?php include_once(_THEME_PATH_. '/inc/header.php'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/sign_up.js" ></script>
<div id="banner-wrap" class="pr">
    
    <!--banner conditional code-->
    <div id="home-banner" class="banner" style="background-image: url('<?php echo _MANAGE_PATH_.'/'.$welcomeImage;?>')">
        <div class="wrapper pr">
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
    <div id="home-banner-wrap" class=" home-banner-wrap">
        <div class=" alC clear">
            <h1 class="bold">Creativity at its best</h1>
            <h2 class="ligt">Great Design comes with understanding customer needs.</h2>
            
            <a class="btn orange alC bgTrns mrR3" href="loginWLabel.php">Login</a>
            <a class="btn blue alC bgTrns" href="signUpWLabel.php">Signup</a>
            
        </div>
    </div>
    </div>
    </div>
</div><!--/banner wrap end here-->
<div class="clear"></div>


<div class="clear"></div>
<div id="container" class="wrapper">
	<h3 class="clear fwN pr"><p>Welcome</p></h3>
    <p class="f18 lh30"><?php echo $welcomeContent; ?></p>
    
    <h3 class="clear fwN pr mrT2"><p>Features</p></h3>
    <div class="clear">
    	<div class="col-3">
        	<div class="pd2 alC">
            	<div class="circle themBg"></div>
                <h4 class="fwN">Corporate Business</h4>
                <p class="mrT1 lh24">Integer mauris lacus, cons equat in luctus id, semper sed felis. Cum sociis natoque penatibus et magnis</p>
                <a href="javascript:void(0);" class="themeLink">read more</a>
            </div>
        	
        </div>
        
        <div class="col-3">
        	<div class="pd2 alC">
            	<div class="circle themBg"></div>
                <h4 class="fwN">Corporate Business</h4>
                <p class="mrT1 lh24">Integer mauris lacus, cons equat in luctus id, semper sed felis. Cum sociis natoque penatibus et magnis</p>
                <a href="javascript:void(0);" class="themeLink">read more</a>
            </div>
        	
        </div>
        
        <div class="col-3">
        	<div class="pd2 alC">
            	<div class="circle themBg"></div>
                <h4 class="fwN">Corporate Business</h4>
                <p class="mrT1 lh24">Integer mauris lacus, cons equat in luctus id, semper sed felis. Cum sociis natoque penatibus et magnis</p>
                <a href="javascript:void(0);" class="themeLink">read more</a>
            </div>
        	
        </div>
        
        
        
    </div><!--clear div end-->
    
</div><!--/container div end-->

<?php include_once(_THEME_PATH_. '/inc/footer.php'); ?>



</body>
</html>