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
global $CONFIG, $IZAP_ECOMMERCE;
$title = __('add_new_product');
$body = elgg_view_title($title);
$body .= elgg_view($IZAP_ECOMMERCE->forms . 'add_edit');
IzapEcommerce::draw_page($title, $body);