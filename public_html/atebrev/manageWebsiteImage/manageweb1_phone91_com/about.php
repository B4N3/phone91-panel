<?php $data=array (
  'domainId' => 'bWFuYWdld2ViMS5waG9uZTkxLmNvbQ==',
  'welcomeImage' => 'YWJvdXRJbWcuanBn',
  'aboutMeta' => 
  array (
    'title' => 'YWJvdXQgcGFnZQ==',
    'mKeyword' => 'ZmVkZmpmIGVmaiBkZWtmaiBka2pmZGs=',
    'mDescription' => 'ZmprbGRlIGpma2RqZmRramYga2RqZmtk',
  ),
  'bannerStatus' => 'MA==',
  'aboutbannerDetail' => 
  array (
    'heading' => 'cndleXJ1eWVydWllIHJ5ZSB1cnkgdWU=',
    'subHeading' => 'cnVleXJ1aWV5IHJ1aWV5',
    'text' => 'IGVyZXlyeWVyZXJl',
    'link' => 'aHR0cDovL21hbmFnZXdlYjEucGhvbmU5MS5jb20=',
  ),
  'whoUR' => 'cmZlcmVyZXI=',
  'vision' => 'ZHdldHJldHJnZmdmZw==',
  'mission' => 'IGZnZiBnZmcgZmcgZmcgZmc=',
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