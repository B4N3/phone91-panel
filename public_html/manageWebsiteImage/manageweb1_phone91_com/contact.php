<?php $data=array (
  'domainId' => 'bWFuYWdld2ViMS5waG9uZTkxLmNvbQ==',
  'contactMeta' => 
  array (
    'title' => 'Y29udGFjdCB1cw==',
    'mKeyword' => 'cmlldXJpZXVyaWU=',
    'mDescription' => 'ZmRmamRqZmRq',
  ),
  'cntbannerStatus' => 'MA==',
  'contactbannerDetail' => 
  array (
    'heading' => 'Y29udGFjdCBwYWdl',
    'subHeading' => 'Z2V0IGZyaWVuZGx5IHN1cHBvcnQ=',
    'text' => 'Z2V0IGl0IG5vdw==',
    'link' => 'aHR0cDovL21hbmFnZXdlYjEucGhvbmU5MS5jb20=',
  ),
  'contactFormStatus' => 'MA==',
  'contactFormEmail' => 'YW5ra3Vib3NzdGVzdEBnbWFpbC5jb20=',
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