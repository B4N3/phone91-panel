<?php
include_once(_MANAGE_PATH_."home.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="<?php echo $homeMeta_mKeyword;?>" />
<meta name="description" content="<?php echo $homeMeta_mDescription;?>" />
<title><?php echo $homeMeta_title; ?></title>
<?php include_once(_THEME_PATH_.'/inc/head.php')?>
</head>
<body>
<!--header-->
<?php include_once(_THEME_PATH_.'/inc/header.php')?>
<!--banner starts-->
	<div id="banner" style="background-image: url(<?php echo _MANAGE_PATH_.'/'.$welcomeImage;?>)" class="home">
    	<div class="wrapper">
        	<h1><?php echo $homebannerDetail_heading;?></h1>
            <h2 class="mrT2"><?php echo $homebannerDetail_subHeading;?></h2>
			<div>
            <a id="getin" href="<?php echo $homebannerDetail_link;?>"><?php echo $homebannerDetail_text;?></a>
			</div>
        </div>
    </div>
<!--//banner-->
<!--container starts-->
	<div id="container">
    	<div class="wrapper">
        <!--welcome section starts-->
            <div id="welcome">
                <h3 class="mrB4">Welcome	
                    <div class="line"><span></span></div>
                </h3>                
                <p><span class="big"></span><?php echo $welcomeContent; ?></p>
                <div class="cl"></div>
            </div>
        <!--welcome section ends-->
            
		<!--features section starts-->           
            <div id="features">
                <h3 class="mrT4 mrB4">Features	
                    <div class="line"><span></span></div>
                </h3>
                <ul>
                    <li>
                        <div class="circle">
                        	<div class="icont i1"></div>
                        </div>
                        <p class="fhead mrT2 mrB2">Corporate Business</p>
                        <p class="mrB1">mauris lacus, cons equat in luctus id, semper sed felis. </p>
                        <a class="lmore">Learn more</a>
                    </li>
                    
                    <li>
                        <div class="circle">
                        	<div class="icont i2"></div>
                        </div>
                        <p class="fhead mrT2 mrB2">Responsive Theme</p>
                        <p class="mrB1">mauris lacus, cons equat in luctus id, semper sed felis. </p>
                        <a class="lmore">Learn more</a>
                    </li>
                    
                    <li>
                        <div class="circle">
                        	<div class="icont i3"></div>
                        </div>
                        <p class="fhead mrT2 mrB2">Coded Carefully</p>
                        <p class="mrB1">mauris lacus, cons equat in luctus id, semper sed felis. </p>
                        <a class="lmore">Learn more</a>
                    </li>
                    <div class="cl"></div>
                </ul>
            </div>
		<!--features section starts-->
        </div>
        <!--wrapper div ends-->
      </div>
  <!--//container-->
<?php include_once(_THEME_PATH_.'/inc/footer.php')?>
</body>
</html>
