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
$title = __('plugin_settings');
$body = elgg_view_title($title);
$body .= elgg_view($IZAP_ECOMMERCE->forms . 'plugin_settings', array('entity' => IzapEcommerce::izap_get_plugin_entity()));
IzapEcommerce::draw_page($title, $body);

