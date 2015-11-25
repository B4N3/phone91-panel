<?php $data=array (
  'welcomeImage' => 'd2VsY29tZUltYWdlLnBuZw==',
  'homeMeta' => 
  array (
    'mKeyword' => 'SXQncyBlYXN5IHRvIHN0YXJ0IHVzaW5nIExpYmVyYWwgQ2FsbHMNCg0KIFJlZ2lzdGVyIHlvdXIgYWNjb3VudCBOT1cgYW5kIHlvdSB3aWxsIA0KICAgIGdldCAwLDEgVVNEIGZvciBGUkVFDQoNCiBJbnN0YWxsIExpYmVyYWwgQ2FsbHMgYW5kIG1ha2UgDQogICAgRlJFRSBjYWxscyByaWdodCBub3c=',
    'mDescription' => 'SXQncyBlYXN5IHRvIHN0YXJ0IHVzaW5nIExpYmVyYWwgQ2FsbHMNCg0KIFJlZ2lzdGVyIHlvdXIgYWNjb3VudCBOT1cgYW5kIHlvdSB3aWxsIA0KICAgIGdldCAwLDEgVVNEIGZvciBGUkVFDQoNCiBJbnN0YWxsIExpYmVyYWwgQ2FsbHMgYW5kIG1ha2UgDQogICAgRlJFRSBjYWxscyByaWdodCBub3c=',
    'title' => 'TGliZXJhbCBDYWxscw==',
  ),
  'welcomeContent' => 'SXQncyBlYXN5IHRvIHN0YXJ0IHVzaW5nIExpYmVyYWwgQ2FsbHMNCg0KIFJlZ2lzdGVyIHlvdXIgYWNjb3VudCBOT1cgYW5kIHlvdSB3aWxsIA0KICAgIGdldCAwLDEgVVNEIGZvciBGUkVFDQoNCiBJbnN0YWxsIExpYmVyYWwgQ2FsbHMgYW5kIG1ha2UgDQogICAgRlJFRSBjYWxscyByaWdodCBub3c=',
  'homebannerDetail' => 
  array (
    'heading' => 'TGliZXJhbCBDYWxscw==',
    'subHeading' => 'TWFrZSBDYWxscw==',
    'text' => 'dmlzaXQ=',
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