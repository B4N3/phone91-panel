<?php
include_once(_MANAGE_PATH_."contact.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="<?php echo $contactMeta_mKeyword;?>" />
<meta name="description" content="<?php echo $contactMeta_mDescription;?>" />
<title><?php echo $contactMeta_title; ?></title>
<?php include_once(_THEME_PATH_.'/inc/head.php')?>
</head>
<body>
<!--header-->
<?php include_once(_THEME_PATH_.'/inc/header.php')?>

<!--banner starts-->
	<div id="banner" class="contact">
    	<div class="wrapper">
        	<h1><?php echo $contactbannerDetail_heading;?></h1>
            <h2 class="mrT2"><?php echo $contactbannerDetail_subHeading;?></h2>
            <a id="getin" href="<?php echo $contactbannerDetail_link;?>"><?php echo $contactbannerDetail_text;?></a>
        </div>
    </div>
<!--banner ends-->

<!--container starts-->
	<div id="container">
    	<div class="wrapper" id="contact">
        
		<!--contact-->
        	<h3 class="mrB4">Contact <div class="line"><span></span></div></h3>
            <div id="leftmap">
            	<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="<?php echo $gMapEmbededCode; ?>"></iframe>
            </div>
            <div id="form">
            	<h4>Feel free to Drop us a Line</h4>
                <p class="mrT1">Vestibulum placerat, tortor sit amet placerat adipiscing, tortor dui condimentum nunc, in vestibulum nulla massa id dolor.</p>
                <form>
                	<input type="text" placeholder="Name (Required)"/>
                    <input type="email" placeholder="Email (Required)"/>
                    <input type="text" placeholder="Subject"/>
                    <textarea placeholder="Message"></textarea>
                    <input id="submit" type="submit" value="SUBMIT"/>
                </form>
            </div>
            <div class="cl"></div>
        </div>
		<!--wrapper div ends-->
    </div>
	<!--container ends-->
<!---footer---->
<?php include_once(_THEME_PATH_.'/inc/footer.php')?>
</body>
</html>
