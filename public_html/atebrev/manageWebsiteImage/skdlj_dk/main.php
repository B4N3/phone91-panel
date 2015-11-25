<?php $data=array (
  'companyName' => 'c2Q7bGtmag==',
  'domainName' => 'c2tkbGouZGs=',
  'resellerId' => 'MzExMjY=',
  'language' => 'RnJlbmNo',
  'compEmail' => 'ZGtzamZzZEBsZC5ka2Q=',
  'id' => 'MTg3',
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