<?php $data=array (
  'domainId' => 'bWFuYWdld2ViMS5waG9uZTkxLmNvbQ==',
  'welcomeImage' => 'cHJpY2luZ0ltZy5qcGc=',
  'pricingMeta' => 
  array (
    'title' => 'cHJpY2luZw==',
    'mKeyword' => 'amZrZCBqZmtkamYgZGtmaiA=',
    'mDescription' => 'dm1jLG12LGNtdmNx',
  ),
  'pricingbannerDetail' => 
  array (
    'heading' => 'TG93ZXN0IHByb2NpbmcgZXZlcg==',
    'subHeading' => 'Z2V0IGl0IHNvb24=',
    'text' => 'Z28gbm93',
    'link' => 'aHR0cDovL21hbmFnZXdlYjEucGhvbmU5MS5jb20=',
  ),
  'tariffPlan' => '',
  'bankDetail' => 
  array (
    0 => 
    array (
      'slNo' => 'MA==',
      'BankName' => 'dHIgdHJ0ciB0',
      'ifsc' => 'NjU2NTY0',
      'accountNo' => 'NDM0Njc1NjM0NTY1NjQ1Njc=',
      'accountName' => 'Z2JoZ2ZoIGdoIG5naA==',
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