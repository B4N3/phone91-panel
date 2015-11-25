<?php
/**
 * Copyright (C) 2006 Google Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *      http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Please refer to the Google Checkout PHP Sample Code Documentation
 * for requirements and guidelines on how to use the sample code.
 * 
 * "ResponseHandlerAPIFunctions.php" is a client library of functions 
 * that enable merchants to systematically handle Google Checkout
 * notifications using the Notification API and to systematically 
 * generate XML for Order Processing API requests.
 *
 * You will need to modify the functions that handle Google Checkout
 * notifications.
 */

/*
 * If you are implementing the Notification and Order Processing APIs,
 * you should also include the OrderProcessingAPIFunctions file, which
 * contains functions that handle Notification API requests.
 */

include("NotificationAPIFunctions.php");
include("MerchantCalculationsAPIFunctions.php");
include('dbcon.php');
/**
 * The ProcessXmlData function creates a DOM object representation of the
 * XML document received from Google Checkout. It then evaluates the root 
 * tag of the document to determine which function should handle the document.
 *
 * This function routes the XML responses that Google Checkout sends in 
 * response to API requests. These replies are sent to one of the other 
 * functions in this library.
 *
 * This function also routes Merchant Calculations API requests and
 * Notification API requests. Those requests are processed by functions
 * in the MerchantCalculationsAPIFunctions.php and 
 * NotificationAPIFunctions.php libraries, respectively.
 *
 * @param    $xml_data    The XML document sent by the Google Checkout server.
 */

function ProcessXmlData($xml_data) {
 
    $dom_response_obj = domxml_open_mem($xml_data);
    $dom_data_root = $dom_response_obj->document_element();
    $message_recognizer = $dom_data_root->tagname();

    /*
     * Select the appropriate function to handle the XML document
     * by evaluating the root tag of the document. Functions to
     * handle the following types of responses are contained in
     * this document:
     *     <request-received>
     *     <error>
     *     <diagnosis>
     *     <checkout-redirect>
     *
     * This function routes the following types of responses
     * to the MerchantCalculationsAPIFunctions.php file:
     *     <merchant-calculation-callback>
     *
     * This function routes the following types of responses
     * to the NotificationAPIFunctions.php file:
     *     <new-order-notification>
     *     <order-state-change-notification>
     *     <charge-amount-notification>
     *     <chargeback-amount-notification>
     *     <refund-amount-notification>
     *     <risk-information-notification>
     * 
     */

    switch ($message_recognizer) {
    
        // <request-received> received
        case "request-received":
            ProcessRequestReceivedResponse($dom_response_obj);
            break;

        // <error> received
        case "error":
            ProcessErrorResponse($dom_response_obj);
            break;

        // <diagnosis> received
        case "diagnosis":
            ProcessDiagnosisResponse($dom_response_obj);
            break;

        // <checkout-redirect> received
        case "checkout-redirect":
            ProcessCheckoutRedirect($dom_response_obj);
            break;
        /*
         * +++ CHANGE ME +++
         * The following case is only for partners who are implementing 
         * the Merchant Calculations API. If you are not implementing 
         * the Merchant Calculations API, you may ignore this case.
         */

        // <merchant-calculation-callback> received
        case "merchant-calculation-callback":
            ProcessMerchantCalculationCallback($dom_response_obj);
            break;

        /*
         * +++ CHANGE ME +++
         * The following cases are only for partners who are
         * implementing the Notification API. If you are not
         * implementing the Notification API, you may ignore
         * the remaining cases in this function.
         */

        // <new-order-notification> received
        case "new-order-notification":
            ProcessNewOrderNotification($dom_response_obj);
            break;

        // <order-state-change-notification> received
        case "order-state-change-notification":
            ProcessOrderStateChangeNotification($dom_response_obj);
            break;

        // <charge-amount-notification> received
        case "charge-amount-notification":
            ProcessChargeAmountNotification($dom_response_obj);
            break;

        // <chargeback-amount-notification> received
        case "chargeback-amount-notification":
            ProcessChargebackAmountNotification($dom_response_obj);
            break;
    
        // <refund-amount-notification> received
        case "refund-amount-notification":
            ProcessRefundAmountNotification($dom_response_obj);
            break;

        // <risk-information-notification> received
        case "risk-information-notification":
            ProcessRiskInformationNotification($dom_response_obj);
            break;

        /*
         * None of the above: The message is not recognized. 
         * You should not remove this case.
         */
        default:
    }
}

/******** Functions for processing synchronous response messages *********/

/**
 * The ProcessRequestReceivedResponse function receives a synchronous
 * Google Checkout response to an API request originating from your site. This
 * function indicates that your API request contained properly formed
 * XML but does not indicate whether your request was processed successfully.
 *
 * @param    $dom_response_obj    synchronous response XML message
 */
function ProcessRequestReceivedResponse($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * You may need to modify this function if you wish to log
     * information or perform other actions when you receive
     * a Google Checkout <request-received> response. The <request-received> 
     * response indicates that you sent a properly formed XML request to 
     * Google Checkout. However, this response does not indicate whether your 
     * request was processed successfully.
     */
    echo htmlentities($dom_response_obj->dump_mem());
}

/**
 * The ProcessErrorResponse function receives a synchronous Google Checkout 
 * response to an API request originating from your site. This function 
 * indicates that your API request was not processed. A request might not be
 * processed if it does not contain properly formed XML or if it does not 
 * contain a valid merchant ID and merchant key.
 *
 * @param    $dom_response_obj    synchronous response XML message
 */
function ProcessErrorResponse($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * You may need to modify this function if you wish to log
     * information or perform other actions when you receive
     * a Google Checkout <error> response. The <error> response indicates 
     * that you sent an invalid XML request to Google Checkout and 
     * contains information explaining why the request was invalid.
     */
  echo $dom_response_obj->dump_mem();
}

/**
 * The ProcessDiagnosisResponse function receives a synchronous Google
 * Checkout response to an API request sent to the Google Checkout
 * XML validator. You can submit a request to the validator by appending
 * the text "/diagnose" to the POST target URL. The response to a
 * diagnostic request contains a list of any warnings returned by
 * the Google Checkout validator.
 *
 * @param    $dom_response_obj    synchronous response XML message
 */
function ProcessDiagnosisResponse($dom_response_obj) {
    /*
     * +++ CHANGE ME +++
     * You may need to modify this function if you wish to log
     * warnings or perform other actions when you receive
     * a Google Checkout <diagnosis> response. The <diagnosis> response 
     * contains warnings that the Google Checkout XML validator generated 
     * when evaluating your XML request.
     */
    echo htmlentities($dom_response_obj->dump_mem());
}

/**
 * The ProcessCheckoutRedirect function receives a synchronous Google
 * Checkout response to a Checkout API request. The <checkout-redirect>
 * response identifies the URL to which you should redirect your customer
 * so that the customer can complete an order using Google Checkout.
 *
 * @param    $dom_response_obj    synchronous response XML message
 */
function ProcessCheckoutRedirect($dom_response_obj) {

    // Identify the URL to which the customer should be redirected
    $dom_data_root = $dom_response_obj->document_element();
    $redirect_url_list = $dom_data_root->get_elements_by_tagname("redirect-url");
    $redirect_url = $redirect_url_list[0]->get_content();

/*$from_email = "feedback@msg91.com";
$headers = "X-Priority: 1\n"; 
$headers .= "X-MSMail-Priority: High\n"; 
$headers .= "Return-Path: <$from_email>\n";
$headers .= "Reply-To: <".$from_email.">\n";
$headers .= "From:  <" . $from_email . ">\n";
$headers .= "X-Sender: <$from_email>\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$xml_response=htmlentities($dom_response_obj->dump_mem());

mail("indoreankita@gmail.com","in process checkout func",$xml_response,$headers);
*/
	 // Redirect the customer to the URL
    Header("Location: " . $redirect_url);
}

/** End of file **/

?>