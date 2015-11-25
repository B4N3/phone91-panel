<?php $data=array (
  'welcomeImage' => '',
  'homeMeta' => 
  array (
    'mKeyword' => 'Y2hlYXAsIGNhbGwsIGNhbGxpbmcsIHZvaWNlLCB2b2lwLCBjaGVhcGVzdCwgZnJlZSw=',
    'mDescription' => 'VGhpcyB3ZWJzaXRlIHByb3ZpZGVzIGJlc3QgYW5kIGxvd2VzdCBjYWxsaW5nIHJhdGVzIHdvcmxkd2lkZS4=',
    'title' => 'Q2hlYXBlc3QgTG9uZyBEaXN0YW5jZSBWb2ljZSBDYWxsaW5nIGluIEluZGlh',
  ),
  'welcomeContent' => '',
  'homebannerDetail' => 
  array (
    'heading' => '',
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