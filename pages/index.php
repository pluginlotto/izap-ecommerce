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
$title = __('welcome_to_store');
$body = elgg_view_title($title);
$options['type'] = 'object';
$options['subtype'] = 'izap_ecommerce';
$options['full_view'] = FALSE;
$options['offset'] = get_input('offset', 0);
$options['']
$body .= elgg_list_entities($options);
//$body .= elgg_view($IZAP_ECOMMERCE->views . 'default');
IzapEcommerce::draw_page($title, $body);
