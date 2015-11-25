function delete_voiceroute(id){if(confirm("Are You Sure Want To Delete This Route")==true)$.ajax({type:"POST",url:"../action_layervoice.php?action=105",data:"id="+id,success:function(msg){if(msg=="Route Deleted Successfully"){show_message(msg,"success");$("#tr_"+id).hide()}else show_message(msg,"error")}})}

