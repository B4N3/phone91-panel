<?php


 


if(isset($_POST['template']))
{
  $email=$_REQUEST['mail'];
//  echo $email;  
  $subject=$_REQUEST['subject'];
  $message = $_POST['template'];

// In case any of our lines are larger than 70 characters, we should use wordwrap()
$message = wordwrap($message, 70, "\r\n");
mail($email, $subject, $message);

    
}

?>

<body>
   
    
    <form action="" method="post">
     <label class="lbl">Text Mail</label>
                                    <div class="thefield"> 
                                        <textarea id="template" name="template" rows='6' cols='70'></textarea>
                                    </div>
        
        
        <input type="submit" value="Send Mail" />
   
</form>
    
    </body>
    
    
    <script>
function closeWin()
{
newwindow.close();
}
</script>
    