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
global $CONFIG;
$title = elgg_echo('izap-ecommerce:my_orders');
$body = elgg_view_title($title);
$options['type'] = 'object';
$options['subtype'] = 'izap_order';
$options['owner_guid'] = get_loggedin_userid();
if(func_get_custom_value_byizap(array('plugin' => GLOBAL_IZAP_ECOMMERCE_PLUGIN, 'var' => 'show_not_confirmed_orders')) == 'no') {
  $options['metadata_names'] = 'confirmed';
  $options['metadata_values'] = 'yes';
}
$list = elgg_list_entities_from_metadata($options);
if(empty($list)) {
  $list = func_izap_bridge_view('views/no_data');
}
$body .= $list;
IzapEcommerce::draw_page($title, $body);
