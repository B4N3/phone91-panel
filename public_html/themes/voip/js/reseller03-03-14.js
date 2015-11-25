/* @AUTHOR: SAMEER RATHOD 
 * @desc : reseller.js consist of all the javascript function which are realated to the reseller 
 *         seprate description of each function is included in the funciton itself
 **/

var _globalTimeOut = null;
function prepareRow(jsonData)
{
    var $class;
    var str = "";
    var i = 1;
    $.each(jsonData, function(i, field){
                    if(i == 1 || i%2 != 0 )
                        $class = "odd";
                    else
                        $class = "even";
                    /*PREPARE THE OUTPUT STRING THIS CONSIST OF THE HTML PART TO RENDER AND APPEND TO DIV*/
                    str += "<tr class='"+$class+"' id='rowId_"+field.id_tariffs_key+"'>\
                        <td><input class='checkBox' onchange='onCheck();' type='checkbox' value='"+field.id_tariffs_key+"'/></td>\
                        <td><input type='hidden' id='idTariffKey_"+field.id_tariffs_key+"' name='idTariffKey_"+field.id_tariffs_key+"' value='"+field.id_tariffs_key+"' /><input type='text' id='prefix_"+field.id_tariffs_key+"' name='prefix_"+field.id_tariffs_key+"' value='"+field.prefix+"' class='editable'/></td>\
                        <td><input type='text' id='country_"+field.id_tariffs_key+"' name='country_"+field.id_tariffs_key+"' value='"+field.description+"' class='editable' /></td>\
                        <td><input type='text' id='operator_"+field.id_tariffs_key+"' name='operator_"+field.id_tariffs_key+"' value='"+field.operator+"' class='editable' /></td>\
                        <td><input type='text' id='rate_"+field.id_tariffs_key+"' name='rate_"+field.id_tariffs_key+"' value='"+field.voiceRate+"' class='editable' /></td>\
                        <td><input type='button' title='Save' class='btn btn-mini btn-primary ' value='Save' name='saveButton' onclick='editPlan(\""+field.id_tariffs_key+"\");'/>  </td>\
                        </tr>"

                    i++;
                });
    return str;
}
var count = 1;
function prepareRowAdmin(jsonData)
{
    var $class;
    var str = "";
    var i = 1;
    $.each(jsonData, function(i, field){
                    if(i == 1 || i%2 != 0 )
                        $class = "odd";
                    else
                        $class = "even";
                    /*PREPARE THE OUTPUT STRING THIS CONSIST OF THE HTML PART TO RENDER AND APPEND TO DIV*/
                    str += "<tr class='"+$class+"' id='rowId_"+field.id_tariffs_key+"'>\
                        <td><input type='hidden'  id='idTariffKey_"+field.id_tariffs_key+"' name='idTariffKey_"+field.id_tariffs_key+"' value='"+field.id_tariffs_key+"' /><input type='text' onblur='editPlan("+field.id_tariffs_key+")' id='prefix_"+field.id_tariffs_key+"' name='prefix_"+field.id_tariffs_key+"' value='"+field.prefix+"'/></td>\
                        <td><input type='text' onblur='editPlan("+field.id_tariffs_key+")' id='country_"+field.id_tariffs_key+"' name='country_"+field.id_tariffs_key+"' value='"+field.description+"' /></td>\
                        <td><input type='text' onblur='editPlan("+field.id_tariffs_key+")' id='operator_"+field.id_tariffs_key+"' name='operator_"+field.id_tariffs_key+"' value='"+field.operator+"' /></td>\
                        <td class='noBorder'><input type='text' onblur='editPlan("+field.id_tariffs_key+")' id='rate_"+field.id_tariffs_key+"' name='rate_"+field.id_tariffs_key+"' value='"+field.voiceRate+"' /><span class='ic-24 delete cp' title='Delete' onclick='deleteAll(1,"+field.id_tariffs_key+")'></span></td>\
                        <td></td>\
                        </tr>"

                    i++;
                    count++;
                });
                
    return str;
}
function getTariffDetails(tariffId,limit,type,page)
{
    /*@author : SAMEER RATHOD
     *@desc : This function is used to fetch the tariff details from db
     **/
    /**/
    if(type == '' || type == undefined)
        type = 'reseller';
    
    var str;
    $.ajax({
        url:"/controller/managePlanController.php?call=manageTariff",
        type:"POST",
        data:{
            pid:tariffId,
            limit:limit,
            page_number:page
        },
        dataType:"JSON",
        success: function(msg)
        {
            console.log(type);
            /*GET THE VALUE OF CURRENTLY SELECTED TARIFF*/
            $('#currentSelected').val(tariffId);

            /*INITIALIZE VARIABLE*/
           var str="";
            
            if(msg.allvaluess == "Invalid Plan")
            {
                show_message(msg.allvaluess,"error");
            }
            else if(msg.allvaluess != null && msg.allvaluess != "Invalid Plans")/*CHECK IF RESPONSE IF NOT NULL*/
            {   
                /*ITERATE THROUGH THE VALUE TO GET THE DESIRED OUTPUT*/
                if(type == "admin")
                 str = prepareRowAdmin(msg.allvaluess);
                else
                 str = prepareRow(msg.allvaluess);
            }
            
             $('#tbody').html(str); 
             
            if(str == "" || count <= 10)
                $('#pagination').hide();
            else
                $('#pagination').show();
            
             planPagination(page,msg.pages,tariffId,limit,type);
        }

    }) 

}

function planPagination(start,count,tariffId,limit,type)
 {
     if(start == undefined || start == 0 || start== "")
        start=1;
    
    if(count == undefined || count == 0 || count== "")
        count = 1;
//    var type = ''; 
     $(function() {
                    $('#pagination').paginate({
                        count       : count,
                        start       : start,
                        display     : 10,
                        border : true,
                        text_color: '#000',
                        background_color: '#ddd',
                        text_hover_color: '#fff',
                        background_hover_color: '#333',
                        images                  : false,
                        mouse                   : 'press',
                        page_choice_display     : true,
                        show_first              :true,
                        show_last               :true,
                        onChange                : function(page){
                         console.log(page);                           
                         //window.location.href= window.location.href.split('?')[0]+'?&pageNo='+page;
                         getTariffDetails(tariffId,limit,type,page);
//                                   $.ajax({
//                                    url:"/controller/managePlanController.php?call=manageTariff",
//                                    type:"POST",
//                                    data:{
//                                        pid:tariffId,
//                                        limit:limit,
//                                        page_number:page
//                                    },
//                                    dataType:"JSON",
//                                    success: function(msg)
//                                    {
//                                        console.log(msg);
//                                        /*GET THE VALUE OF CURRENTLY SELECTED TARIFF*/
//                                        $('#currentSelected').val(tariffId);
//
//                                        /*INITIALIZE VARIABLE*/
//                                    var str="";
//
//                                        if(msg.allvaluess == "Invalid Plan")
//                                        {
//                                            show_message(msg.allvaluess,"error");
//                                        }
//                                        else if(msg.allvaluess != null && msg.allvaluess != "Invalid Plans")/*CHECK IF RESPONSE IF NOT NULL*/
//                                        {   
//                                            /*ITERATE THROUGH THE VALUE TO GET THE DESIRED OUTPUT*/
//                                            if(type == "admin")
//                                            str = prepareRowAdmin(msg.allvaluess);
//                                            else
//                                            str = prepareRow(msg.allvaluess);
//                                        }
//
//                                        $('#tbody').html(str); 
//                                        planPagination(page,msg.pages,tariffId,limit);
//                                    }
//
//                                })
                              }
                        });
            });                                
                        
 }

function editPlan(key)
{
    /*@AUTHOR : SAMEER RATHOD
     *@DESC : THIS FUNCTION IS CALLED WHEN A SIGLE ROW FROM THE TARIFF IS TO EDITED 
     **/
    
    /*GE5T VALUES FORM ALL THE FIELDS OF THE TABLE*/
    var prefix = $('#prefix_'+key).val();
    var country = $('#country_'+key).val();
    var rate = $('#rate_'+key).val();
    var operator = $('#operator_'+key).val();
    var pid = $('#currentSelected').val();
    
    
    $.ajax({
        url:"/controller/managePlanController.php?call=editTariff",
        type:"POST",
        dataType:"JSON",
        data:{
            id:key,
            pid:pid,
            operator:operator,
            prefix:prefix,
            country:country,
            rate:rate
            
        },
        success:function (response){
            show_message(response.msg,response.status);
        }
    })

}


function addRow()
{
    /* @AUTHOR :SAMEER 
     * @DESC : ADD THE EXTRA ROW TO THE TABLE IN MANUAL ENTRY OPTION 
     */
    var i = $('#tariff_table table tr').size();
    var row;
    row = '<tr>\
            <td><input type="text" value="" class="cntryCode" id="countryCode'+(i-1)+'" name="countryCode[]" class=""/></td>\
            <td><input type="text" value="" class="cntryName" id="countryName'+(i-1)+'" name="countryName[]" class=""/></td>\
            <td><input type="text" value="" class="operator" id="operator'+(i-1)+'" name="operator[]" class=""/></td>\
            <td><input type="text" value="" class="rate" id="rate'+(i-1)+'" name="rate[]" class=""/></td>\
        </tr>';
    $('#tariff_table table').append(row);
    /*UPDATE THE VALUE THIS IS USED TO ITERATE THROUGH ALL THE VALUED DURING INSERTION OF THE TARIFF*/
    $('#sizeOfRow').val((i));
}
    
function searchTariff(ths)
{
    /*@AUTHOR : SAMEER RATHOD 
     *@DESC: FUNCITON SEARCHED THE TARIFF BY PREFIX OR BY COUNTRY NAME 
     **/
    if(_globalTimeOut != null)
    clearTimeout(_globalTimeOut);

_globalTimeOut = setTimeout(function(){
    var keyword = ths.val();
    /*GET THE VALUE OF THE CURRENTLY SELECTED TARIFF ID */
    var tariffId = $('#currentSelected').val();
    $.ajax({
        url:'/controller/managePlanController.php',
        data:{
            call:'searchTariffDetails',
            keyword:keyword,
            tariffId:tariffId
        },
        type:"POST",
        dataType:"JSON",
        success:function(response)
        {
            var str = "";
            var $class = "";
            if(response != null)
            {
                /*ITERATE THROUGH THE RESPONSE*/
                $.each(response, function(i, field){
                    if(i == 1 || i%2 != 0 )
                        $class = "odd";
                    else
                        $class = "even";
                    /*PREPARE THE OUTPUT OF HTML*/
                    str += "<tr class='"+$class+"' id='rowId_"+field.slNo+"'>\
                            <td><input class='checkBox' onchange='onCheck();' type='checkbox' value='"+field.slNo+"'/></td>\
                            <td><input type='hidden' id='idTariffKey_"+field.slNo+"' name='idTariffKey_"+field.slNo+"' value='"+field.slNo+"' />\
							<input type='text' id='prefix_"+field.slNo+"' name='prefix_"+field.slNo+"' value='"+field.prefix+"'  class='editable'/></td>\
                            <td><input type='text' id='country_"+field.slNo+"' name='country_"+field.slNo+"' value='"+field.description+"' class='editable' /></td>\
                            <td><input type='text' id='operator_"+field.slNo+"' name='operator_"+field.slNo+"' value='"+(field.operator != 'undefined'?field.operator : "")+"' class='editable' /></td>\
                            <td><input type='text' id='rate_"+field.slNo+"' name='rate_"+field.slNo+"' value='"+field.voiceRate+"' class='editable' /></td>\
                            <td><input type='button' title='Save' class='btn btn-mini btn-primary ' value='Save' name='saveButton' onclick='editPlan(\""+field.slNo+"\");'/>  </td>\
                            </tr>"
                    i++;
                });
                $('#tbody').html(str);
            }
        }
    })
    
    },600);
}
    
function deleteAll(isAdmin,tariffRowId)
{
    /*@AUTHOR : SAMEER RATHOD
     *@DESC : FUNCTION USED TO DELETE THE TARIFF EITHER ONE BY ONE OR ALL 
     */
    /*INITIALIZE THE ID ARRAY TO STORE THE ID OF ALL THE TARIFF WHICH HAVE TO BE DELETED*/
    var idArr = [];
    var deleteAll = 0;
    /*GET THE TARIFF ID */
    var tariffId = $('#currentSelected').val();
    if(isAdmin == 1)
    {
        if(tariffRowId == undefined || tariffRowId == "")
            return false;
        idArr[0] =tariffRowId;
    }
    else{
        /*CHECK IF DELETE ALL IS CHECKED OR NOT IS YES THEN IT WILL DELETE ALL THE TARIFF OF THE CURRENT TARIFF*/
        if($('#checkAll').is(':checked'))
             deleteAll = 1;
        /*GET THE VALUE OF THE ROW WHICH IS SELECTED*/
        $('.checkBox:checked').each(function(){
            idArr.push($(this).val());
        })
    }
    $.ajax({
        url: "/controller/managePlanController.php?call=deleteTariff",
        data:{
            idArr:idArr,
            tariffId:tariffId,
            deleteAll:deleteAll
        },
        type:"POST",
        dataType:"JSON",
        success: function(response)
        {
            /*HIDE THE ENTRY WHICH IS DELETED*/
            if(deleteAll == 1)
                $('#tbody').html('');
            show_message(response.msg,response.status);
            $.each(idArr,function(key,value){
                $('#rowId_'+value).hide();
            })
            if($('#tbody tr').length <= 10)
                $('#pagination').hide();
        }
    })

}

function toggleState(ths,type)
{
    /*@AUTHOR : SAMEER RATHOD 
     *@DESC : THIS FUNCTION IS USED TO TOGGLE THE VLAUE OF THE LABEL OF INC.DEC SWITCH
     **/
    
    ths.toggleClass('freducer');
    if($('#changefunder'+type).val() == "planInc")
    {
        $('#inc'+type).html('Decrease Rate');
        $('#changefunder'+type).val("planDec");
    }
    else
    {
        $('#inc'+type).html('Increase Rate');
        $('#changefunder'+type).val("planInc");
    }
}
function toggleDiv(divToshow,divTohide)
{
   /* @AUTHOR :SAMEER 
    * @DESC : TOGGLE THE DIV FOR THREE OPTIONS IE SELECT BROWSE AND MANUAL
    */
    $('#'+divToshow).show();
    $('#'+divTohide).hide();
}

/*used for manage website*/
var  _planCurrencyVariable = {};
function selectPlan()
{
    
    
    /*@AUTHOR : SAMEER RATHOD 
     *@DESC : THIS FUNCTION IS FETCHES EXISTING PLAN NAME TO FETCH AND DISPLAY IT IN SELECT OPTION*/
    var option;
    $.ajax({
        url:"/controller/managePlanController.php?call=selectPlan",
        dataType:"JSON",
        success:function(resMsg)
        {
            
            if(resMsg != null)
            {
//                console.log(resMsg);
                option += "<option value='Select'>Select</option>";
                $.each(resMsg,function(i,item){
                    _planCurrencyVariable[item.tariffId] = Array(item.outputCurrency,item.currency) ; 
//                    _planCurrencyVariable[item.tariffId] = ; 
                    option += "<option value='"+item.tariffId+"'>"+item.planName+"</option>";
                 })
                    $('#selPlan').html(option);
					
                    $('.selPlan').each(function(){
						$(this).html(option);						
						$(this).val($(this).attr('select'));
					})
                    
            }
            
        }
        
    })
    
}


optionsAddPlan = {
        /*@AUTHOR :SAMEER RATHOD
        *@DESC : THESE ARE THE OPTION SET FOR THE AJAX FORM METHOD AND TRIGERED 
        *        FROM ADDNEW PLAN FORM AND EDIT TARIFF PLAN FORM
        **/
        url:"controller/managePlanController.php?call=addplan",
        type:"post",
        dataType:"JSON",
        beforeSubmit : validateAddPlanForm,
        success : function(response){
            var currentId = $('#currentSelected').val();
            var randomnumber=Math.floor(Math.random()*11);
            var currentPage = window.location.hash.substring(1).split("|");
            if(currentPage[1] == "reseller-add-plan.php")
                currentId = response.insertId;
            getManagePlanDetails(null,currentId);
            show_message(response.msg,response.status);
            
            if(response.status == "success")
                window.location.hash ='#!reseller-manage-plan.php|reseller-manage-plan-setting.php?tariffId='+response.insertId+'&num='+randomnumber;
        }
    }
optionsEditPlan = {
            /*@AUTHOR :SAMEER RATHOD
             *@DESC : THESE ARE THE OPTION SET FOR THE AJAX FORM METHOD AND TRIGERED FROM ADDNEW PLAN FORM AND EDIT TARIFF PLAN FORM
             **/
            url:"controller/managePlanController.php?call=addplan",
            type:"post",
            dataType:"JSON",
            beforeSubmit:validateEditPlanForm,
            success : function(response){
                var currentId = $('#currentSelected').val();
                var randomnumber=Math.floor(Math.random()*11);
                var currentPage = window.location.hash.substring(1).split("|");
                
                if(currentPage[1] == "reseller-add-plan.php")
                    currentId = response.insertId;
                getManagePlanDetails(null,currentId);
                show_message(response.msg,response.status);
//                p91Loader('stop');
                if(response.status == "success")
                    window.location.hash ='#!reseller-manage-plan.php|reseller-manage-plan-setting.php?tariffId='+response.insertId+'&num='+randomnumber;
            }
        }
 function textOnlyValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z ]+/.test(value))
         return false;
     else
         return true;
}
 function planNameValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for textonly validation as it is not present in the valdate.js
     **/
     if(/[^a-zA-Z0-9\@\_\-\s]+/.test(value))
         return false;
     else
         return true;
}
function myNumberOnlyValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for numbers validation
     **/
    if($(element).closest('.incCheck').is(':checked'))
    {
        if(value.length < 1 || value.length > 500)
            return false;
        else{
            if(/[^0-9]+/.test(value))
                return false;
            else
                return true;
        }
            
            
    }
    else
        return true;
}
function checkNumber(value,element)
{
    if(/[^0-9]+/.test(value))
    {
        return false;
    }
    else
        return true;
}
function mySelectValidation(value, element){
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : custom validation function for select option required field validator
     **/
    if(($('#select').is(':checked') && $('#select').val() == 2) || $('#importInput').val() == 2)
    {
        if(value == "Select" || value == "")
            return false;
        else
            return true;
    }
    else
        return true;
}
$.validator.addMethod("textOnly", textOnlyValidation, "Please enter only alpha characters( a-z ).");
$.validator.addMethod("planName", planNameValidation, "Please enter provide valid data characters( a-zA-Z0-9\@\_\-\s ).");
$.validator.addMethod("numberOnly", myNumberOnlyValidation, "Please enter only numbers only characters( 1-500 ).");
$.validator.addMethod("select", mySelectValidation, "Please select a plan");
$.validator.addMethod("checkNumber", checkNumber, "Please select billing seconds in number format only");
function validateAddPlanForm()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the all plan form before submitting for javascript validation
     **/
    $('#addPlanForm').validate({
        rules: {
            planName :{
                planName:true,
                required: true,
                minlength: 6,
                maxlength: 18

                        },
            billingSec:{
                checkNumber:true,
                required:true,
                min:1,
                max:600
            },
            plantype :{
                select: true
                    } 
            }
        })
    if($("#addPlanForm").valid())
                return true; 
        else
        {
            //p91Loader('stop');
            return false;
        }
}

function validateEditPlanForm()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the edit plan form before submitting for javascript validation
     **/
    $('#appendForm').validate({
        rules: {
                plantype :{
                    select: true
                        } 
                }
    })
    if($("#appendForm").valid())
            return true; 
        else        
            return false;
        
}
function validateAdminAddPlanForm()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the edit plan form before submitting for javascript validation
     **/
    $('#addPlanAdmin').validate({
        rules: {
                plantype :{
                    select: true
                        } 
                }
    })
    if($("#addPlanAdmin").valid())
            return true; 
        else        
            return false;
        
}
function validateInsertRows()
{
    /* @AUTHOR: SAMEER RATHOD 
     * @desc : function the insert rows in manul insert option before submitting for javascritp validation
     **/
    if($('#manual').is(':checked') || $('#importInput').val() == 3)
    {
        var flag;
        $('#tariff_table tr').each(function(i){
console.log(i);
            if(i > 0)
            {
            
            var value0 = $(this).find('input').eq(0).val(); 
            var value1 = $(this).find('input').eq(1).val(); 
            var value2 = $(this).find('input').eq(2).val(); 
            var value3 = $(this).find('input').eq(3).val(); 
            
            if((/[^0-9]+/.test(value0)) || value0 == "")
                {
                    console.log(value0);
                    flag = 1;
                    $(this).find('input').eq(0).addClass("error");
                    return false;
                }

            if((/[^a-zA-Z]+/.test(value1)) || value1 == "")
                {
                    console.log(value1);
                    flag = 1;
                    $(this).find('input').eq(1).addClass("error");
                    return false;
                }
            if((/[^a-zA-Z]+/.test(value2)) || value2 == "")
                {
                    console.log(value2);
                    flag = 1;
                    $(this).find('input').eq(2).addClass("error");
                    return false;
                }
            if((/[^0-9\.]+/.test(value3)) || value3 == "")
                {
                    console.log(value3);
                    flag = 1;
                    $(this).find('input').eq(3).addClass("error");
                    return false;
                }
            }
           
        })
        if(!flag)
            return true;
        else
        {
            show_message("Invalid entry in the field","error");
            return false;
        }
    }
    else
        return true;
    
    
}
function validateAdminEditPlanForm()
{
    var id = $('#idName').val();
    $('#'+id).validate({
        rules: {
            planName :{
                planName:true,
                required: true,
                minlength: 6,
                maxlength: 18

                        },
            billingSec:{
                number:true,
                required:true,
                min:1,
                max:600
            },
            outputCurr:{
                number:true,
                required:true
            }
            
        }
    })
    if($('#'+id).valid())
            return true; 
        else        
            return false;
}
