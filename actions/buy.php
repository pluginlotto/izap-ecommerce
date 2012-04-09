<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version 1.0
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */

/**
 * action buy product
 */
gatekeeper();
global $IZAP_ECOMMERCE;
$cart_id = time();
$cart = get_from_session_izap_ecommerce('izap_cart');
$payment_method = get_input('payment_option');
$data_array['items'] = get_from_session_izap_ecommerce('items');
$data_array['grandTotal'] = get_from_session_izap_ecommerce('total_cart_price');

switch ($payment_method) {
  case "paypal":
    $data_array['return'] = $IZAP_ECOMMERCE->link . 'pay_return';
    $data_array['notify_url'] = $IZAP_ECOMMERCE->link . 'paypal_notify?owner_guid=' . get_input('owner_guid', 0);
    break;

  case "alertpay":
    $data_array['ap_returnurl'] = $IZAP_ECOMMERCE->link . 'pay_return';
    break;
}
// save order first but disable it
$order = save_order_izap_ecommerce($data_array['items'], $cart_id);
$order->payment_method = $payment_method;

if ($order->guid !== 0) {
  add_to_session_izap_ecommerce('cart_id', $cart_id);
  $data_array['custom'] = $order->guid;
  $payment = new IzapPayment($payment_method);
  $payment->setParams($data_array);
  $processed = $payment->process((int) get_input('owner_guid'));
  if ($payment_method == 'paypal' || $payment_method == 'alertpay') {
    exit;
  }
  if ($processed['status'] === TRUE) {
    $order->confirmed = 'yes';
    $order->payment_transaction_id = $payment->getTransactionId();

    // save purchased product info with user
    save_order_with_user_izap_ecommerce($order);

    remove_from_session_izap_ecommerce('izap_cart');

    IzapEcommerce::sendOrderNotification($order);
    system_message(elgg_echo('izap-ecommerce:order_success'));
    forward($IZAP_ECOMMERCE->link . 'order/' . $order->guid);
  } else {
    // delete the not processed order
    $order->delete();
    $response = $payment->getResponse();
    $error = (empty($response['error_msg']) ? 'No response from the remote server' : $response['error_msg']);
    //    ECHO elgg_echo('izap-ecommerce:unable_to_process_payment') . ': ' .
//            (empty($response['error_msg']) ? elgg_echo('izap-ecommerce:no_response') : $response['error_msg']);EXIT;
    register_error(elgg_echo('izap-ecommerce:unable_to_process_payment') . $error);
    forward(REFERER);
  }
} else {
  register_error(elgg_echo('izap-ecommerce:unable_to_save_order'));
  forward($_SERVER['HTTP_REFERER']);
}
exit;