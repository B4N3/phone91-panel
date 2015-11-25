<?php $data=array (
  'companyName' => 'TXlJbmV0IExpbmsgTWFya2V0aW5n',
  'domainName' => 'bXlwaG9uZS5teWluZXRsaW5rLmNvbQ==',
  'resellerId' => 'Mzg3Mzk=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'aW5mby5teWluZXRsaW5rQGdtYWlsLmNvbQ==',
  'id' => 'MTg2',
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