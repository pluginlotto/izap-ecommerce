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
$title = elgg_echo('izap-ecommerce:all_orders');
$body = elgg_view_title($title);
$list = elgg_list_entities(array('type' => 'object', 'subtype' => 'izap_order', 'limit' => 20));
if($list == '') {
  $list = func_izap_bridge_view('views/no_data', array('plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN));
}
$body .= $list;
IzapEcommerce::draw_page($title, $body);
