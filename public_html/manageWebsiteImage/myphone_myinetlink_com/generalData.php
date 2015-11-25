<?php $data=array (
  'logoImage' => 'bG9nby5wbmc=',
  'socialLinks' => 
  array (
    'facebook' => '',
    'twitter' => '',
    'linkedin' => '',
    'gplus' => '',
  ),
  'contact' => 
  array (
    'address' => '',
    'phoneNo' => '',
    'email' => 'aW5mby5teWluZXRsaW5rQGdtYWlsLmNvbQ==',
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