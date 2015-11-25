<?php $data=array (
  'welcomeImage' => 'TG9yZW0gSXBzdW0=',
  'homeMeta' => 
  array (
    'mKeyword' => 'TG9yZW0gSXBzdW0=',
    'mDescription' => 'TG9yZW0gSXBzdW0=',
    'title' => 'TG9yZW0gSXBzdW0=',
  ),
  'welcomeContent' => 'TG9yZW0gSXBzdW0=',
  'homebannerDetail' => 
  array (
    'heading' => 'TG9yZW0gSXBzdW0=',
    'subHeading' => 'TG9yZW0gSXBzdW0=',
    'text' => 'TG9yZW0gSXBzdW0=',
    'link' => 'TG9yZW0gSXBzdW0=',
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