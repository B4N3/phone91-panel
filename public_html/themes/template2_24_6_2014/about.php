<?php
include_once(_MANAGE_PATH_."about.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="<?php echo $aboutMeta_mKeyword;?>" />
<meta name="description" content="<?php echo $aboutMeta_mDescription;?>" />
<title><?php echo $title; ?></title>

<?php include_once(_THEME_PATH_.'/inc/head.php')?>
</head>

<body>
	
<!--header-->
<?php include_once(_THEME_PATH_.'/inc/header.php')?>

<!--banner starts-->
	<div id="banner" style="background-image: url(<?php echo $welcomeImage;?>)" class="about">
    	<div class="wrapper">
        	<h1><?php echo $aboutbannerDetail_heading;?></h1>
            <h2 class="mrT2"><?php echo $aboutbannerDetail_subHeading;?></h2>
            <a id="getin" href="<?php echo $aboutbannerDetail_link;?>"><?php echo $aboutbannerDetail_text;?></a>
        </div>
    </div>
<!--banner ends-->

<!--container starts-->
	<div id="container">
    	<div class="wrapper">
        
<!--who we are-->
			<div>
				<h3 class="mrB3">Who we are	
						<div class="line"><span></span></div>
				</h3>
				<p class="bigD decriptPadd"> <span class="big">D</span><?php echo $whoUR; ?></p>
				<div class="cl"></div>
            </div>
<!--our mission-->   
			<div>        
				<h3 class="lessSpace">Our Mission	
					<div class="line"><span></span></div>
				</h3>
				<p class="decriptPadd"><?php echo $mission; ?></p>
				<div class="cl"></div>
            </div>
<!--our vision-->     
			<div>      
				<h3 class="lessSpace">Our Vision	
					<div class="line"><span></span></div>
				</h3>
				<p class="decriptPadd"> <?php echo $vision; ?></p>
				<div class="cl"></div>
            </div>    
                
        </div>
<!--wrapper div ends-->
    </div>
<!--container ends-->

<!---footer---->
<?php include_once(_THEME_PATH_.'/inc/footer.php')?>

</body>
</html>
