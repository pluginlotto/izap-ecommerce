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

global $IZAP_ECOMMERCE;
$list_param = izap_get_params(1);

$title = elgg_echo('izap-ecommerce:welcome_to_store');
$body = elgg_view_title($title);
$options['type'] = 'object';
$options['subtype'] = 'izap_ecommerce';
//if($list_param != 'all') {
//  $options['container_guid'] = page_owner();
//}
$options['limit'] = 10;
$options['full_view'] = FALSE;
$options['offset'] = get_input('offset', 0);
$options['view_type_toggle'] = TRUE;
$options['pagination'] = TRUE;

$context = get_context();
set_context('search');
$list =elgg_list_entities($options);
if($list == '') {
  $list = func_izap_bridge_view('views/no_data');
}
$body .= $list;
set_context($context);
//$body .= elgg_view($IZAP_ECOMMERCE->views . 'default');
IzapEcommerce::draw_page($title, $body);
