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
gatekeeper();
global $IZAP_ECOMMERCE;
$guid = izap_get_params(1);
$order = get_entity($guid);
//verify_order_izap_ecommerce($order); // TODO:: Uncomment this
$title = __('order_number') . ' - #' . $guid;
$body = elgg_view_title($title);
$body .= elgg_view($IZAP_ECOMMERCE->views . 'order_detail', array('entity' => $order));
IzapEcommerce::draw_page($title, $body);