<?php
/* @author : SAMEER
 * @created : 10-09-2013
 * @description : verify the email of the user 
 */
include 'config.php';
if (isset($_REQUEST['confirmationCode']) && isset($_REQUEST['email'])) {
    if (strlen($_REQUEST['confirmationCode']) > 0) {
        #decode the confirmation code
        $confirmCode = base64_decode(trim($_REQUEST['confirmationCode']));
        #get the email id of the user
        $email = trim($_REQUEST['email']);
        #initialize the confirm flag set to 0 for not confirmed 1 for confirmed and 2 for already confirmed
        $confirm_flag = 0;
        //This query check for contact information in contact and temp - contact
        $tempresult = $funobj->db->query("select * from 91_tempEmails where confirm_code='" . $confirmCode . "' AND email='" . $email."'") ;
        
        if ($tempresult) {
            if ($tempresult->num_rows > 0) { //if their is entry into temp table or contact table
                #then check if there exist any entry in the verified table 
                $result = $funobj->db->query("select * from 91_verifiedEmails where email='" . $email . "'");
                if ($result->num_rows > 0) { //If information exists
                    #already exist
                    $confirm_flag = 2;
                } else {
                    #get the information if mobile number is unconfirmed
                    $resInfo = $tempresult->fetch_array(MYSQLI_ASSOC);
                    #get the userid 
                    $userId = $resInfo['userid'];
                    #get the serial number of the id 
                    $tempPid = $resInfo['tempEmail_id'];
                    #set the confirm flag to confirmed
                    $confirm_flag = 1;
                }
            } else {
                
                $result = $funobj->db->query("select * from 91_verifiedEmails where email='" . $email . "'");
                if ($result->num_rows > 0) { //If information exists
                    $confirm_flag = 2;
                }
                else
                    $confirm_flag = 0;
            }

            if ($confirm_flag == 1) {
                #insert the email di from temp to verified table 
                $insertSql = "INSERT INTO 91_verifiedEmails (userid, email,confirm_code) VALUES ('" . $userId . "','" . $email . "','" . $confirmCode . "')";
                $insQueryRes = $funobj->db->query($insertSql);
                if ($insQueryRes) {
                    #delete the entry form the temp email table 
                    $deleteQuery = "DELETE FROM 91_tempEmails where tempEmail_id=" . $tempPid;
                    $delRes = $funobj->db->query($deleteQuery);
                    if (!$delRes)
                        mail("sameer@hostnsoft.com", "Phone91 " . __FILE__ . " verify email delete query fail", "query " . $deleteQuery . " Error " . $funobj->db->error);
                }
                else
                    mail("sameer@hostnsoft.com", "Phone91 " . __FILE__ . " verify email query fail", "query " . $tempsql . " Error " . $temp_email_error);
            }
        }
?>
        <?php

        switch ($confirm_flag) {

            case 0:

                echo 'Sorry Code is not matched.';
                break;
            case 1:

                echo 'Successfully Confirmed';
                break;
            case 2:

                echo 'This Number is already confirmed by you.';
                break;
        }
    }//end of if code is matched
    else { //if code is not match
        echo 'Sorry Invalid Code ';
    }
}
else 
    echo "Invalid Link please try to resed the link";
        ?>
<?php

// mysql_close($con); ?>