<?php $data=array (
  'domainId' => 'bWFuYWdld2ViMi5waG9uZTkxLmNvbQ==',
  'welcomeImage' => '',
  'aboutMeta' => 
  array (
    'title' => 'YWJvdXQgcGFnZQ==',
    'mKeyword' => 'ZmpkayBqZmtsZCBqZg==',
    'mDescription' => 'IGpma2RqZmtkamZr',
  ),
  'bannerStatus' => 'MA==',
  'aboutbannerDetail' => 
  array (
    'heading' => 'ZGZqZmsgZXJ1ZWly',
    'subHeading' => 'amRla2ZpZXJr',
    'text' => 'amtkc2pma2xkamY=',
    'link' => 'aHR0cDovL21hbmFnZXdlYjIucGhvbmU5MS5jb20=',
  ),
  'whoUR' => '4KSP4KS44KWH4KSP4KS14KSCIOCkuOCkvuCkruClguCkueCkv+CklSDgpLXgpL/gpJrgpLDgpLXgpL/gpK7gpLDgpY3gpLYg4KSq4KSi4KSo4KWHIOCkrOCkqOCkvuCkpOCkvyDgpJXgpY3gpLfgpK7gpKTgpL4g4KSq4KS54KWL4KSa4KSo4KWHIOCkpuClh+CkluCkqOClhyDgpLXgpLngpLngpLAg4KSt4KWA4KSv4KS5IOCkleCkvuCksOCljeCkryDgpJzgpL/gpK7gpY3gpK7gpYcg4KSV4KWN4KS34KSu4KSk4KS+4KWkIOCkrOCkv+CkqOCljeCkpuClgeCkkyDgpLjgpYHgpLjgpY3gpKrgpLbgpY3gpJ8g4KSc4KWI4KS44KWAIOCkteCkv+CktuClh+CktyDgpLjgpLngpL/gpKQg4KSk4KSw4KWA4KSV4KWHIOCkqOCkv+CksOCljeCkpuClh+CktiDgpJXgpK7gpY3gpKrgpY3gpK/gpYHgpJ/gpLAg4KSs4KSo4KS+4KSPIOCkleCksOCkvuCkqOCkviDgpLXgpL7gpKTgpL7gpLXgpLDgpKMg4KS24KS+4KSw4KS/4KSw4KS/4KSVIOCksOCkmuCkqOCkviDgpKbgpL/gpKjgpL7gpILgpJUg4KS14KS/4KS24KWN4KS14KS14KWN4KSv4KS+4KSq4KS/IOCkruClgeCkleCljeCkpCDgpKfgpY3gpK/gpYfgpK8g4KSm4KSw4KWN4KS24KS+4KSk4KS+IOCkteCljeCkr+CkteCkueCkvuCksCDgpJXgpLDgpKjgpYcg4KSh4KS+4KSy4KWH4KWkIOCkuOCkguCkquCkvuCkpuCklSDgpIXgpKfgpL/gpJXgpL7gpLAg4KSc4KS+4KSo4KWHICBkc2ZkZmZkZmRmZGZmZGZkc2ZkZmRmZHNmZHNkZmRzZmRmZHNmZGZkZmQgZGY=',
  'vision' => 'cnRyZXQgdA==',
  'mission' => 'ZXJlIHJlIHI=',
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