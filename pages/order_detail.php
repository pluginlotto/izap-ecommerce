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

gatekeeper();
global $IZAP_ECOMMERCE;
$guid = izap_get_params(1);
$order = get_entity($guid);
verify_order_izap_ecommerce($order);
$title = elgg_echo('izap-ecommerce:order_number') . ' - #' . $guid;
$body = elgg_view_title($title);
$body .= elgg_view($IZAP_ECOMMERCE->views . 'order_detail', array('entity' => $order));
IzapEcommerce::draw_page($title, $body);