<?php $data=array (
  'domainId' => 'd3d3LnJveWFsaW5mby5pbg==',
  'welcomeImage' => 'cHJpY2luZ0ltZy5qcGc=',
  'pricingMeta' => 
  array (
    'title' => '',
    'mKeyword' => '',
    'mDescription' => '',
  ),
  'pricingbannerDetail' => 
  array (
    'heading' => '',
    'subHeading' => '',
    'text' => '',
    'link' => '',
  ),
  'tariffPlan' => '',
  'bankDetail' => 
  array (
    0 => 
    array (
      'slNo' => 'MA==',
      'BankName' => 'QVhJUyBCQU5L',
      'ifsc' => 'QVhJUzAwMDY1',
      'accountNo' => 'NjU2NTY2NjU1NjI1NTYzMg==',
      'accountName' => 'Uk9ZQUwgVk9JQ0U=',
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