<?php
//include_once("/home/voip91/public_html/newapi/panel_config.php");
//include_once("/home/voip91/public_html/sendMail/mailTemplate.php");
//include_once("/home/voip91/public_html/newapi/general_class.php");
//$genClsObj = new general_function();
//if (!$genClsObj->check_reseller() && !$genClsObj->check_user() && !$genClsObj->check_admin())
//    $genClsObj->expire();
//
//if (isset($_REQUEST['action']) && $_REQUEST['action'] == "add") {
//    if (!mb_check_encoding($_REQUEST['template'], 'UTF-8')) {
//        $message = utf8_encode($_REQUEST['template']);
//    }
//    else
//        $message = $_REQUEST['template'];
//
//    $userdata = array("panelId" => panelDbType, "dbType" => panelDbType);
//    $data = array("sendVia" => $_REQUEST['sendVia'], "template" => $message, "tag" => $_REQUEST['tag'], "subject" => $_REQUEST['subject'], "fromEmail" => $_REQUEST['fromemail'], "other" => $userdata);
//    mailTemplate::addTemplate($data);
//    return json_encode(array("ok" => 1, "msg" => "added successfully"));
//}
//if (isset($_REQUEST['action']) && $_REQUEST['action'] == "del") {
//    $id = $_REQUEST['deleteid'];
//    $delquery = array("_id" => new MongoId($id));
//    $ret = mailTemplate::deleteTemplate($delquery);
//    return json_encode(array("ok" => $ret, "msg" => "added successfully"));
//}
//if (isset($_REQUEST['action']) && $_REQUEST['action'] == "upd") {
//    $id = $_REQUEST['eid'];
//    $subject = $_REQUEST['sub'];
//    $fromEmail = $_REQUEST['femail'];
//    $tag = $_REQUEST['tag'];
//    $templt = stripslashes($_REQUEST['templt']);
//    //echo $templt; die();
//    $condition = array("_id" => new MongoId($id));
//    $setdata = array('$set' => array("template" => $templt, "subject" => $subject, "fromEmail" => $fromEmail, "tag" => $tag));
//
//    $result = mailTemplate::editTemplate($condition, $setdata);
//    return json_encode($result);
//}
//$cursor = mailTemplate::getAllTemplate(array("other.panelId" => panelId), array(), 0, 0);
?>

<div class="section table_section">
    <!--[if !IE]>start title wrapper<![endif]-->
    <div class="title_wrapper">
        <h2>Mail Templates</h2>
        <span class="title_wrapper_left"></span>		
        <span class="title_wrapper_right" style="display: block; "></span>
    </div>
    <div class="section_content">
        <!--[if !IE]>start section content top<![endif]-->
        <div class="sct">			
            <div class="sct_right">
                <div class="notification"><?php echo $_SESSION['msg']; ?></div>
                <!--<form action="#"><fieldset>-->
                <!--[if !IE]>start table_wrapper<![endif]-->
                <div class="table_wrapper">
                    <div class="table_wrapper_inner">
                        <div class="outer">
                            <form action="addMailTemplate.php" method="post" id="addtemplate" name="addtemplate" onsubmit="return validateForm();">
                                <div class="fltlt outer">
                                    <label class="lbl">template</label>
                                    <div class="thefield"> 
                                        <textarea id="temp1" name="template" rows='6' cols='70'></textarea>
                                    </div>
                                    <div class="thefield">
                                        <input type="submit" name="submit" value="Add"/>
                                    </div>
                                </div>
                                <div class="fltlt outer">
                                    <label class="lbl">subject</label>
                                    <div class="thefield">
                                        <input type="text" name="subject" id="sub1"/>
                                    </div>
                                    <label class="lbl">from email</label>
                                    <div class="thefield">
                                        <input type="text" name="fromemail" id="frome"/>
                                    </div>
                                    <label class="lbl">send via</label>
                                    <div class="thefield">
                                        <select name="sendVia" id="sendv">
                                            <option>mandrill</option>
                                            <option>panel</option>
                                            <option>mailchimp</option>
                                        </select>
                                    </div>
                                    <label class="lbl">tag</label>
                                    <div class="thefield">
                                        <input type="text" name="tag" id="tag"/>
                                    </div>
                                </div>

                            </form>
                        </div>
<!--                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="50%">Template</th> 
                                    <th>id</th>
                                    <th>subject</th>
                                    <th>from email</th>
                                    <th>tag</th> 
                                    <th>Action Menu</th>
                                </tr>
                                <?php
                                while ($cursor->hasNext()) {
                                    $row = $cursor->getNext();
                                    ?>
                                    <tr>
                                        <td>
                                            <textarea class="clickNedit templateTxt tip" id="templt<?php echo $row['_id'] ?>" rows="5" title="Click to Edit"><?php echo $row['template']; ?></textarea>
                                        </td>
                                        <td>
                                            <?php
                                            $idno = $row['_id'];
                                            echo $idno;
                                            ?>
                                        </td>
                                        <td>
                                            <textarea class="clickNedit templateTxt tip" id="sub<?php echo $row['_id'] ?>" rows="5" title="Click to Edit"> <?php echo $row['subject']; ?></textarea>
                                        </td>
                                        <td>
                                            <textarea class="clickNedit templateTxt tip" id="mail<?php echo $row['_id'] ?>" rows="5" title="Click to Edit"><?php echo $row['fromEmail']; ?></textarea>
                                        </td>
                                        <td>
                                            <textarea class="clickNedit templateTxt tip" id="tag_<?php echo $row['_id'] ?>" rows="5" title="Click to Edit"><?php echo $row['tag']; ?></textarea>
                                        </td>
                                        <td>
                                            <div>
                                                <span class="icon icon_edit"><input type="submit" name="submit" onclick="Edit_this('<?php echo $idno; ?>')" value="Update"/></span>
                                            </div>
                                            <div>
                                                <span class="icon icon_edit"><input type="submit" name="submit" onclick="Delete_this('<?php echo $row['_id'] ?>')" value="Delete"/></span>
                                            </div>
                                            <div>
                                                <span class="icon icon_edit"><input type="submit" name="submit" onclick="pView('<?php echo $row['_id'] ?>')" value="Preview"/></span>
                                            
                                            </div>
                                            
                                             <div>
                                                <span class="icon icon_edit"><input type="submit" name="submit" onclick="Preview_this('<?php echo $row['_id'] ?>')" value="Send Mail"/></span>
                                            
                                            </div>
                                            
                                        </td>  
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>-->
                    </div>
                </div>
                <!--</fieldset></form>-->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
                                function pView(id) {
                                    var pWin = window.open("", "PreviewWin");
                                    pWin.document.write(document.getElementById("templt" + id).value);
                                    return true;
                                }
                                function Edit_this(id)
                                {
                                    var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
                                    var temp = $("#templt" + id).val().trim();
                                    alert(temp);
                                    var sub = $("#sub" + id).val();
                                    var fmail = $("#mail" + id).val();
                                    var tagedit = $("#tag_" + id).val();
                                    if (temp == '') {
                                        show_message("Please Enter Template ", "error");
                                        this.focus();
                                        return false;
                                    }
                                    if (sub == '') {
                                        show_message("Please Enter subject ", "error");
                                        this.focus();
                                        return false;
                                    }
                                    if (!(fmail.match(reg))) {
                                        show_message("Please Enter valid Mail  ", "error");
                                        this.focus();
                                        return false;
                                    }
                                    $.ajax({
                                        url: 'addMailTemplate.php?action=upd',
                                        type: 'POST',
                                        data: 'eid=' + id + '&sub=' + sub + '&femail=' + fmail + '&tag=' + tagedit + '&templt=' + temp,
                                        success: function(msg)
                                        {
                                            $("#ajax_content").load("addMailTemplate.php");
                                        }
                                    });
                                }
</script>
<script type="text/javascript">
    function validateForm()
    {
        var temp = $('#addtemplate #temp1').val().trim();
        var subj = $('#addtemplate #sub1').val().trim();
        var reg = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
        if (temp == '') {
            show_message("Please Enter Template ", "error");
            this.focus();
            return false;
        }
        if (subj == '') {
            show_message("Please Enter subject ", "error");
            this.focus();
            return false;
        }
        if (!($('#addtemplate #frome').val().match(reg))) {
            show_message("Please Enter valid Mail  ", "error");
            this.focus();
            return false;
        }
        return true;
    }
    // wait for the DOM to be loaded 
    $(document).ready(function() {
        var Updateoptions = {
            target: '#ajax_content',
            url: 'addMailTemplate.php?action=add',
            success: function(msg) {
                $("#ajax_content").load("addMailTemplate.php");
            }
        };
        $('#addtemplate').ajaxForm(Updateoptions);
        return true;
    });
    function Delete_this(id)
    {
        $.ajax({
            url: 'addMailTemplate.php?action=del',
            data: 'deleteid=' + id,
            success: function(msg) {
                $("#ajax_content").load("addMailTemplate.php");
            }
        });
    }
    
    
   
    
    function Preview_this(id)
    {
        email=$("#mail" + id).val();
     sub=$("#sub" + id).val();
        newwindow=window.open('Preview.php?mail='+email+'&subject='+sub,'PreviewWin');
     
//        newwindow.document.write(id);
//	newwindow.document.write(document.getElementById("templt" + id).value);
	return false;
        
         
       
    }
</script>
