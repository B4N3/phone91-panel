<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo _HOME_KEYWORD_;?>" />
<meta name="description" content="<?php echo _HOME_DESCRIPTION_;?>" />
<title>Welcome to my website</title>

<?php include_once(_THEME_PATH_.'/inc/head.php')?>
</head>

<body>
	
<!--header-->
<?php include_once(_THEME_PATH_.'/inc/header.php')?>

<!--banner starts-->
	<div id="banner" class="home">
    	<div class="wrapper">
        	<h1><?php echo _BANNER_HEADING_;?></h1>
            <h2 class="mrT2"><?php echo _BANNER_SUB_HEAD_;?></h2>
            <a id="getin" href="signUpWLabel.php">Try Demo</a>
        </div>
    </div>
<!--banner ends-->

<!--container starts-->
	<div id="container">
    	<div class="wrapper">
<!--welcome section starts-->
        	<div id="welcome">
                <h3 class="mrB4">Welcome	
                    <div class="line"></div>
                </h3>                
                <p><span class="big">D</span><?php echo _WELCOME_CONTENT_; ?></p>
                <div class="cl"></div>
    		</div>
<!--welcome section ends-->
            
<!--features section starts-->           
            <div id="features">
                <h3 class="mrT4 mrB4">Features	
                    <div class="line"></div>
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
<!--container ends-->

<!---footer---->
<?php include_once(_THEME_PATH_.'/inc/footer.php')?>

</body>
</html>
