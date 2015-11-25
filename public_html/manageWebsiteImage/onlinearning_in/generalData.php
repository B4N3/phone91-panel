<?php $data=array (
  'logoImage' => '',
  'socialLinks' => 
  array (
    'facebook' => '',
    'twitter' => '',
    'linkedin' => '',
    'gplus' => '',
  ),
  'contact' => 
  array (
    'address' => 'QWhtZWRhYmFk',
    'phoneNo' => 'OTgyNTY1OTc3MQ==',
    'email' => 'Y29udGFjdHVzQG9ubGluZWFybmluZy5pbg==',
  ),
) ; foreach($data as $key => $val)
                                    {
                                        if(is_array($val))
                                        {
                                            foreach($val as $ikey => $val2)
                                            {
                                                ${$key."_".$ikey} = htmlspecialchars(base64_decode($val2));
                                            }
                                        }
                                        else
                                            ${$key} = htmlspecialchars(base64_decode($val));
                                    }  ?>