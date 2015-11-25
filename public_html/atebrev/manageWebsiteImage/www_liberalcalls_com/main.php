<?php $data=array (
  'companyName' => 'TGliZXJhbCBDYWxscw==',
  'domainName' => 'd3d3LmxpYmVyYWxjYWxscy5jb20=',
  'resellerId' => 'MzUwNTg=',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'ZWNhcmVzb2x1dGlvbnNAaW4uY29t',
  'id' => 'MTkw',
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