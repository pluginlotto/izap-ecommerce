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

include_once dirname(dirname(__FILE__)) . '/lib/gateways/paypal/paypal.php';
include_once dirname(dirname(__FILE__)) . '/lib/gateways/clsGateway.php';

global $IZAP_ECOMMERCE;

$debug = FALSE;
if(get_plugin_setting('sandbox', $IZAP_ECOMMERCE->plugin_name) == 'yes') {
  $debug = TRUE;
}

$gateway = new gateway('paypal', '', $debug);
$variables = $gateway->gopaypal();

if($variables['status']) {
  global $IZAP_ECOMMERCE;

  $paypal_invoice_id = $variables['invoiceid'];
  $order_id = $variables['ipn_data']['custom'];
  $order = get_entity($order_id);
  
  $main_array['confirmed'] = 'yes';
  $main_array['paypal_invoice_id'] = $paypal_invoice_id;

  $provided['entity'] = $order;
  $provided['metadata'] = $main_array;
  func_izap_update_metadata($provided);

  notify_user(
          $order->owner_guid,
          $CONFIG->site->guid,
          __('order_processed'),
          __('order_processed_message') . $IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid
  );
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
          __('order_processe_error'),
          __('order_processe_error_message') . $IZAP_ECOMMERCE->link . 'order_detail/' . $order->guid
  );
}