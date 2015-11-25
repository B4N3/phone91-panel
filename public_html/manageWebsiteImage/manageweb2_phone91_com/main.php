<?php $data=array (
  'companyName' => 'bWFuYWdlIHdlYiB0d28=',
  'domainName' => 'bWFuYWdld2ViMi5waG9uZTkxLmNvbQ==',
  'compEmail' => 'bWFuYWdld2ViMkBnbWFpbC5jb20=',
  'language' => 'RW5nbGlzaA==',
  'resellerId' => 'MzIyNTc=',
  'id' => 'MTgz',
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