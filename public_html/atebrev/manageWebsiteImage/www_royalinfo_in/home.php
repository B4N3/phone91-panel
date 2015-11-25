<?php $data=array (
  'welcomeImage' => 'd2VsY29tZUltYWdlLmpwZw==',
  'homeMeta' => 
  array (
    'mKeyword' => 'V09STEQgQ0xBU1MgVk9JQ0UgU0VSVklDRQ==',
    'mDescription' => '',
    'title' => 'Ojo6Ojo6OiBST1lBTCBWT0lDRSA6Ojo6Ojo6',
  ),
  'welcomeContent' => 'Um95YWwgVm9pY2UgU29sdXRpb24gSXMgYSBXb3JsZCBDbGFzcyBWT0lQIFNvbHV0aW9uLiBQcm92aWRlIFZPSVAgU2VydmljZSBBbnkgd2hlcmUgQW55IE1vbWVudCBPdmVyIFRoZSBXb3JsZC4gUm95YWwgVm9pY2UgaXMgYSBVSyBCYXNlZCBDb21wYW55LiBUbyBQcm92aWRlIFdvcmxkIExhcmdlc3QgQ2FsbGluZyBOZXR3b3JrIEZvciBDb25uZWN0aW5nIFBlb3BsZS4g',
  'homebannerDetail' => 
  array (
    'heading' => 'Lg==',
    'subHeading' => 'Lg==',
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