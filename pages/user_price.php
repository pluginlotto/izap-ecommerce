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

admin_gatekeeper();
$user = get_user_by_username(get_input('izap_username'));
$product = get_product_izap_ecommerce(get_input('product_guid'));

if($user && $product) {
  echo elgg_echo('izap-ecommerce:price') . ': ' . $product->getUserPrice($user);
}