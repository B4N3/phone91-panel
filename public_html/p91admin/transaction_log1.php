<?php 
include_once("/home/voip91/public_html/newapi/general_class.php");
$genClsObj = new general_function();
if (!$genClsObj->check_reseller() && !$genClsObj->check_user() && !$genClsObj->check_admin())
{$genClsObj->expire();}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        
    <title>MSG91 : Version 1.0 : Bulk SMS Solution : Bulk SMS Gateway</title>
    <style>
.paginate {
	font-family:Arial, Helvetica, sans-serif;
	padding: 3px;
	margin: 3px;
}
.paginate a {
	padding:2px 5px 2px 5px;
	margin:2px;
	border:1px solid #999;
	text-decoration:none;
}
.paginate a:hover, .paginate a:active {
	border: 1px solid #999;
}
.paginate span.current {
	margin: 2px;
	padding: 2px 5px 2px 5px;
	border: 1px solid #999;
	font-weight: bold;
}
.paginate span.disabled {
	margin-left: 113px;
	padding:2px 5px 2px 5px;
	margin:2px;
	border:1px solid #eee;
}
</style>
    </head>
    <div class="section">
        <!--[if !IE]>start title wrapper<![endif]-->
        <div class="title_wrapper">
            <h2>Recharge History</h2>
                       <span class="title_wrapper_right" style="display: block; "></span></div>
                 <div class="sct_right">
                    <div><?php  echo $_SESSION['msg'];$_SESSION['msg'] = '';     ?></div>               

                    <div class="table_wrapper">
                        <div class="table_wrapper_inner">
                          
                               
                                <?php
                                $total_rows = 0;
                                
                                include_once("/home/voip91/public_html/newapi/user_function_class.php");  
                                $limit = 20;
                                $page = basename($_SERVER['PHP_SELF']);
                               
//		          $rowArray = $user_obj->getUserDetail_limit('payments ', '50', 'money,data,description', 'money > 0','100');
                            $dbh = $user_obj->connect_db();
                           $res = $user_obj->select_fields(" * ", ' payments ', ' where money > 0 ',' data ' , " 1500 ", $dbh);
                                mysql_close($dbh);  
                                $all = array();
                                while(($row = mysql_fetch_assoc($res))) {
                                    $all[] = $row;
                                }  
                                $total_rows = count($all);  
                              $pages = ceil($total_rows / $limit);
                          
           
   
            if ($total_rows <= 0) {
                ?>
                <div align="center" class="msg">You Do Not Have Any Records</div>       
                <?
            } else {
                ?>
                <form class="plain" action="" method="post" enctype="multipart/form-data">
                    <table cellspacing="0" width="100%">
                        <thead><!-- universal table heading -->
                            <tr>
                                    <th>Date </th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                </tr>
                        </thead>
                        <tfoot><!-- table foot - what to do with selected items --></tfoot>
<!--                        <tbody id="tab">-->
                            <?php
                            $i = 0;
                            $p = 1;
                          
                            foreach ($all as $val) {
                                if ($i == 0){ ?>
                                   <tbody id="p<?php echo $p ?>" class="numberGroup" >
                                       <?php }
                                echo '<tr>';
                                print_r("<td> <div>" .$val['data']. "</div></td>");
                                print_r(" <td><div>" .$val['money']. "</div></td>");
                                 print_r(" <td><div>" .$val['description']. "</div></td>");
                                echo '</tr>';
                                 $i++;
                                if ($i == $limit) {
                                    echo '</tbody>';
                                    $i = 0;
                                    $p++;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </form>
            <? } ?>
        </div>
    </div>
</div>
<?php
if (!isset($_REQUEST['page_number'])) {
    if ($pages > 1) {
        ?>
        <div id="jPagination"></div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.numberGroup').hide();
                $('#p1').addClass('_current');
                $('._current').show();
            });
            $(function() {	
                $("#jPagination").paginate({
                    count                   : <?php echo $pages; ?>,
                    start                   : 1,
                    display                 : 30,
                    border                  : true,
                    border_color            : '#BEF8B8',
                    text_color              : '#68BA64',
                    background_color        : '#FFF2E1',
                    border_hover_color      : '#68BA64',
                    text_hover_color        : 'black',
                    background_hover_color  : '#CAE6FF',
                    images                  : false,
                    mouse                   : 'press',
                    page_choice_display     : true,
                    show_first              : true,
                    show_last               : true,
                    onChange                : function(page){           
                        $('._current').removeClass('_current').hide();
                        $('#p'+page).addClass('_current').show();
                    }
                });
            });
        </script>
    <?php } ?>
    <script type="text/javascript">
        $(document).ready(function() { 
            var options = {
                                        	 
                url:        'transaction_log.php',
                beforeSubmit: function(data){
                    var numLen = $('#add_number').val().length;
                    var regex = /[^0-9]+/;
                    var testNum = regex.test($('#add_number').val()); 
                    if( !testNum && numLen > 7 && numLen < 16 )
                        return true;
                    else
                    {
                        show_message("Please Enter Valid Number","error");
                        return false;
                    }
                },
                success:    function(msg){ 
                                                    
                    if(msg=='Record Added successfully')
                    {
                        show_message(msg,"success"); 
                        $('#contentright').load('blockNumber.php');      
                    }
                    else
                        show_message(msg,"error");      	
                } 
            }; 
            // bind 'myForm' and provide a simple callback function 

            $('#add_blockno').ajaxForm(options);
                                        	
        });


        function load_page(page,num)
        {
            $("#loading").show();
            $("#contentright").load(page+"&page_number="+num,function() {$('#loading').hide();});
        }	


    </script>
<?php } ?>

    