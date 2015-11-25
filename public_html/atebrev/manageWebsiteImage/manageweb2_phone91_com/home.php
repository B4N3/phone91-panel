<?php $data=array (
  'welcomeImage' => 'd2VsY29tZUltYWdlLnBuZw==',
  'homeMeta' => 
  array (
    'mKeyword' => 'ZmprZGpma2RqIGtmZGoga2Y=',
    'mDescription' => 'IGpka2pma2RqZiBrZGpm',
    'title' => 'aG9tZSBwYWdl',
  ),
  'welcomeContent' => 'ZHJ3ZXIgZXIgZXI=',
  'homebannerDetail' => 
  array (
    'heading' => 'ZmVyeSB1ZXly',
    'subHeading' => 'IGpramVrZWpyaw==',
    'text' => 'amtmamRma2RqZiA=',
    'link' => 'aHR0cDovL21hbmFnZXdlYjIucGhvbmU5MS5jb20=',
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