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

global $CONFIG;
$title = __('my_orders');
$body = elgg_view_title($title);
$body .= list_entities_from_metadata('confirmed', 'no', 'object', 'izap_order', get_loggedin_userid()); // TODO: Change to yes (Only did for testing)
IzapEcommerce::draw_page($title, $body);
