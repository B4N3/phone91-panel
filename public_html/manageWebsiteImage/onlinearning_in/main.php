<?php $data=array (
  'companyName' => 'QVNSIENvbW11bmljYXRpb25z',
  'domainName' => 'b25saW5lYXJuaW5nLmlu',
  'resellerId' => 'Mzg3ODI=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'Y29udGFjdHVzQG9ubGluZWFybmluZy5pbg==',
  'id' => 'MTkx',
  'theme' => 'dGVtcGxhdGUy',
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