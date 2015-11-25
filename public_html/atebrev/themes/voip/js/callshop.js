/* @author : sameer rathod
 * @desc : file consist of all the javascript functions included on callshop
 */
function alphaNumValidate(value,element)
{
    console.log("alpha"+/[^a-zA-Z0-9]+/.test(value))
    console.log("value"+value)
    if(/[^a-zA-Z0-9]+/.test(value))
        return false;
    else
        return true;
}
function numberRangeValidate(value,element)
{
    if(value < 1 || value > 100000)
        return false;
    else
        return true;
}
function validateSelect(value,element)
{
//    console.log(value);
    if(value == "Select" || value == "")
        return false
    else
        return true;
}
function validateSipUser(value,element)
{
    if($('#sip').is(':checked') && $('#sip').val() == '0')
    {
        if(value == "" || '/[^a-zA-Z0-9]/'.test(value))
            return false
        else
            return true
    }
}
function validateSipPassword(value,element)
{
    if($('#sip').is(':checked') && $('#sip').val() == '0')
    {
        if(value == "" || '/[^a-zA-Z0-9\.\_\-\@\$\!\{\}\)\(]/'.test(value))
            return false
        else
            return true
    }
}
function validatemessengerId(value,element)
{
    if($('#sip').is(':checked') && $('#sip').val() == '1')
    {
        if(value == "" || '/[^a-zA-Z0-9\.\_\-\@]/'.test(value))
            return false
        else
            return true
    }
}
$.validator.addMethod("aplhanumeric", alphaNumValidate, "Please enter only alphaNumeric characters( a-z,A-Z,0-9 ).");
$.validator.addMethod("numberRange", numberRangeValidate, "Please enter number between 1 & 100000");
$.validator.addMethod("validateSelect", validateSelect, "Please select a plan");
$.validator.addMethod("validateSipUser", validateSipUser, "please select a proper user name mut me alphanumeri");
$.validator.addMethod("validateSipPassword", validateSipPassword, "please select a proper password must not contain character other then alphanumeric or !@${}-_");
$.validator.addMethod("validatemessengerId", validatemessengerId, "please select a valid messenger Id");
function validateCaLLShopForm(){
    // validate the comment form when it is submitted	
    $("#add-Cshop").validate({
        rules: {
            userName :{
                required: true,
                minlength: 8,
                maxlength: 18,
                aplhanumeric:true
                                
            },
            tariffId :{
                required: true,
                validateSelect:true
            },
            balance :{
                required: true,
                number:true,
                numberRange:true,
                minlength: 1,
                maxlength: 18
            }   
                                                         
                        
        }
    })
          
    if($("#add-Cshop").valid())
        return true; 
    else
        return false;
}
function validateSystemEditForm(){
    // validate the comment form when it is submitted	
    $("#addSystemForm").validate({
        rules: {
            systemName :{
                required: true,
                minlength: 8,
                maxlength: 18,
                aplhanumeric:true
                                
            },
            
            userName :{
                validateSipUser: true,
                minlength: 8,
                maxlength: 18
            },
            password :{
                validateSipPassword: true,
                minlength: 8,
                maxlength: 18
            },
            messengerId :{
                validatemessengerId: true
            }           
        }
    })
          
    if($("#addSystemForm").valid())
        return true; 
    else
        return false;
}

function validateEditCallShopFrom(formData, jqForm, options){

        $("#editCallshop").validate({
                rules: {
                        name :{
                            required: true,
                            maxlength: 40,
                            aplhanumeric:true
                        },
                        selPlan :{
                            required: true,
                            validateSelect:true
                        }
                        
                       }
        })
        


            if($("#editCallshop").valid())
                    return true; 
            else
                    return false;
}
