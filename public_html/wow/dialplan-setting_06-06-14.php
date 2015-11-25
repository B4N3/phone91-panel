<?php
include dirname(dirname(__FILE__)) . '/config.php';
if (!$funobj->login_validate() || !$funobj->check_admin()) {
    $funobj->redirect("/index.php");
}

$page = "1";
if (isset($_REQUEST['pageNo']) && !empty($_REQUEST['pageNo'])) {
    $page = $_REQUEST['pageNo'];
}
?>
<!--Dial plan Setting-->
<!--For Add below code will visible-->
<label  class="searchAdd">
<!--    <input type="text" class="fl" placeholder="Add Country" id="countryName" name="countryName">             
    <input type="button"  title="Add" class="btn btn-medium btn-primary clear" value="Add" onclick="addCountry()">-->
</label>
<!--//For Add below code will visible-->
<input type="text" class="fl" placeholder="Find Country" id="countryNameSearch" name="search" value="" onkeyup= "searchPrefix($(this))">   

<!--<a target="_blank" href="/controller/dialPlanController.php?planId=<?php echo $_REQUEST['planId']; ?>&type=csv&call=exportDialPlan" ><input type="button" value="Export Csv"/></a>
<a target="_blank" href="/controller/dialPlanController.php?planId=<?php echo $_REQUEST['planId']; ?>&type=xlsx&call=exportDialPlan" ><input type="button" value="Export xls"/></a>
-->

<div class="tablflip-scroll dialplanTbl">
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="cmntbl  boxsize grayTabl bdrNon">
        <thead>
            <tr>
                <th width="18%">User Prefix</th>
                <th width="18%">Route</th>

                <th width="18%">&nbsp;</th>
                <th width="46%"><p class="arBorder fl cp sucsses" title="Add" onclick="addRow($(this))">
            <span class="ic-12 add "></span>
        </p></th>
        </tr>
        </thead>

        <tbody id="prefixTableBody">
            <tr class="even">
                <td><input type="text" value="" class="isInput120"/></td>
                <td>
                    <select name="" class="isInput150">
                        <option>100</option>
                        <option>122</option>
                    </select>
                </td>
                <td>
                    <div class="fr mrL2">\
                        <span class="ic-24 delete cp fl" title="Delete" onclick="deleteRow($(this))"></span>
                        <span class="ic-24 edit cp fl mrT mrR1 db" title="Edit"></span>
                    </div>
                </td>
                <td>&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <div id="pagination" style="width: 100%;"></div>
    <br>
</div>
<!--//Dial plan Setting-->
<?php if (!isset($_REQUEST['planId'])) { ?>
    <script> $('#prefixTableBody').html('<tr class="addState">\
                     <td colspan="100%">\
                            <span class="fl db mrR1 mrT1">Invalid PlanId Please select a plan from left side</span>\
                    </td>\
                </tr>');</script>
    <?php
    exit();
}
?>
<script type="text/javascript">
    var _globalRoute = null;

    function addRow()
    {
        var tr = $('#prefixTableBody tr');

        var str = '<tr class="even">\
                   <td><input type="text" countryId="" value="" class="isInput120 uPrefix" onblur="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\',\'planSlno\')" /></td>\
                   <td>\
                                                <select name="" class="isInput150 selRoute" onchange="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\',\'planSlno\')">\
                                                        ' + _globalRoute + '\
                                                </select>\
                                   </td>\
                   <td>\
                    <div class="fr mrL2">\
                    <span class="ic-24 delete cp fl delPrefixBtn" title="Delete" onclick="deleteRow($(this))"></span>\
                     <span class="ic-24 edit cp fl mrT mrR1 db editPrefixBtn" title="Edit" onclick="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\',\'planSlno\')"></span>\
                     </div>\
                                        </td>\
                   <td>&nbsp;</td>\
                </tr>';

        if (tr.length > 0)
        {
            $('#prefixTableBody').prepend(str);
        }
        else
        {
            $('#prefixTableBody').html(str);
        }

    }



//    $("#demo2").paginate({
//        count: 50,
//        start: 5,
//        display: 10,
//        border: false,
//        text_color: '#304254',
//        background_color: '#F5F6F7',
//        text_hover_color: '#fff',
//        background_hover_color: '#304254'
//    });


    function addCountry()
    {
        var countryName = $('#countryName').val();
        var countryReg = /[^A-Za-z\s]/;

        // validation to check country name
        if (countryReg.test(countryName) || countryName == "")
        {
            show_message("Error Invalid country name only apphabet and space is allowed must not be more then 30 character", "error");
            return;
        }


        $.ajax({
            url: "/controller/dialPlanController.php",
            type: "POST",
            dataType: "JSON",
            data: {"call": "addCountry", "country": countryName, "planId": "<?php echo $_REQUEST['planId']; ?>"},
            success: function(response) {
                show_message(response.msg, response.status);
                if (response.status == "success")
                {
                    $('#countryName').val("");
                    var str = '<tr class="addState">\
                 <td colspan="100%">\
                        <span class="fl db mrR1 mrT1">' + countryName + '</span>\
                     <p class="arBorder fl cp sucsses" title="Add" onclick="addRow($(this))">\
                        <span class="ic-12 add "></span>\
                    </p>\
                </td>\
            </tr><tr class="even">\
                   <td><input type="text" countryId="' + response.lastInsertId + '" value="" class="isInput120 uPrefix" onblur="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')" /></td>\
                   <td>\
                                                <select name="" class="isInput150 selRoute" onchange="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')">\
                                                        ' + _globalRoute + '\
                                                </select>\
                                   </td>\
                   <td>\
                                                <div class="fr mrL2">\
                                                <span class="ic-24 delete cp fl delPrefixBtn" title="Delete" onclick="deleteRow($(this))"></span>\
                                                 <span class="ic-24 edit cp fl mrT mrR1 db editPrefixBtn" title="Edit" onclick="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')"></span>\
                                                 </div>\
                                        </td>\
                   <td>&nbsp;</td>\
                </tr>';

//                var str1 = $('#prefixTableBody tr:eq(1)').clone();


//                   $('.uPrefix',str1).val("");
//                  $('.uPrefix',str1).attr("countryId",''+response.lastInsertId); 
//                             $('#prefixTableBody').prepend(str1);
                    $('#prefixTableBody').prepend(str);
                }

            }
        })
    }

    function renderPrefix(response)
    {
        var str = "";
        var sel = $('<select onchange="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')">').addClass('selRoute');
        sel.append(_globalRoute);
        $.each(response, function(key, value) {


            if ($.type(value) === 'object')
            {

                /* str += '<tr class="addState">\
                 <td colspan="100%">\
                 <span class="fl db mrR1 mrT1">'+((typeof value.country === "undefined")?"Not Defined":value.country)+'</span>\
                 <p class="arBorder fl cp sucsses" title="Add" onclick="addRow($(this))">\
                 <span class="ic-12 add "></span>\
                 </p>\
                 </td>\
                 </tr>';*/
                if (value.prefix == null || typeof value.prefix === "undefined")
                {
                    str += '<tr class="even">\
                   <td><input type="text" countryId="' + value.slno + '" value="" class="isInput120 uPrefix" onblur="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')" /></td>\
                   <td>\
                                                <select name="" class="isInput150 selRoute" onchange="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')">\
                                                        ' + _globalRoute + '\
                                                </select>\
                                   </td>\
                   <td>\
                                                <div class="fr mrL2">\
                                                <span class="ic-24 delete cp fl delPrefixBtn" title="Delete" onclick="deleteRow($(this))"></span>\
                                                 <span class="ic-24 edit cp fl mrT mrR1 db editPrefixBtn" title="Edit" onclick="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\')"></span>\
                                                 </div>\
                                        </td>\
                   <td>&nbsp;</td>\
                </tr>';
                } else {

                    $.each(value.prefix, function(keyOne, valueOne) {
                        sel.attr('onchange','addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\','+valueOne.slNo+')');
                        sel.find('option').removeAttr('selected');
                        sel.find('option[value=' + valueOne.routeId + ']').attr('selected', 'selected');


                        var selStr = sel.prop("outerHTML");

                        str += '<tr class="even">\
                   <td><input type="text" countryId="' + value.slno + '" value="' + valueOne.userPrefix + '" class="isInput120 uPrefix" onblur="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\',' + valueOne.slNo + ')"/></td>\
                   <td>' + selStr + '</td>\
                   <td>\
                                                <div class="fr mrL2">\
                                                <span class="ic-24 delete cp fl delPrefixBtn" title="Delete" onclick="deleteRow($(this),\'<?php echo $_REQUEST['planId']; ?> \',\'' + valueOne.slNo + '\',2)"></span>\
                                                 <span class="ic-24 edit cp fl mrT mrR1 db editPrefixBtn" title="Edit" onclick="addPrefix($(this),\'<?php echo $_REQUEST['planId']; ?>\',' + valueOne.slNo + ')"></span>\
                                                 </div>\
                                        </td>\
                   <td>&nbsp;</td>\
                </tr>';


                    })
                }

            }
        })
        return str;


    }

    var _globalTImeOut = null;
    function searchPrefix(ths,page)
    {
        if (_globalTImeOut != null)
            clearTimeout(_globalTImeOut);
        var keyword = ths.val();

        if (keyword == "")
        {
            getPrefix();
            return false;
        }
        _globalTImeOut = setTimeout(function() {

            $.ajax({
                url: "/controller/dialPlanController.php",
                type: "POST",
                dataType: "JSON",
                data: {"call": "searchPrefix", "planId": "<?php echo $_REQUEST['planId']; ?>", "search": 1, "keyword": keyword,"pageNumber":page},
                success: function(response)
                {
                    if (response.status == "error")
                    {
                        show_message(response.msg, response.status);
//                      $('#prefixTableBody').html("No data to display please add a country to start");
                    }
                    else
                    {
                        var str = renderPrefix(response);
                        $('#prefixTableBody').html(str);
                        
                        if(str == "" || response.totalPages <= 1)
                            $('#pagination').hide();
                        else
                            $('#pagination').show();
                        
                        prefixPagination(page, response.totalPages,"search",ths)
                    }
                }
            })
        }, 600);
    }
    function getPrefix(page)
    {

//        page = '<?php // echo $page; ?>';
        $.ajax({
            url: "/controller/dialPlanController.php",
            type: "POST",
            dataType: "JSON",
            data: {"call": "getPrefix", "planId": "<?php echo $_REQUEST['planId']; ?>", "pageNumber": page},
            success: function(response) {

                if (response.status == "error")
                {
                    show_message(response.msg, response.status);
                    $('#prefixTableBody').html("No data to display please add a country to start");
                }
                else
                {

                    var str = renderPrefix(response);
                    $('#prefixTableBody').html(str);

                    //  console.log('count'+response.count);
                    //    console.log(response);
                    if(str == "" || response.totalPages <= 1)
                        $('#pagination').hide();
                    else
                        $('#pagination').show();
                    
                    prefixPagination(page, response.totalPages,"getDetails")
                }


            }
        })
    }
    getRoute()


    function prefixPagination(start,count,funType,ths)
    {
        if (start == undefined || start == 0 || start == "")
            start = 1;

        if (count == undefined || count == 0 || count == "")
            count = 1;

        $(function() {
            $('#pagination').paginate({
                count: count,
                start: start,
                display: 10,
                border: true,
                text_color: '#000',
                background_color: '#ddd',
                text_hover_color: '#fff',
                background_hover_color: '#333',
                images: false,
                mouse: 'press',
                page_choice_display: true,
                show_first: true,
                show_last: true,
                onChange: function(page)
                {
                    console.log(page);
//                    window.location.href = window.location.href + '&pageNo=' + page;
                    //(page);
                   
                    if(funType == "getDetails")
                         getPrefix(page);
                     else if(funType == "search")
                         searchPrefix(ths,page);
                }

            });
        });

    }

    function deleteRow(ths, planId, serialNum, type) {
        if (type == 2)
        {
            var confirmFlag = confirm("Are you sure you want to delete this entry");
            if (confirmFlag == true)
            {
                $.ajax({
                    url: "/controller/dialPlanController.php",
                    type: "POST",
                    dataType: "JSON",
                    data: {"call": "deletePrefix", "dialPlanId": planId, "prefixId": serialNum},
                    success: function(response) {
                        show_message(response.msg, response.status);
                        if (response.status == "success")
                            ths.parents('tr').remove();
                    }
                })

            }

        }
        else
        {
            ths.parents('tr').remove();
        }
    }


    function getRoute()
    {

        $.ajax({
            url: "/controller/routeController.php",
            type: "POST",
            dataType: "JSON",
            data: {"call": "getRouteDetails"},
            success: function(response) {

                var str = "<option value=''>Select</option>";
                $.each(response, function(key, value) {
                    str += '<option value="' + value.routeId + '">' + value.route + '</option>';
                })
                //$('.selRoute').html(str);
                _globalRoute = str;
                getPrefix();
            }
        })
    }

    function addPrefix(ths, planId, id)
    {
        var tr = ths.parents('tr');
        var prefix = $('.uPrefix', tr).val();
        var route = $('.selRoute', tr).val();
        var countryId = $('.uPrefix', tr).attr('countryId');

        var prefixReg = /[^0-9\*\#\+]/;
        var idReg = /[^0-9]/;

        // validation to check country name
        if (prefixReg.test(prefix) || prefix == "" || prefix.length > 10)
        {
            show_message("Error Invalid Prefix Only Number and (*,#,+) are allowed", "error");
            return;
        }

        if (id != "" && id != "planSlno" && id !== undefined && idReg.test(id))
        {
            show_message("Error Invalid Prefix ", "error");
            return;
        }
        var data = {"call": "addPrefix", "dialPlanId": planId, "countryId": countryId, "prefix": prefix, "routeId": route}
        if (id != "" && id !== undefined && id != "planSlno")
        {
            data.id = id;
            data.call = "editPrefix";
        }
        if (route == "")
        {
            show_message("Please select route first.", "error");
            return;
        }

        $.ajax({
            url: "/controller/dialPlanController.php",
            type: "POST",
            dataType: "JSON",
            data: data,
            success: function(response) {
console.log(ths.index());
                show_message(response.msg, response.status);
                if(response.status == "success")
                {
                    /*console.log(ths.index());
                    console.log($('.selRoute').eq(ths.index()).attr('onchange').replace('planSlno',response.id));*/
            var parentTr = ths.parents('tr');
                       parentTr.find('.selRoute').attr('onchange',parentTr.find('.selRoute').attr('onchange').replace('planSlno',response.id));
                       parentTr.find('.uPrefix').attr('onblur',parentTr.find('.uPrefix').attr('onblur').replace('planSlno',response.id));
                       parentTr.find('.editPrefixBtn').attr('onclick',parentTr.find('.editPrefixBtn').attr('onclick').replace('planSlno',response.id));
                       parentTr.find('.delPrefixBtn').attr('onclick','deleteRow($(this),'+planId+','+response.id+',2)');
//                    $('.selRoute').eq(ths.index()).attr('onchange',$('.selRoute').eq(ths.index()).attr('onchange').replace('planSlno',response.id));
//                    $('.uPrefix').eq(ths.index()).attr('onblur',$('.uPrefix').eq(ths.index()).attr('onblur').replace('planSlno',response.id));
//                    $('.editPrefixBtn').eq(ths.index()).attr('onclick',$('.editPrefixBtn').eq(ths.index()).attr('onclick').replace('planSlno',response.id));
//                    $('.delPrefixBtn').eq(ths.index()).attr('onclick',);
                }
            }
        })

    }

<?php if (!isset($_REQUEST['planId'])) { ?>
        $('#dialPlanUl li:first').trigger('click');
<?php } ?>

</script>
