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

$guid = izap_get_params(2);
$izap_product = get_product_izap_ecommerce($guid);
if(!$izap_product) {
  register_error(elgg_echo('izap-ecommerce:invalid_product'));
  forward();
}
$title = $izap_product->title;
$body = elgg_view_entity($izap_product, TRUE);
func_increment_views_byizap($izap_product);
IzapEcommerce::draw_page($title, $body);