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
$title = __('plugin_settings');
$body = elgg_view_title($title);
$body .= elgg_view($IZAP_ECOMMERCE->forms . 'plugin_settings', array('entity' => IzapEcommerce::izap_get_plugin_entity()));
IzapEcommerce::draw_page($title, $body);

