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

//include_once dirname(dirname(__FILE__)) . '/lib/gateways/paypal/paypal.php';
//include_once dirname(dirname(__FILE__)) . '/lib/gateways/clsGateway.php';

global $IZAP_ECOMMERCE;
$cart_id = time();
$cart = get_from_session_izap_ecommerce('izap_cart');
$payment_method = get_input('payment_option');
//$data_array['business'] = get_plugin_setting('paypal_account', $IZAP_ECOMMERCE->plugin_name);
$data_array['items'] = get_from_session_izap_ecommerce('items');
$data_array['grandTotal'] = get_from_session_izap_ecommerce('total_cart_price');

if(get_input('payment_option') == 'paypal') {
  $data_array['return'] = $IZAP_ECOMMERCE->link . 'pay_return';
  $data_array['notify_url'] = $IZAP_ECOMMERCE->link . 'paypal_notify';
}

$debug = FALSE;
if(get_plugin_setting('sandbox', $IZAP_ECOMMERCE->plugin_name) == 'yes') {
  $debug = TRUE;
}

$guid = save_order_izap_ecommerce($data_array['items'], $cart_id);
if($guid) {
  add_to_session_izap_ecommerce('cart_id', $cart_id);
  $data_array['custom'] = $guid;
//  $gateway = new gateway('paypal', '', $debug);
//  $gateway->paypal($data_array);
  $payment = new IzapPayment($payment_method);
  $payment->setParams($data_array);
  if($payment->process((int) get_input('owner_guid'))) {
    $order = get_entity($guid);
    $order->confirmed = 'yes';
    $order->payment_transaction_id = $payment->getTransactionId();
    system_message(elgg_echo('izap-ecommerce:order_success'));
    forward($IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid);
  }else {
    $response = $payment->getResponse();
    register_error(__('unable_to_process_payment') . ': ' . $response['error_msg']);
    forward($_SERVER['HTTP_REFERER']);
  }
}else {
  register_error(__('unable_to_save_order'));
  forward($_SERVER['HTTP_REFERER']);
}
exit;