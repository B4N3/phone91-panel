<?php $data=array (
  'domainId' => 'bWFuYWdld2ViMi5waG9uZTkxLmNvbQ==',
  'welcomeImage' => 'cHJpY2luZ0ltZy5qcGc=',
  'pricingMeta' => 
  array (
    'title' => 'cHJpY2luZyBwYWdl',
    'mKeyword' => 'amZka2pmIGllanIgZWZq',
    'mDescription' => 'amtmZGpma2RqZms=',
  ),
  'pricingbannerDetail' => 
  array (
    'heading' => 'ZmRmZGY=',
    'subHeading' => 'eXRncnRydA==',
    'text' => 'eXR5dA==',
    'link' => 'aHR0cDovL21hbmFnZXdlYjIucGhvbmU5MS5jb20=',
  ),
  'tariffPlan' => '',
  'bankDetail' => 
  array (
    0 => 
    array (
      'slNo' => 'MA==',
      'BankName' => 'ZmVyZmRmZGY=',
      'ifsc' => 'NDM0MzQzNA==',
      'accountNo' => 'NDM1NDU0NjQ1NDM1NDU0',
      'accountName' => 'ZmdmZ2Zn',
    ),
  ),
) ;  foreach($data as $key => $val)
                                        {
                                            if(is_array($val))
                                            {
                                                foreach($val as $ikey => $val2)
                                                {
                                                    if(is_array($val2))
                                                    {
                                                        $tmpAr = array();
                                                        foreach($val2 as $ikey2 => $val3)
                                                        {
                                                            $tmpAr[$ikey2] = htmlspecialchars(base64_decode($val3));
                                                        }
                                                        $detailAr[] = $tmpAr;
                                                    }
                                                    else
                                                        ${$key."_".$ikey} = htmlspecialchars(base64_decode($val2));
                                                }
                                            }
                                            else
                                                ${$key} = htmlspecialchars(base64_decode($val));
                                        }  ?>