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

$old_cart = get_from_session_izap_ecommerce('izap_cart', TRUE);
$product = get_product_izap_ecommerce(get_input('guid'));
if($product) {
  $old_cart[] = $product->guid;
}
add_to_session_izap_ecommerce('izap_cart', array_unique($old_cart));
forward($_SERVER['HTTP_REFERER']);