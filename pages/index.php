<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
*/

global $IZAP_ECOMMERCE;
$title = __('welcome_to_store');
$body = elgg_view_title($title);
$options['type'] = 'object';
$options['subtype'] = 'izap_ecommerce';
$options['full_view'] = FALSE;
$options['offset'] = get_input('offset', 0);
$body .= elgg_list_entities($options);
//$body .= elgg_view($IZAP_ECOMMERCE->views . 'default');
IzapEcommerce::draw_page($title, $body);
