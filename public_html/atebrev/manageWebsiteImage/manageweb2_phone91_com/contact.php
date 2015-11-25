<?php $data=array (
  'domainId' => 'bWFuYWdld2ViMi5waG9uZTkxLmNvbQ==',
  'contactMeta' => 
  array (
    'title' => 'Y29udGFjdCBwYWdl',
    'mKeyword' => 'ZGVyZWdkZw==',
    'mDescription' => 'Z2ZnZmdmZw==',
  ),
  'cntbannerStatus' => 'MA==',
  'contactbannerDetail' => 
  array (
    'heading' => 'Z2ZnZmdm',
    'subHeading' => 'Z2ZnZmdm',
    'text' => 'ZGZnZGZnZGY=',
    'link' => 'aHR0cDovL21hbmFnZXdlYjIucGhvbmU5MS5jb20=',
  ),
  'contactFormStatus' => 'MA==',
  'contactFormEmail' => 'YW5raXRAZ21haWwuY29t',
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