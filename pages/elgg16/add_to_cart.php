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

$old_cart = get_from_session_izap_ecommerce('izap_cart', TRUE);
$product = get_product_izap_ecommerce(get_input('guid'));
if($product) {
  $old_cart[] = $product;
}
add_to_session_izap_ecommerce('izap_cart', $old_cart);
forward($_SERVER['HTTP_REFERER']);
