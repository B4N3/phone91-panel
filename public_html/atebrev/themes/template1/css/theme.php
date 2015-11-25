<?php
    header("Content-type: text/css; charset: UTF-8");
	$themeBg = "#555";
	$themeColor = "#262626";
	$linkColor = "#555555";
	$circleclr = "#f8f8f8";
	$themelinks=" #17A7DB";
	$CDNURL = "url(../images/banner-1.jpg)";
	
	/*
	for htaccess
	<FilesMatch "^.*?style.*?$">
SetHandler php5-script
</FilesMatch>*/
?>

#header, .btn:hover, .themBg, #footer, .themeBg {
   background-color:<?php echo $themeBg; ?>;
}
#nav a:hover, #nav a.active, h3 > p, .themeLink{
	color:<?php echo $themeColor; ?>;
}
.themeLink{
color:<?php echo $themelinks; ?>;
}
#home-banner, #all-banner{
 	background-image:<?php echo $CDNURL;?>; 
}
.national div a{ color:<?php echo $themeColor; ?>;}
.circle{
 	background:<?php echo $circleclr; ?>;
}
