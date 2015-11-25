<?php $data=array (
  'companyName' => 'bWFuYWdld2ViIOCkpuClgeCkqOCkv+CkhiA=',
  'domainName' => 'bWFuYWdld2ViMS5waG9uZTkxLmNvbQ==',
  'compEmail' => 'YW5ra3Vib3NzdGVzdEBnbWFpbC5jb20=',
  'language' => 'RW5nbGlzaA==',
  'resellerId' => 'MzM4MjQ=',
  'id' => 'MTgy',
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