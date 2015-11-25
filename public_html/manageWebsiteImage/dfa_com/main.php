<?php $data=array (
  'companyName' => '4KSm4KSr4KS44KSm4KWN4KSrIA==',
  'domainName' => 'ZGZhLmNvbQ==',
  'compEmail' => 'ZGZhQGZkc2EuY29t',
  'language' => 'RW5nbGlzaA==',
  'resellerId' => 'MzExOTg=',
  'id' => 'MTk1',
  'theme' => '',
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