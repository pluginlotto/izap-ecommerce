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

$cart = get_from_session_izap_ecommerce('izap_cart', TRUE);
$guid = get_input('guid');
$array_key = array_search($guid, $cart);
if($array_key !== FALSE) {
  unset ($cart[$array_key]);
}
add_to_session_izap_ecommerce('izap_cart', array_unique($cart));
forward($_SERVER['HTTP_REFERER']);

