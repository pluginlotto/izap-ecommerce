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

global $CONFIG, $IZAP_ECOMMERCE;

$product = get_product_izap_ecommerce(get_input('guid'));
$forward_url = $IZAP_ECOMMERCE->link . 'wishlist';
$forward_url = $_SERVER['HTTP_REFERER'];
if($product) {
  if(func_save_wishlist_izap_ecommerce($product->guid)) {
    system_message(elgg_echo('izap-ecommerce:added_to_wishlist'));
  }
}
forward($forward_url);