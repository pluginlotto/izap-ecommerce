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

global $IZAP_ECOMMERCE;

$payment = new IzapPayment('paypal');
$debug = FALSE;
if(get_plugin_usersetting('paypal_test_mode', get_input('owner_guid'), GLOBAL_IZAP_PAYMENT_PLUGIN) == 'yes') {
  $debug = TRUE;
}
$variables = $payment->validate($debug);

if($variables['status'] === TRUE) {
  global $IZAP_ECOMMERCE;

  $paypal_invoice_id = $variables['invoiceid'];
  $order_id = $variables['ipn_data']['custom'];
  $order = get_entity($order_id);
  
  $main_array['confirmed'] = 'yes';
  $main_array['payment_transaction_id'] = $paypal_invoice_id;

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  func_izap_update_metadata($provided);

  IzapEcommerce::sendOrderNotification($order);
}else {
  $order_id = $variables['ipn_data']['custom'];
  $order = get_entity($order_id);

  $main_array['confirmed'] = 'no';
  $main_array['error_status'] = 'Error while paypal notification';
  $main_array['error_time'] = time();
  $main_array['paypal_return'] = serialize($variables);

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  func_izap_update_metadata($provided);
  
  notify_user(
          $order->owner_guid,
          $CONFIG->site->guid,
          elgg_echo('izap-ecommerce:order_processe_error'),
          elgg_echo('izap-ecommerce:order_processe_error_message') . $IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid
  );
}