<?php 
include_once("/home/voip91/public_html/newapi/general_class.php");
include_once("/home/voip91/public_html/newapi/panel_config.php");
include_once("/home/voip91/public_html/newapi/function_layer.php");
$genClsObj = new general_function();
if (!$genClsObj->check_reseller() && !$genClsObj->check_user() && !$genClsObj->check_admin())
    $genClsObj->expire();


 	$rnd = mt_rand(800, 1200); 
ob_start ("ob_gzhandler");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no" />
<title>Administration Panel</title>
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>admin-min.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>general-min.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>colorbox.css" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>dashboard.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>tables.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>pagination.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo ADMINCSSURL;?>forms.css"  />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo CSSURL;?>jquery.autocomplete.css" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo CSSURL;?>themes/base/jquery.ui.all.css" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo CSSURL;?>jquery.tablesorter.css" />
<link media="screen" rel="stylesheet" type="text/css" href="<?php echo CSSURL;?>themes/base/jquery.paginate.css" />
<script type='text/javascript' src="<?php echo JSURL;?>jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.quicksearch.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>admin_action.js?v=<?php echo $rnd; ?>"></script>
<script type="text/javascript" src="<?php echo JSURL;?>test.js?v=<?php echo $rnd; ?>"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
</head>
<body>
<div id='loading'></div>
<div id='notification'></div>
<div id="wrapper">
	<?php  include_once('inc/header.php');?>
    <div id="content" class="pa t42 left right b21">
    
        <div id="sidebar" class="pa t0 left bottom right">
           <?php include_once('inc/leftmenu.php');?>
        </div>  
                  
    	<div class="toggleContent pa t0 l220 bottom greybg" alt="hidemenu" onclick="hideMenu(this);" title="Click to Expand"></div>
        
        <div id="page" class="pa t42 bottom l230 right">
            <div class="inner">                
                <div id="ajax_content"></div>				                
            </div>
            <div class="clf"></div>
        </div>
    </div>
    <?php //include_once('inc/footer.php');   removed because design suite properly in system and creating headache in viewing other useful pages.?>	
</div>

<p><img src="images/loading.gif" alt="loading" style="display:none;" /></p>
<script type='text/javascript' src="<?php echo JSURL;?>jquery.colorbox.js"></script>
<script type='text/javascript' src="<?php echo JSURL;?>jquery.autocomplete.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.ui.widget.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.ui.core.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.form.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.paginate.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>highcharts.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.highchartTable.js"></script>
<script type="text/javascript" src="<?php echo JSURL;?>jquery.tablesorter.min.js"></script>
<script type="text/javascript" >
  //code Removed by Rahul 07Sep2012
$("#searchClient").keydown(function(){
    $('#expandCollapse').addClass('expico');
	$('#closeQuickSrch').show();
});
//$("#searchClient").focus(function(){
//    $("#expandCollapse").trigger('click')
////    $("#expandCollapse").trigger('click')
//});
var qsObject=$("#searchClient").quicksearch("ul.sidebar_menu li",{ 'onAfter': killReq});


$('#closeQuickSrch').click(function(){
	$(this).hide();
	$("#searchClient").val('').focus().trigger('keyup')
})

var hash = window.location.hash.substring(1);
if(window.location.hash)
{    
    loadpage(hash,'#ajax_content')
}
else{
   
}
		
$(window).bind('hashchange', function(){
    loadpage(hash,'#ajax_content')
});

</script>



<!--<script type="text/javascript">
var clicktracker_url        = "click-tracker.php";
var clicktracker_domains    = Array("<?php echo $_SERVER['SERVER_NAME'];?>");
var clicktracker_extensions = Array("php","javascript:;");
</script>-->
<script type="text/javascript" src="<?php echo JSURL;?>click-tracker.js"></script>
</body>
</html>
<?php // unset($smsobj);?>