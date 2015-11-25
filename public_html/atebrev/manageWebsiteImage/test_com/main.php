<?php $data=array (
  'companyName' => 'dGVzdA==',
  'domainName' => 'dGVzdC5jb20=',
  'resellerId' => 'MzkwMTA=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'dGVzdEBtYWlsaW5hdG9yLmNvbQ==',
  'id' => 'MTk0',
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