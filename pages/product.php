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
$guid = izap_get_params(1);
$izap_product = get_product_izap_ecommerce($guid);
if(!$izap_product) {
  register_error(__('invalid_product'));
  forward();
}
$title = $izap_product->title;
$body = elgg_view_entity($izap_product, TRUE);
func_increment_views_byizap($izap_product);
IzapEcommerce::draw_page($title, $body);