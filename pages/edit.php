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
global $IZAP_ECOMMERCE;
$product = get_entity(izap_get_params(1));
if(!$product) {
  forward();
}
$title = __('edit_product');
$body = elgg_view_title($title);
$body .= elgg_view($IZAP_ECOMMERCE->forms . 'add_edit', array('entity' => $product));
IzapEcommerce::draw_page($title, $body);