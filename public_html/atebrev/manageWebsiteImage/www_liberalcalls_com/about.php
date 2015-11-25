<?php $data=array (
  'domainId' => 'd3d3LmxpYmVyYWxjYWxscy5jb20=',
  'welcomeImage' => '',
  'aboutMeta' => 
  array (
    'title' => 'TGliZXJhbCBDYWxscw==',
    'mKeyword' => 'RGlzY292ZXIgdGhlIEFkdmFudGFnZXMgb2YgYSBTb2Z0d2FyZSBCYXNlZCBWb0lQIElQIFBCWA0KDQo=',
    'mDescription' => '',
  ),
  'bannerStatus' => 'MA==',
  'aboutbannerDetail' => 
  array (
    'heading' => '',
    'subHeading' => '',
    'text' => '',
    'link' => '',
  ),
  'whoUR' => 'RGlzY292ZXIgdGhlIEFkdmFudGFnZXMgb2YgYSBTb2Z0d2FyZSBCYXNlZCBWb0lQIElQIFBCWA==',
  'vision' => 'Vk9JUCBjYWxscyB0byBJbmRpYSBmcm9tIEF1c3RyYWxpYSAtIHRoZSBiZXN0IHNvbHV0aW9uIGZvciBjYWxsaW5nIEluZGlhIGZyb20gQXVzdHJhbGlhDQpWT0lQIHVubGltaXRlZCBjYWxscyB0byBJbmRpYSB3aXRoIHJlbGlhYmxlIEluZGlhIGNhbGxpbmcgc2VydmljZXMgZG8gbm90IGhhdmUgdG8gYmUgZXhwZW5zaXZl',
  'mission' => 'TWFrZSBWT0lQIHVubGltaXRlZCBjYWxscyB0byBJbmRpYSB3aXRoIHJlbGlhYmxlIEluZGlhIGNhbGxpbmcgc2VydmljZXMgIQ0KVk9JUCB1bmxpbWl0ZWQgY2FsbHMgdG8gSW5kaWEgd2l0aCByZWxpYWJsZSBJbmRpYSBjYWxsaW5nIHNlcnZpY2VzIGRvIG5vdCBoYXZlIHRvIGJlIGV4cGVuc2l2ZQ==',
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