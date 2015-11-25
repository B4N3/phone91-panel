<?php $data=array (
  'logoImage' => 'bG9nby5qcGc=',
  'socialLinks' => 
  array (
    'facebook' => '',
    'twitter' => '',
    'linkedin' => '',
    'gplus' => '',
  ),
  'contact' => 
  array (
    'address' => 'R2xvYmFsIFJlYWNoIEV4cGVydHMgUHZ0THRkIDFzdCBGbG9vciAybmQgTWFpbiBSb2FkIFNlc2hhZHJpcHVyYW0gIEJhbmdsb3JlICA1NjAgMDIw',
    'phoneNo' => 'OTYzMjI2Njk4OA==',
    'email' => 'ZWNhcmVzb2x1dGlvbnNAaW4uY29t',
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