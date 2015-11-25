<?php $data=array (
  'domainId' => 'd3d3LnJveWFsaW5mby5pbg==',
  'welcomeImage' => 'YWJvdXRJbWcuanBn',
  'aboutMeta' => 
  array (
    'title' => 'Ojo6Ojo6OiBST1lBTCBWT0lDRSA6Ojo6Ojo6',
    'mKeyword' => '',
    'mDescription' => '',
  ),
  'bannerStatus' => 'MA==',
  'aboutbannerDetail' => 
  array (
    'heading' => '',
    'subHeading' => '',
    'text' => 'c2lnblVw',
    'link' => 'aHR0cDovL3d3dy5yb3lhbGluZm8uaW4vc2lnblVwV0xhYmVsLnBocA==',
  ),
  'whoUR' => 'Um95YWwgVm9pY2UgU29sdXRpb24gSXMgYSBXb3JsZCBDbGFzcyBWT0lQIFNvbHV0aW9uLiBQcm92aWRlIFZPSVAgU2VydmljZSBBbnkgd2hlcmUgQW55IE1vbWVudCBPdmVyIFRoZSBXb3JsZC4gUm95YWwgVm9pY2UgaXMgYSBVSyBCYXNlZCBDb21wYW55LiBUbyBQcm92aWRlIFdvcmxkIExhcmdlc3QgQ2FsbGluZyBOZXR3b3JrIEZvciBDb25uZWN0aW5nIFBlb3BsZS4=',
  'vision' => 'V2hpbGUgbW9zdCBWb0lQIHByb3ZpZGVycyBpbnNpc3Qgb24gc3VwcGx5aW5nIFZvSVAgZGV2aWNlLCB3aGljaCB1c3VhbGx5IGlzIGxvY2tlZCBzbyBpdCBvbmx5IHdvcmtzIHdpdGggb25lIHNlcnZpY2UgcHJvdmlkZXIsIHdpdGggVm9JUFZvSVAsIHlvdSBoYXZlIHRoZSBmcmVlZG9tIHRvIHVzZSB2aXJ0dWFsbHkgYW55IHNvZnRwaG9uZSwgVm9JUCBhZGFwdGVyLCBnYXRld2F5LCA=',
  'mission' => 'V2hlbiB5b3UgdXNlIHlvdXIgb3duIHVubG9ja2VkIGRldmljZSwgeW91IGhhdmUgYSBjaG9pY2UgdG8gcGxhY2UgY2FsbHMgdG8gZGlmZmVyZW50IGRlc3RpbmF0aW9ucyB1c2luZyBkaWZmZXJlbnQgcHJvdmlkZXJzIHRvIGdldCB0aGUgbG93ZXN0IGNhbGwgcmF0ZS4NClZvSVBWb0lQIHJldm9sdXRpb25pemVzIHRoZSB3YXkgcGVvcGxlIHN1YnNjcmliZSB0byB0ZWxlcGhvbmUgc2VydmljZS4gDQo=',
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