<?php $data=array (
  'welcomeImage' => 'd2VsY29tZUltYWdlLmpwZw==',
  'homeMeta' => 
  array (
    'mKeyword' => 'cmVyZWZkZmRmZGY=',
    'mDescription' => 'Z2ZkZ2RmZ2Rm',
    'title' => 'ZmRlcg==',
  ),
  'welcomeContent' => 'dHJmcmV0IHJ0cnQgZyBmZw==',
  'homebannerDetail' => 
  array (
    'heading' => 'ZmRyZXI=',
    'subHeading' => 'ZmRzZnNhZHNkc2Q=',
    'text' => 'amhqaGpoag==',
    'link' => 'aHR0cDovL21hbmFnZXdlYjEucGhvbmU5MS5jb20=',
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