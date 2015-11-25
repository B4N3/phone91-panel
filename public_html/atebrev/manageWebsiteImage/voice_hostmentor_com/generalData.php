<?php $data=array (
  'logoImage' => 'bG9nby5naWY=',
  'socialLinks' => 
  array (
    'facebook' => '',
    'twitter' => '',
    'linkedin' => '',
    'gplus' => '',
  ),
  'contact' => 
  array (
    'address' => 'MiAxNjIgTWFyaSBhbW1hbiBrb3ZpbCBzdHJlZXQ=',
    'phoneNo' => 'OTE5ODY1NzI4MzIx',
    'email' => 'YW1hcndpbnNvZnRAZ21haWwuY29t',
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