<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* @version 1.0
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Jangra<tarun@izap.in>"
* For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
* Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */


//Setting information about the transaction
$receivedSecurityCode = urldecode($_POST['ap_securitycode']);
$receivedMerchantEmailAddress = urldecode($_POST['ap_merchant']);
$transactionStatus = urldecode($_POST['ap_status']);
$testModeStatus = urldecode($_POST['ap_test']);
$purchaseType = urldecode($_POST['ap_purchasetype']);
$totalAmountReceived = urldecode($_POST['ap_totalamount']);
$feeAmount = urldecode($_POST['ap_feeamount']);
$netAmount = urldecode($_POST['ap_netamount']);
$transactionReferenceNumber = urldecode($_POST['ap_referencenumber']);
$currency = urldecode($_POST['ap_currency']);
$transactionDate= urldecode($_POST['ap_transactiondate']);
$transactionType= urldecode($_POST['ap_transactiontype']);

//Setting the customer's information from the IPN post variables
$customerFirstName = urldecode($_POST['ap_custfirstname']);
$customerLastName = urldecode($_POST['ap_custlastname']);
$customerAddress = urldecode($_POST['ap_custaddress']);
$customerCity = urldecode($_POST['ap_custcity']);
$customerState = urldecode($_POST['ap_custstate']);
$customerCountry = urldecode($_POST['ap_custcountry']);
$customerZipCode = urldecode($_POST['ap_custzip']);
$customerEmailAddress = urldecode($_POST['ap_custemailaddress']);

//Setting information about the purchased item from the IPN post variables
$myItemName = urldecode($_POST['ap_itemname']);
$myItemCode = urldecode($_POST['ap_itemcode']);
$myItemDescription = urldecode($_POST['ap_description']);
$myItemQuantity = urldecode($_POST['ap_quantity']);
$myItemAmount = urldecode($_POST['ap_amount']);

//Setting extra information about the purchased item from the IPN post variables
$additionalCharges = urldecode($_POST['ap_additionalcharges']);
$shippingCharges = urldecode($_POST['ap_shippingcharges']);
$taxAmount = urldecode($_POST['ap_taxamount']);
$discountAmount = urldecode($_POST['ap_discountamount']);

//Setting your customs fields received from the IPN post variables
$myCustomField_1 = urldecode($_POST['apc_1']);
$myCustomField_2 = urldecode($_POST['apc_2']);
$myCustomField_3 = urldecode($_POST['apc_3']);
$myCustomField_4 = urldecode($_POST['apc_4']);
$myCustomField_5 = urldecode($_POST['apc_5']);
$myCustomField_6 = urldecode($_POST['apc_6']);

$user = get_user($myCustomField_1);
if($user) {
  define("IPN_SECURITY_CODE", get_plugin_usersetting('alertpay_IPN_security_code', $user->guid, GLOBAL_IZAP_PAYMENT_PLUGIN));
  define("MY_MERCHANT_EMAIL", get_plugin_usersetting('alertpay_user_id', $user->guid, GLOBAL_IZAP_PAYMENT_PLUGIN));
}

if ($receivedMerchantEmailAddress == MY_MERCHANT_EMAIL && $receivedSecurityCode == IPN_SECURITY_CODE && $transactionStatus == "Success") {
  $_POST['IT_WORKED'] = 'YES';
} else {
  $_POST['IT_WORKED'] = 'NO';
}

// just some test data
if ($testModeStatus == "1") {
  $_POST['IPN_SECURITY_CODE'] = IPN_SECURITY_CODE;
  $_POST['MY_MERCHANT_EMAIL'] = MY_MERCHANT_EMAIL;

  foreach($_POST as $key => $val) {
    $string .= $key .' = ' . urldecode($val) . "<br />\n\r";
  }
  func_send_mail_byizap(array(
          'msg' => $string,
          'subject' => 'TEST MAIL FROM: ALERT PAY',
          'from' => 'testAP@izap.in',
          'from_username' => 'ALERT PAY TEST',
          'to' => 'chetan@izap.in',
  ));
}
// test data ends