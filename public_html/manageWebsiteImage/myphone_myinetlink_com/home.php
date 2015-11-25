<?php $data=array (
  'welcomeImage' => '',
  'homeMeta' => 
  array (
    'mKeyword' => '',
    'mDescription' => '',
    'title' => 'TXlJbmV0TGluayBQaG9uZQ==',
  ),
  'welcomeContent' => '',
  'homebannerDetail' => 
  array (
    'heading' => 'TXlJbmV0TGluayBQaG9uZQ==',
    'subHeading' => '',
    'text' => '',
    'link' => '',
  ),
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