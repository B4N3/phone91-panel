<?php $data=array (
  'domainId' => 'd3d3LnJveWFsaW5mby5pbg==',
  'contactMeta' => 
  array (
    'title' => 'Ojo6Ojo6OiBST1lBTCBWT0lDRSA6Ojo6Ojo6',
    'mKeyword' => '',
    'mDescription' => '',
  ),
  'cntbannerStatus' => 'MA==',
  'contactbannerDetail' => 
  array (
    'heading' => '',
    'subHeading' => '',
    'text' => '',
    'link' => '',
  ),
  'contactFormStatus' => 'MA==',
  'contactFormEmail' => 'Y2hheWFuXzYwOUB5YWhvby5jb20=',
  'mapLocationStatus' => 'MA==',
  'gMapEmbededCode' => '',
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