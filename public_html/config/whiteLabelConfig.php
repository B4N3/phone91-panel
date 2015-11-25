<?php
/**
*@filesource 
*@author Ankit Patidar <ankitpatidar@hostnsoft.com> 
*@since 25/12/2013
*@uses configuration file for white Label ,it stores all constants used for while Label
*
*
*/
//include required files


include_once(dirname(dirname(__FILE__)).'/classes/websiteClass.php');

//include_once(dirname(__FILE__).'/config.php');
//create fun class object
$funObj = new fun();

if(isset($_REQUEST['id']) && $_REQUEST['id'] != ""){
    $serverHost = $_REQUEST['id'];
}
else {
    $serverHost = $_SERVER['HTTP_HOST'];
}
//defined constant for domain name 
if(!defined('_DOMAIN_NAME_'))
	define('_DOMAIN_NAME_', $serverHost);

if(!defined('_DOMAIN_FOLDER_'))
	define('_DOMAIN_FOLDER_', str_replace (".", "_", $serverHost));

//define manage website path
if(!defined('_MANAGE_PATH_'))
{
    if(file_exists('/manageWebsiteImage/'._DOMAIN_NAME_))
    {
	define('_MANAGE_PATH_',dirname(dirname(__FILE__)).'/manageWebsiteImage/'._DOMAIN_NAME_);
       
    }
    else 
    {
        define('_MANAGE_PATH_',dirname(dirname(__FILE__)).'/manageWebsiteImage/sevphalDef/'); 
        
    }
    
}

defined('_DEFAULT_MANAGE_PATH_') or define('_DEFAULT_MANAGE_PATH_', dirname(dirname(__FILE__)).'/manageWebsiteImage/sevphalDef/');

$manageWebsiteFlag = 0;
//
$result = $funObj->getDomainResellerIdViaApc(_DOMAIN_NAME_,2);
include_once _MANAGE_PATH_.'main.php';
//$result = $data;
if($result != 0)
{
    $manageWebsiteFlag = 1;
}


//var_dump($result);

if(isset($_SESSION) && !isset($_SESSION['res_id']) && isset($resellerId))
    $_SESSION['res_id'] = $resellerId;


$defaultTheme = ((isset($result['theme']) and $result['theme'] != '') ? $result['theme'] :'defaultTheme');

//var_dump($defaultTheme);
//defined constant theme for whitelabel use
if(!defined('_DOMAIN_THEME_'))
	define('_DOMAIN_THEME_', "$defaultTheme");

if(!defined('_THEME_PATH_'))
	define('_THEME_PATH_', "themes/"._DOMAIN_THEME_);

//constant for domain flag if 0 select folder,if 1 select whitelabel theme
#if(!defined('_DOMAIN_FLAG_'))
#	define('_DOMAIN_FLAG_', $result['flag']);
//
//
////if(!defined('_COMPANY_NAME_'))
////	define('_COMPANY_NAME_', $result['companyName']);
//
//// if(!$result)
//// 	die('Domain name not Registered!!!');
//
//
//// if($domainResult['flag'] == 0)
//// {
//	
//// 	//include index of folder
//// 	include_once(dirname(dirname(__FILE__)).'/manageWebsiteImage/voip91/index.php');
//// 	exit();
//
//// }
//// else
//// {
//// 	//code to include templete
//// }
//
//
//
////create manage website object
//$webObj = new websiteClass();
//
////get reseller id
//$resellerId = $result['resellerId'];
//
////get company name
//$companyName = $result['companyName'];
//
////set company name 
//if(!defined('_COMPANY_NAME_'))
//	define('_COMPANY_NAME_', $companyName);
//
//
////get website details
//$getGenData = json_decode($webObj->getGeneralData(_DOMAIN_NAME_,$resellerId),TRUE);
//
////var_dump($getGenData);
////die('stop');
//
////set values for genaral data
//$logoImage = (isset($getGenData['logoimage'] ) and $getGenData['logoimage'] != '') ? $getGenData['logoimage'] :'';
//
//$welImag = (isset($getGenData['welcomeImage'] ) and $getGenData['welcomeImage'] != '') ? $getGenData['welcomeImage'] :'Welcome imag';
//
//if(!defined('_HOME_BANNER_IMAGE_'))
//	define('_HOME_BANNER_IMAGE_', _MANAGE_PATH_.$welImag);
//
///**
//*set banner details,heading,subheading,button text and button link
//*/
//$homeTitle = (isset($getGenData['title'] ) and $getGenData['title'] != '') ? $getGenData['title'] :'Welcome to my website';
//
//if(!defined('_HOME_TITLE_'))
//	define('_HOME_TITLE_', $homeTitle);
//
//
//$homeBannerHeading = (isset($getGenData['heading'] ) and $getGenData['heading'] != '') ? $getGenData['heading'] : '' ;
//if(!defined('_BANNER_HEADING_'))
//	define('_BANNER_HEADING_', $homeBannerHeading);
//
//$homeBannerSubHead = (isset($getGenData['subHeading'] ) and $getGenData['subHeading'] != '') ? $getGenData['subHeading'] : '' ;
//if(!defined('_BANNER_SUB_HEAD_'))
//	define('_BANNER_SUB_HEAD_', $homeBannerSubHead);
//
//$homeBannerButtonText = (isset($getGenData['text'] ) and $getGenData['text'] != '') ? $getGenData['text'] : '' ;
//if(!defined('_BANNER_BUTTON_TEXT_'))
//	define('_BANNER_BUTTON_TEXT_', $homeBannerButtonText);
//
//$homeBannerButtonLink = (isset($getGenData['link'] ) and $getGenData['link'] != '') ? $getGenData['link'] : '' ;
//if(!defined('_BANNER_BUTTON_LINK_'))
//	define('_BANNER_BUTTON_LINK_', $homeBannerButtonLink);
//
////set logo image name
//if(!defined('_LOGO_IMAGE_'))
//	define('_LOGO_IMAGE_', $logoImage);
//
///**
//*get socail links for website
//*/
//$socialLinksFB = (isset($getGenData['socialLinks']['facebook'] ) and $getGenData['socialLinks']['facebook'] != '') ? $getGenData['socialLinks']['facebook'] : '' ;
//if(!defined('_FB_LINK_'))
//	define('_FB_LINK_', $socialLinksFB);
//
//$socialLinksTwitter = (isset($getGenData['socialLinks']['twitter'] ) and $getGenData['socialLinks']['twitter'] != '') ? $getGenData['socialLinks']['twitter'] : '' ;
//if(!defined('_TWITTER_LINK_'))
//	define('_TWITTER_LINK_', $socialLinksTwitter);
//
//$socialLinksLinkedin = (isset($getGenData['socialLinks']['linkedin'] ) and $getGenData['socialLinks']['linkedin'] != '') ? $getGenData['socialLinks']['linkedin'] : '' ;
//if(!defined('_LINKEDIN_LINK_'))
//	define('_LINKEDIN_LINK_', $socialLinksLinkedin);
//
//$socialLinksGplus = (isset($getGenData['socialLinks']['gplus'] ) and $getGenData['socialLinks']['gplus'] != '') ? $getGenData['socialLinks']['gplus'] :'';
//if(!defined('_GPLUS_LINK_'))
//	define('_GPLUS_LINK_', $socialLinksGplus);
//
///**
//*set company details
//*/
//$contactAdd = (isset($getGenData['contact']['address'] ) and $getGenData['contact']['address'] != '') ? $getGenData['contact']['address'] : '' ;
//if(!defined('_CONTACT_ADDRESS_'))
//	define('_CONTACT_ADDRESS_', $contactAdd);
//
//$contactPhoneNo = (isset($getGenData['contact']['phoneNo'] ) and $getGenData['contact']['phoneNo'] != '') ? $getGenData['contact']['phoneNo'] : '' ;
//if(!defined('_CONTACT_PHONE_NO_'))
//	define('_CONTACT_PHONE_NO_', $contactPhoneNo);
//
//$contactEmail = (isset($getGenData['contact']['email'] ) and $getGenData['contact']['email'] != '' )? $getGenData['contact']['email'] : '' ;
//if(!defined('_CONTACT_EMAIL_'))
//	define('_CONTACT_EMAIL_', $contactEmail);
//
///**
//*set home page details in constants
//*/
//$homeKeyword = (isset($getGenData['mKeyword'] ) and $getGenData['mKeyword'] != '') ? $getGenData['mKeyword'] : '' ;
//if(!defined('_HOME_KEYWORD_'))
//	define('_HOME_KEYWORD_', $homeKeyword);
//
//$homeDescription = (isset($getGenData['mDescription'] ) and $getGenData['mDescription'] != '') ? $getGenData['mDescription'] : '' ;
//if(!defined('_HOME_DESCRIPTION_'))
//	define('_HOME_DESCRIPTION_', $homeDescription);
//
//$welcomeImage = (isset($getGenData['welcomeImage'] ) and $getGenData['welcomeImage'] != '') ? $getGenData['welcomeImage'] : '' ;
//if(!defined('_WELCOME_IMAGE_'))
//	define('_WELCOME_IMAGE_', $welcomeImage);
//
//$welcomeContent = (isset($getGenData['welcomeContent'] ) and $getGenData['welcomeContent'] != '') ? $getGenData['welcomeContent'] : '' ;
//if(!defined('_WELCOME_CONTENT_'))
//	define('_WELCOME_CONTENT_', $welcomeContent);
//
//
////get about data
//$aboutData = json_decode($webObj->getAboutData(_DOMAIN_NAME_,$resellerId),TRUE);
//
////var_dump($aboutData);
////die('stop');
//
//$abountTitle = (isset($aboutData['title'] ) and $aboutData['title'] != '') ? $aboutData['title'] :'Welcome to my website';
//
//if(!defined('_ABOUT_TITLE_'))
//	define('_ABOUT_TITLE_', $abountTitle);
//
//$abountBannerImg = (isset($aboutData['welcomeImage'] ) and $aboutData['welcomeImage'] != '') ? $aboutData['welcomeImage'] :'Welcome image';
//
//if(!defined('_ABOUT_BANNER_IMAGE_'))
//	define('_ABOUT_BANNER_IMAGE_', _MANAGE_PATH_.$abountBannerImg);
//
//
////set values for about data,keyword and description
//$aboutKeyword = (isset($aboutData['mKeyword'] ) and $aboutData['mKeyword'] != '') ? $aboutData['mKeyword'] : '' ;
//if(!defined('_ABOUT_KEYWORD_'))
//	define('_ABOUT_KEYWORD_', $aboutKeyword);
//
//$aboutDescription = (isset($aboutData['mDescription'] ) and $aboutData['mDescription'] != '') ? $aboutData['mDescription'] : '' ;
//if(!defined('_ABOUT_DESCRIPTION_'))
//	define('_ABOUT_DESCRIPTION_', $aboutDescription);
//
//
///**
//*set banner details,heading,subheading,button text and button link
//*/
//$bannerHeading = (isset($aboutData['bannerDetail']['heading'] ) and $aboutData['bannerDetail']['heading'] != '') ? $aboutData['bannerDetail']['heading'] : '' ;
//if(!defined('_ABOUT_BANNER_HEADING_'))
//	define('_ABOUT_BANNER_HEADING_', $bannerHeading);
//
//$bannerSubHead = (isset($aboutData['bannerDetail']['subHeading'] ) and $aboutData['bannerDetail']['subHeading'] != '') ? $aboutData['bannerDetail']['subHeading'] : '' ;
//if(!defined('_ABOUT_BANNER_SUB_HEAD_'))
//	define('_ABOUT_BANNER_SUB_HEAD_', $bannerSubHead);
//
//$bannerButtonText = (isset($aboutData['bannerDetail']['text'] ) and $aboutData['bannerDetail']['text'] != '') ? $aboutData['bannerDetail']['text'] : '' ;
//if(!defined('_ABOUT_BANNER_BUTTON_TEXT_'))
//	define('_ABOUT_BANNER_BUTTON_TEXT_', $bannerButtonText);
//
//$bannerButtonLink = (isset($aboutData['bannerDetail']['link'] ) and $aboutData['bannerDetail']['link'] != '') ? $aboutData['bannerDetail']['link'] : '' ;
//if(!defined('_ABOUT_BANNER_BUTTON_LINK_'))
//	define('_ABOUT_BANNER_BUTTON_LINK_', $bannerButtonLink);
//
//
///**
//*set constants mission,vision and whoUR for website
//*/
//$mission = (isset($aboutData['mission'] ) and $aboutData['mission'] != '') ? $aboutData['mission'] : '' ;
//if(!defined('_MISSION_'))
//	define('_MISSION_', $mission);
//
//$vision = (isset($aboutData['vision'] ) and $aboutData['vision'] != '') ? $aboutData['vision'] : '' ;
//if(!defined('_VISION_'))
//	define('_VISION_', $vision);
//
//$whoUR = (isset($aboutData['whoUR'] ) and $aboutData['whoUR'] != '') ? $aboutData['whoUR'] : '' ;
//if(!defined('_WHO_UR_'))
//	define('_WHO_UR_', $whoUR);
//
////set banner status
//$bannerStatus = (isset($aboutData['bannerStatus'] ) and $aboutData['bannerStatus'] != '') ? $aboutData['bannerStatus'] : '' ;
//if(!defined('_BANNER_STATUS_'))
//	define('_BANNER_STATUS_', $bannerStatus);
//
////get contact page data
//$contactData = json_decode($webObj->getContactPageData(_DOMAIN_NAME_,$resellerId),TRUE);
//
//$contactTitle = (isset($contactData['title'] ) and $contactData['title'] != '') ? $contactData['title'] : '' ;
//if(!defined('_CONTACT_TITLE_'))
//	define('_CONTACT_TITLE_', $contactTitle);
//
////set contact data,keywork,description,banner status
//$contactKeyword = (isset($contactData['mKeyword'] ) and $contactData['mKeyword'] != '') ? $contactData['mKeyword'] : '' ;
//if(!defined('_CONTACT_KEYWORD_'))
//	define('_CONTACT_KEYWORD_', $contactKeyword);
//
//$contactDescription = (isset($contactData['mDescription'] ) and $contactData['mDescription'] != '' )? $contactData['mDescription'] : '' ;
//if(!defined('_CONTACT_DESCRIPTION_'))
//	define('_CONTACT_DESCRIPTION_', $contactDescription);
//
//$cntbannerStatus = (isset($contactData['cntbannerStatus'] ) and $contactData['cntbannerStatus'] != '') ? $contactData['cntbannerStatus'] : '' ;
//if(!defined('_CONTACT_BANNER_STATUS_'))
//	define('_CONTACT_BANNER_STATUS_', $cntbannerStatus);
//
////get contact banner details,heading,subheading,button link,button text
//$cntbannerHeading = (isset($contactData['cntbannerDetail']['heading'] ) and $contactData['cntbannerDetail']['heading'] != '') ? $contactData['cntbannerDetail']['heading'] : '' ;
//if(!defined('_CONTACT_BANNER_HEADING_'))
//	define('_CONTACT_BANNER_HEADING_', $cntbannerHeading);
//
//$cntbannerSubHeading = (isset($contactData['cntbannerDetail']['subHeading'] ) and $contactData['cntbannerDetail']['subHeading'] != '') ? $contactData['cntbannerDetail']['subHeading'] : '' ;
//if(!defined('_CONTACT_BANNER_SUB_HEADING_'))
//	define('_CONTACT_BANNER_SUB_HEADING_', $cntbannerSubHeading);
//
//
//$cntbannerBtnlink = (isset($contactData['cntbannerDetail']['link'] ) and $contactData['cntbannerDetail']['link'] != '') ? $contactData['cntbannerDetail']['link'] : '' ;
//if(!defined('_CONTACT_BANNER_BUTTON_LINK_'))
//	define('_CONTACT_BANNER_BUTTON_LINK_', $cntbannerBtnlink);
//
//
//$cntbannerButtonTxt = (isset($contactData['cntbannerDetail']['text'] ) and $contactData['cntbannerDetail']['text'] != '') ? $contactData['cntbannerDetail']['text'] : '' ;
//if(!defined('_CONTACT_BANNER_BUTTON_TEXT_'))
//	define('_CONTACT_BANNER_BUTTON_TEXT_', $cntbannerButtonTxt);
//
///**
//*set contact form status and contact form email
//*/
//$contactFormStatus = (isset($contactData['contactFormStatus'] ) and $contactData['contactFormStatus'] != '') ? $contactData['contactFormStatus'] : '' ;
//if(!defined('_CONTACT_FORM_STATUS_'))
//	define('_CONTACT_FORM_STATUS_', $contactFormStatus);
//
//$contactFormEmail = (isset($contactData['contactFormEmail'] ) and $contactData['contactFormEmail'] != '') ? $contactData['contactFormEmail'] : '' ;
//if(!defined('_CONTACT_FORM_EMAIL_'))
//	define('_CONTACT_FORM_EMAIL_', $contactFormEmail);
//
//
///**
//*set map location status and gmap embeded code status
//*/
//$mapLocationStatus = (isset($contactData['mapLocationStatus'] ) and $contactData['mapLocationStatus'] != '') ? $contactData['mapLocationStatus'] : '' ;
//if(!defined('_MAP_LOCATION_STATUS_'))
//	define('_MAP_LOCATION_STATUS_', $mapLocationStatus);
//
//$gMapEmbededCode = (isset($contactData['gMapEmbededCode'] ) and $contactData['gMapEmbededCode'] != '') ? $contactData['gMapEmbededCode'] : '' ;
//if(!defined('_GMAP_EMBEDED_CODE_'))
//	define('_GMAP_EMBEDED_CODE_', $gMapEmbededCode);
//
////get pricing data
//$pricingData = json_decode($webObj->getPricingPageData(_DOMAIN_NAME_,$resellerId),TRUE);
//
////var_dump($pricingData);
////die('stop');
//$pricingTitle = (isset($pricingData['title'] ) and $pricingData['title'] != '') ? $pricingData['title'] : '' ;
//if(!defined('_PRICING_TITLE_'))
//	define('_PRICING_TITLE_', $pricingTitle);
//
//$pricingBannerImg = (isset($pricingData['welcomeImage'] ) and $pricingData['welcomeImage'] != '') ? $pricingData['welcomeImage'] :'Welcome image';
//
//if(!defined('_PRICING_BANNER_IMAGE_'))
//	define('_PRICING_BANNER_IMAGE_', _MANAGE_PATH_.$pricingBannerImg);
//
//
//
////set pricing data,keyword,description,tariff plan and bank details
//$pricingKeyword = (isset($pricingData['mKeyword'] ) and $pricingData['mKeyword'] != '') ? $pricingData['mKeyword'] : '' ;
//if(!defined('_PRICING_KEYWORD_'))
//	define('_PRICING_KEYWORD_', $pricingKeyword);
//
//
//$pricingDescription = (isset($pricingData['mDescription'] ) and $pricingData['mDescription'] != '') ? $pricingData['mDescription'] : '';
//if(!defined('_PRICING_DESCRIPTION_'))
//	define('_PRICING_DESCRIPTION_', $pricingDescription);
//
////get contact banner details,heading,subheading,button link,button text
//$pricingBannerHeading = (isset($pricingData['heading']) and $pricingData['heading'] != '') ? $pricingData['heading']: '' ;
//if(!defined('_PRICING_BANNER_HEADING_'))
//	define('_PRICING_BANNER_HEADING_', $pricingBannerHeading);
//
//$pricingBannerSubHeading = (isset($pricingData['subHeading'] ) and $pricingData['subHeading']!= '') ? $pricingData['subHeading'] : '' ;
//if(!defined('_PRICING_BANNER_SUB_HEADING_'))
//	define('_PRICING_BANNER_SUB_HEADING_', $pricingBannerSubHeading);
//
//$pricingBannerText = (isset($pricingData['text']) and $pricingData['text'] != '') ? $pricingData['text']: '' ;
//if(!defined('_PRICING_BANNER_BUTTON_TEXT_'))
//	define('_PRICING_BANNER_BUTTON_TEXT_', $pricingBannerText);
//
//$pricingBannerLink = (isset($pricingData['link']) and $pricingData['link'] != '') ? $pricingData['link']: '' ;
//if(!defined('_PRICING_BANNER_BUTTON_LINK_'))
//	define('_PRICING_BANNER_BUTTON_LINK_', $pricingBannerLink);
//
//
//$tariffPlan = (isset($pricingData['tariffPlan'] ) and $pricingData['tariffPlan'] != '') ? $pricingData['tariffPlan'] : '' ;
//if(!defined('_TARIFF_PLAN_'))
//	define('_TARIFF_PLAN_', $tariffPlan);
//
//
//$bankDetail = (isset($pricingData['bankDetail'] ) and $pricingData['bankDetail'] != '') ? $pricingData['bankDetail'] : '' ;
//
//if(!defined('_BANK_DETAIL_'))
//{   
//	define('_BANK_DETAIL_', json_encode($bankDetail));
//}

//print_r($_SESSION);
//print_r(get_defined_constants());
?>
