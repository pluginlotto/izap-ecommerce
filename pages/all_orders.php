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
admin_gatekeeper();
global $IZAP_ECOMMERCE;
$title = __('my_orders');
$body = elgg_view_title($title);
$body .= elgg_list_entities(array('type' => 'object', 'subtype' => 'izap_order'));
IzapEcommerce::draw_page($title, $body);
