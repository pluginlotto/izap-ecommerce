<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */

class gateway {
  var $objAuthorize;
  var $objPaypal;
  var $objsoap;
  var $mode;
  var $userArr;
  var $type;
  var $amt=array();  // type of array {shipping,tax,total,withHeldAmount,starterKit}
  var $soap_resultmsg = array(
          '0198' => 'The system was down for maintenance',
          '0199' => 'An unrecognized internal system error has occurred',
          '0200' => 'The source card number is invalid',
          '0201' => 'The source card has not been activated',
          '0202' => 'The source card has not been registered',
          '0203' => 'The source card number was a valid number but could not be found',
          '0204' => 'The source card number was in a blocked state',
          '0205' => 'The source card is past expiration date',
          '0206' => 'The type of transaction was not valid for the source card',
          '0207' => 'The origin of the transaction was not valid for the source card',
          '0208' => 'The status of the source card did not allow this transaction',
          '0209' => 'Sourcecard pin was invalid',
          '0210' => 'Sourcecard had too many invalid pin attempts',
          '0211' => 'Sourcecard was valid but is not accessible by this client',
          '0275' => 'Process was not possible due to missing account setup information',
          '0300' => 'The destination card number is invalid',
          '0301' => 'The destination card has not been activated',
          '0302' => 'The destination card has not been registered',
          '0303' => 'The destination card number was a valid number but could not be found',
          '0304' => 'The destination card number was in a blocked state',
          '0308' => 'The status of the destination card did not allow this transaction',
          '0400' => 'Insufficient funds on source card to complete transaction',
          '0401' => 'Invalid amount format',
          '0402' => 'Insufficient funds on source card to complete transaction',
          '0403' => 'The account maximum balance would have been passed by this transaction',
          '0404' => 'Insufficient funds',
          '0405' => 'Invalid amount',
          '0600' => 'Access Code for source card is invalid',
          '0601' => 'Date of birth for source card is invalid',
          '0602' => 'Username is invalid',
          '0603' => 'Password is invalid',
          '0604' => 'Account was in a blocked state',
          '0605' => 'Account was in an inactive state',
          '0606' => 'Account was restricted from accessing services',
          '0607' => 'The account setup was incomplete',
          '0608' => 'The account credentials were not a valid authentication combination',
          '0700' => 'Transaction failed due to invalid information',
          '0704' => 'The currency trying to be delt with is invalid',
          '0705' => 'The system could not transfer funds between two different currency codes',
          '0800' => 'An unspecified error occured when activating the card',
          '0802' => 'The card has not been activated',
          '0825' => 'An unspecified registration error occured',
          '0826' => 'Registration data was not correct',
          '0850' => 'The user tried to transfer money between cards on different bins',
          '0851' => 'The user tried to transfer money to his own card',
          '0852' => 'The card setup information is incomplete',
          '2202' => 'The daily maximum of card to card transfers was reached',
  );
  function gateway($type,$u_arr,$mode) {
    $this->type = strtolower($type);
    $this->userArr=$u_arr;
    if($type=="authorize") {
      require_once(dirname(__FILE__) . '/'.$type.'/'.$type.'.php');
      $this->objAuthorize = new authorize();
      if($mode) {
        //$this->objAuthorize->SetDebug(true);
        $this->objAuthorize->SetTestMode("custom");
      }
    }
    if($type=="paypal") {
      require_once(dirname(__FILE__) . '/'.$type.'/'.$type.'.php');
      $this->objPaypal = new paypal($mode);
    }
    if($type=="vmoney") {
      require_once('soap/nusoap.php');
      $namespace = "webservice.client.vm";
      $this->objsoap = new soapclient("https://clientservice.virtualmoneyinc.com:8004/MerchantService");
      $err = $this->objsoap->getError();
      if ($err) print "Error:" . $err;
      exit();
      //$this->objsoap = new soapclient("https://clientservice.virtualmoneyinc.com:8004/MerchantAPI");
    }
    if($mode) {
      $this->mode = true;
    }
  }
  function authorize($perArr) {

    $userArr = $this->userArr['address'];
    $dataArr = $perArr;
    //$amt = $perArr['amt'];

    //$grandTotal = $amt['grandTotal'] - $amt['minusAmt'];

    $conf['Billing']['AuthorizeNet']['TransKey'] = $this->userArr['payment']['transKey']; // Set this to your key
    $conf['Billing']['AuthorizeNet']['Login'] = $this->userArr['payment']['loginId']; // Set this to your LoginID / Username

    $args = array(
            "fname"=>$userArr['billingFirstName'],
            "lname"=>$userArr['billingLastName'],
            "address1"=>$userArr['billingStreet'],
            "address2"=>'',
            "city"=>$userArr['billingCity'],
            "province"=>$userArr['billingState'],
            "zip"=>$userArr['billingZip'],
            "s_address1"=>$userArr['shippingStreet'],
            "s_city"=>$userArr['shippingCity'],
            "s_province"=>$userArr['shippingState'],
            "s_country"=>$userArr['shippingCountry'],
            "s_zip"=>$userArr['shippingZip'],
            "company"=>$userArr['shippingContact'],
            "hphone"=>$userArr['shippingContact'],
            "bphone"=>$userArr['ftDayPhone'],
            "email"=>$userArr['shippingEmail'],
            "fax"=>'',
            "conf_email"=>'Yes', //confirmation
            "ccname"=>$userArr['billingFirstName'] . $userArr['billingLastName'],
            "cctype"=>$dataArr['cctype'],
            "ccnum"=>$dataArr['ccno'],
            "ccmonth"=>$dataArr['expireMonth'],
            "ccyear"=>$dataArr['expireYear'],
            "cccsv"=>$dataArr['cvvno'],
            "country"=>$userArr['billingCountry'],
            "ccerror"=>0,
            "lastfour"=>0,
            "transaction"=>0,
            "total"=>$this->userArr['totals']['grandTotal'],
            //"customerID"=>0,
            "taxid"=>$this->userArr['totals']['totalTax'] );

    # strip all odd characters
    foreach ($args as $key => $strip) {
      $args[$key] = preg_replace("/[^a-zA-Z0-9._\ \-\@\&\+\#\,\']+/i", "","$strip");
    }
    preg_match("/[0-9][0-9][0-9][0-9]$/",$args['ccnum'],$matches);
    $args['lastfour'] = $matches[0];
    $stamp = date("His");
    $rand = rand(11000, 99999);
    $args['transaction'] = "$args[lastfour]"."$stamp";


    // Set some Variables We Need for the Crap Below
    $CC_ExpMonth = $args['ccmonth'];
    $CC_ExpYear = $args['ccyear'];
    $CC_ExpDate = $CC_ExpMonth . $CC_ExpYear;
    $CC_IP = $_SERVER['REMOTE_ADDR'];

    preg_match("/[0-9][0-9][0-9][0-9]$/",$args['ccnum'],$matches);
    $args[lastfour] = $matches[0];
    $stamp = date("His");
    $rand = rand(11000, 99999);
    $args[transaction] = "$args[lastfour]"."$stamp";

    //Feed Settings Into AuthorizeNet Class
    $this->objAuthorize->SetCredentials($conf['Billing']['AuthorizeNet']['Login'],
            $conf['Billing']['AuthorizeNet']['TransKey']);
    $this->objAuthorize->SetTransactionType('AUTH_CAPTURE');
    $this->objAuthorize->SetMethodType('CC');
    $this->objAuthorize->SetAmount($args['total']);
    $this->objAuthorize->SetCCNumber($args['ccnum']);
    $this->objAuthorize->SetExpDate($CC_ExpDate);
    $this->objAuthorize->SetCustomerIP($CC_IP);
    $this->objAuthorize->CustomerBilling($args['fname'], $args['lname'],
            $args['address1'], $args['city'], $args['province'],
            $args['zip'], $args['bphone'], $args['email'], $args['fax'], $args['country'], $args['company'],
            $args['customerID'], $args['taxid'], $args['transaction'], '');
    $this->objAuthorize->CustomerShipping($args['fname'], $args['lname'], $args['s_address1'], $args['s_city'], $args['s_province'], $args['s_zip'],$args['s_country']);
    $this->objAuthorize->SetCardCode($args['cccsv']);
    $this->objAuthorize->EmailCustomer(TRUE, $args['email']);
    //     $AuthorizeNet->CopyBillingToShipping();

    //Process The Transaction
    $AuthResponse = $this->objAuthorize->ProcessTransaction();
    $transactionStatus = $this->objAuthorize->ApprovalResponse($AuthResponse);
    $returnArr['comment'] = $this->objAuthorize->GetResponseReason($AuthResponse);
    if($transactionStatus == "APPROVED") {
      $returnArr['invoiceid'] = $this->objAuthorize->GetOrderInvoceId($AuthResponse);
      $returnArr['status'] = true;
    }
    else {
      $returnArr['status'] = false;
    }
    return $returnArr;
  }

  function paypal($dataArr) {
    $this->objPaypal->add_field('business', $dataArr['loginId']);

    foreach($dataArr['items'] as $key => $product) {
      $this->objPaypal->add_field('item_number_' . $key, $key);
      $this->objPaypal->add_field('item_name_' . $key, $product['name']);
      $this->objPaypal->add_field('amount_' . $key, $product['amount']);
    }

    $this->objPaypal->add_field('custom', $dataArr['custom'] );
    $this->objPaypal->add_field('return', $dataArr['return']);
    $this->objPaypal->add_field('notify_url', $dataArr['notifyUrl']);

    $this->objPaypal->add_field('currency_code', "USD");
    $this->objPaypal->add_field('cancel_return', $_SERVER['HTTP_REFERER']);

    $this->objPaypal->submit_paypal_post();
    exit();
  }

  function gopaypal() {
    if($this->objPaypal->validate_ipn()) {
      $returnArr['status'] = true;
      $returnArr['ipn_posted_vars'] = $this->objPaypal->ipn_posted_vars;
      $returnArr['ipn_data'] = $this->objPaypal->ipn_data;
      $returnArr['invoiceid'] = $this->objPaypal->ipn_data['txn_id'];
      $returnArr['ipn_response'] = $this->objPaypal->ipn_response;
    }
    else {
      $returnArr['ipn_posted_vars'] = $this->objPaypal->ipn_posted_vars;
      $returnArr['ipn_response'] = $this->objPaypal->ipn_response;
      $returnArr['status'] = false;
    }
    return $returnArr;
  }

  function calGrandTotal($skip=array()) {
    $total = 0.00;
    if(sizeof($this->amt)>0) {
      foreach( $this->amt as $key=>$val) {
        $total += $val;
      }
    }
    return number_format($total,2);
  }
  function payvmoney($perArr) {
    $sett = $perArr['sett'];
    $per_arr = $perArr['data'];

    $cid = $sett['cid'];
    $password = $sett['password'];
    $source_pan = $per_arr['source_pan'];
    $access_code = $per_arr['access_code'];
    $dob = $per_arr['dob'];

    $params = array('cid' => $cid, 'password' => $password,
            'source_pan' => $source_pan, 'access_code' => $access_code,
            'dob' => $dob, 'amount' => $this->amt);
    $result = $this->objsoap->call('SimplePayment', $params, $namespace);
    print "Result $result";
    exit();

    //$params = array($cid, $password, $source_pan,	$access_code, $dob, $this->amt);
    //$returnArr = $this->objsoap->call('SimplePayment', $params);
    //returnArr - ref_id, result_code, result_string
    if($returnArr['result_code']==0000) {
      $returnArr['status'] = true;
      $returnArr ['invoiceid'] = $returnArr['ref_id'];
    }
    else {
      $returnArr['status'] = false;
    }
    $returnArr['comment'] = $returnArr['result_string'];
    return $returnArr;
  }
}
?>