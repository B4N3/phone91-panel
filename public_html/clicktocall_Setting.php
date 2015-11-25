<?php
include_once('config.php');
include_once(CLASS_DIR.'clickToCall_plugin_class.php');
$ctcObj = new clickToCall_plugin_class();
$dpts=$ctcObj->getDeptNumberList($_REQUEST,$_SESSION);

$depts = json_decode($dpts,TRUE);

?>
<a href="javascript:dynamicPageName('Settings');" class="back btn btn-medium btn-primary hidden-desktop backPhone" title="Back">Back</a>
<div id="cToCBox">
	<div id="dptList" class="setContainer">
		<h2 class="h2 fwN">Add Department <span class="f12">Click to Call</span></h2>    
		<form id="addDepartment" action="javascript:void(0);" class="mrT2">
			<div class="oh">
				<div class="fl mrR"><input type="text" name="deptName" id="deptName"  value="" placeholder="Enter department name" /></div>
				<input class="btn btn-medium btn-primary"  type="submit" name="save" id="save" value="+ Add" />
			</div>        
		</form>
		
		<ul id="departmentList">
                    <?php  foreach($depts['depts'] as $deptId => $detail ) 
                        { 						
						?>
						
			<li id="dptLi<?php echo $deptId ?>" onclick="getDptNumbers(<?php echo $deptId ?>,'<?php echo $detail['name']; ?>')">
				<div class="dName"><?php echo $detail['name']; ?></div>
				<div class="count"><?php echo count($detail['number']); ?></div>
			</li>
                        <?php } ?>
<!--			<li>
				<div class="dName">Sales</div>
				<div class="count">3</div>
			</li>
			<li>
				<div class="dName">Marketing</div>
				<div class="count">1</div>
			</li>-->
			
		</ul>
	</div>   
	
	<div id="dContactList" class="setContainer">
		<a href="javascript:void(0)" onclick="backToDpt();" class="back btn btn-medium btn-inverse fl" title="Back">Back</a>
		<h2 class="h2 fwN fl dptHd"><span id="dptLabel">...</span></h2>
		<form id="editDepartment" action="javascript:void(0);" class="dn">
			<div class="oh">
				<div class="fl mrR"><input type="text" name="deptName" id="updateDeptName"  value="" placeholder="Enter department name" /></div>
				<input class="btn btn-medium btn-primary"  type="submit" name="save" id="save" value="Update" />
				<input class="btn btn-medium btn-inverse"  type="button" value="Cancel" onclick="cancelUpdate();" />
			</div>        
		</form>
		<i class="ic-24 edit cp" id="editDpt" onclick="createDptInput()"></i>
		<span id="delDpt" class="ic-24 actdelC cp fl" title="Delete"></span>
		<div class="cl"></div>
		<form id="addNumber" action="javascript:void(0);" class="mrT2">
			<div class="oh">
				<input id="dptHiddenId" type="hidden" value="" />
				<div class="fl mrR"><input type="text" name="number" id="number"  value="" placeholder="Search number" class="fl mrR" /></div>
				<input class="btn btn-medium btn-primary"  type="submit" name="save" id="save" value="+ Add number" />
			</div>        
		</form>
		
		<ul id="numberList">			
		</ul>
	</div>   
</div>
<div id="delDptDialog" title="Delete department and contacts" style="display:none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure?</p>
</div>
<script type="text/javascript">

jQuery.validator.addMethod("phonenumber", function(inputtxt){
    var reg = /^\(?\+?[\d\(\-\s\)]+$/;
  	return reg.test(inputtxt);	
}, "Not a valid mobile number");

var dpts = jQuery.parseJSON('<?php echo $dpts?>');

$('#deptName').quicksearch('#departmentList li');

function createDptInput(){
	$('#dptLabel, #editDpt, #delDpt, #clicktocallHd').hide();
	$('#editDepartment').show();
}

function cancelUpdate(){
	$('#dptLabel, #editDpt, #delDpt, #clicktocallHd').show();
	$('#editDepartment').hide();
}

$("#addDepartment").validate({
	rules: {
		deptName: {
			required: true,
			minlength: 3,
			maxlength: 25
		}
	},
	submitHandler: function() {
		//$('#addDepartment').submit(function(){
			var deptName = $('#deptName').val();
			if(deptName != '')
			{
				$.ajax({	 
					url: "action_layer.php?action=addUpdateDepartment&deptName="+deptName,
					dataType: "json",
					success:function(data){
						if(data.status == 1)
						{
							var li = '<li id="dptLi'+data.lastDeptId+'" onclick="getDptNumbers('+data.lastDeptId+',\''+deptName+'\')">\
								<div class="dName">'+deptName+'</div>\
								<div class="count">0</div>\
							</li>';
							if(dpts.depts == null)
								dpts.depts = new Object();
							dpts.depts[data.lastDeptId] = new Object();
							dpts.depts[data.lastDeptId].name = deptName;
							dpts.depts[data.lastDeptId].number = [];
							$('#departmentList').prepend(li);
						}
						else
						{
							if($('#deptName').next('.error').length == 1)							
								$('#deptName').next('.error').show().html(data.msg);
							else	
								$('#deptName').after('<label for="deptName" class="error">'+data.msg+'</label>');
						}
					}
				})
			}	
		//})			
	}
});

$("#editDepartment").validate({
	rules: {
		deptName: {
			required: true,
			minlength: 3,
			maxlength: 25
		}
	},
	submitHandler: function() {
		//$('#addDepartment').submit(function(){
			var deptName = $('#updateDeptName').val();
			var dptId = $('#dptHiddenId').val();
			if(deptName != '')
			{
				$.ajax({	 
					url: "action_layer.php?action=addUpdateDepartment&deptName="+deptName+"&deptId="+dptId+"&type=1",
					dataType: "json",
					success:function(data){
						$('#dptLi'+dptId+' .dName, #dptLabel').html(deptName);
						$('#dptLi'+dptId).attr('onclick','getDptNumbers('+dptId+',\''+deptName+'\')');
						$('#dptLabel, #editDpt, #delDpt, #clicktocallHd').show();
						$('#editDepartment').hide();
						dpts.depts[dptId].name = deptName;
					}
				})
			}	
		//})			
	}
});

function updateDptName(){
	console.log()
}

function backToDpt(){
	$('#dptList').show();$('#dContactList').hide();
}

$("#addNumber").validate({
	rules: {
		number: {
			required: true,
			minlength: 3,
			maxlength: 20,
			phonenumber:true
		}
	},
	submitHandler: function(){
		var number = $('#number').val();
		var encodedNumber = encodeURIComponent(number);
		var id = $('#dptHiddenId').val();		
		$.ajax({	 
			url: "action_layer.php?action=addNumberToDept&deptId="+id+"&number="+encodedNumber,
			dataType: "json",
			success:function(data){
				if(data.status == 1)
				{
					var li = '<li id="numLi'+data.lastNumId+'">\
						<div class="dNumber">'+number+'</div>\
						<div class="action">\
							<span class="ic-24 actdelC cp" onclick="delNumberFromDpt('+id+','+data.lastNumId+')" title="Delete"></span>\
						</div>\
					</li>';		
					$('#numberList').prepend(li);			
					var l = dpts.depts[id].number.length;
					dpts.depts[id].number[l] = new Object();
					dpts.depts[id].number[l].id = data.lastNumId;
					dpts.depts[id].number[l].no = number;
					$('.count','#dptLi'+id).html(dpts.depts[id].number.length);
				}
				else
				{
					if($('#number').next('.error').length == 1)							
						$('#number').next('.error').show().html(data.msg);
					else	
						$('#number').after('<label for="number" class="error">'+data.msg+'</label>');	
				}
			}
		})		
	}
});

function delNumberFromDpt(deptId,numId){
	$.ajax({	 
		url: "action_layer.php?action=deleteNumberFromDept&deptId="+deptId+"&numId="+numId,
		dataType: "json",
		success:function(data){
			$('#numLi'+numId).remove();
			var object = dpts.depts[deptId].number;			
			dpts.depts[deptId].number = $.grep(object,function(x) { return x.id != numId});
			$('.count','#dptLi'+deptId).html(dpts.depts[deptId].number.length);
		}
	})
}

function getDptNumbers(id,dpt){
	$('#dptLabel').html(dpt);
	$('#updateDeptName').val(dpt);	
	$('#dptList').hide();
	$('#dContactList').show();
	$('#delDpt').attr('onclick','delDpt('+id+',\''+dpt+'\')')
	$('#dptHiddenId').val(id);
	//$('#addNumber').attr('onsubmit','addNumberToDpt('+id+')');
	
	var numbers = dpts.depts[id].number;
	var li='';
	$.each(numbers, function( index, value ) {
		li += '<li id="numLi'+value.id+'">\
			<div class="dNumber">'+value.no+'</div>\
			<div class="action">\
				<span class="ic-24 actdelC cp" onclick="delNumberFromDpt('+id+','+value.id+')" title="Delete"></span>\
			</div>\
		</li>';
	});	
	
	$('#numberList').html(li);
}

function delDpt(id,dpt){	
	$( "#delDptDialog" ).dialog({		
		modal: true,
		buttons: {
			"Delete": function() {
				$.ajax({	 
					url: "action_layer.php?action=deleteDept&deptId="+id,
					dataType: "json",
					success:function(data){
						$('#dptLi'+id).remove();
						$('#dptList').show();
						$('#dContactList').hide();
						$("#delDptDialog").dialog( "close" );						
						delete dpts.depts[id]
					}
				})		 	
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});	
}
</script>