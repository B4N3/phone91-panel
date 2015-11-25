<?php $data=array (
  'logoImage' => 'bG9nby5qcGc=',
  'socialLinks' => 
  array (
    'facebook' => 'aHR0cHM6Ly93d3cuZmFjZWJvb2suY29tL3JveWFsdm9pY2Vjbw==',
    'twitter' => '',
    'linkedin' => '',
    'gplus' => '',
  ),
  'contact' => 
  array (
    'address' => 'Rm9yIFRlY2huaWNhbCBTdXBwb3J0IENhbGwtIDAwOTEgNzQwNzk5MjA3OA==',
    'phoneNo' => '',
    'email' => 'YWRtaW5Acm95YWxpbmZvLmlu',
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