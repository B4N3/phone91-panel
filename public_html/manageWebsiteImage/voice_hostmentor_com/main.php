<?php $data=array (
  'companyName' => 'SG9zdG1lbnRvcg==',
  'domainName' => 'dm9pY2UuaG9zdG1lbnRvci5jb20=',
  'resellerId' => 'Mzg4MTM=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'YW1hcndpbnNvZnRAZ21haWwuY29t',
  'id' => 'MTkz',
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