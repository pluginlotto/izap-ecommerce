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

global $IZAP_ECOMMERCE;
$cart_id = time();
$cart = get_from_session_izap_ecommerce('izap_cart');

$data_array['loginId'] = get_plugin_setting('paypal_account', $IZAP_ECOMMERCE->plugin_name);

$data_array['items'] = get_from_session_izap_ecommerce('items');
$data_array['grandTotal'] = get_from_session_izap_ecommerce('total_cart_price');
$data_array['return'] = $IZAP_ECOMMERCE->link . 'pay_return';
$data_array['notifyUrl'] = $IZAP_ECOMMERCE->link . 'paypal_notify';

$debug = FALSE;
if(get_plugin_setting('sandbox', $IZAP_ECOMMERCE->plugin_name) == 'yes') {
  $debug = TRUE;
}

$guid = save_order_izap_ecommerce($data_array['items'], $cart_id);
if($guid) {
  add_to_session_izap_ecommerce('cart_id', $cart_id);
  $data_array['custom'] = $guid;
  $gateway = new gateway('paypal', '', $debug);
  $gateway->paypal($data_array);
}else {
  register_error(__('unable_to_save_order'));
  forward($_SERVER['HTTP_REFERER']);
}