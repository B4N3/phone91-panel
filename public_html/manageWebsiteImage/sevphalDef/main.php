<?php 

$data = array (
  'companyName' => 'd2Fsa292ZXI=',
  'domainName' => base64_encode($_SERVER['HTTP_HOST']),
  'resellerId' => 'Mg==',
  'language' => 'RW5nbGlzaA==',
  'compEmail' => 'aW5mb0B3YWxrb3Zlci5pbg==',
  'id' => 'Mg==',
  'theme' => 'dm9pcA==',
) ; 

foreach($data as $key => $val)
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