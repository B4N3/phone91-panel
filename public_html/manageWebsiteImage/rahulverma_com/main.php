<?php $data=array (
  'companyName' => 'cmFodWx2ZXJtYQ==',
  'domainName' => 'cmFodWx2ZXJtYS5jb20=',
  'resellerId' => 'MzQxMDk=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'cmFodWx2ZXJtYUBqZGZoLmNvbQ==',
  'id' => 'MTg4',
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