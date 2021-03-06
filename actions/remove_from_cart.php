<?php

/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * @version 1.0
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
/*
 * action remove product from cart
 */

$cart = get_from_session_izap_ecommerce('izap_cart', TRUE);
$guid = get_input('guid');
$array_key = array_search($guid, $cart);
if ($array_key !== FALSE) {
  unset($cart[$array_key]);
}
add_to_session_izap_ecommerce('izap_cart', array_unique($cart));
forward($_SERVER['HTTP_REFERER']);

