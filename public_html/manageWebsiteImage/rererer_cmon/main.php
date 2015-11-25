<?php $data=array (
  'companyName' => 'Z2ZkZ2Zn',
  'domainName' => 'cmVyZXJlci5jbW9u',
  'resellerId' => 'MzIyNTc=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'ZGtmZGprQGdtYWlsLmNvbQ==',
  'id' => 'MTg0',
  'theme' => 'dGVtcGxhdGUx',
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