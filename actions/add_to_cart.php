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

/**
 * action add to cart action
 */
global $IZAP_ECOMMERCE;
$product = get_product_izap_ecommerce(get_input('product_guid'));
if ($product) {

  // work on attributes
  $attrib_groups = $product->getAttributeGroups();
  if ($attrib_groups) {
    foreach ($attrib_groups as $key => $g) {
      $g_arr[$g['name']] = $key;
    }
  }

  foreach ($_POST['product_attribs'] as $key => $att) {
    $group_name = current(explode('|', $key));
    $attrib_array = $product->getAttribute($g_arr[$group_name]);
    $p_att = (array) $att;

    foreach ($p_att as $attrib) {
      $tmp = explode('|', $attrib);
      $att_price = $tmp[0];
      $att_key = $tmp[1];
      if ($att_price) {
        $posted_attribs[$attrib_array[$att_key]['name'] . ' (' . $IZAP_ECOMMERCE->currency_sign . $attrib_array[$att_key]['value'] . ')'] = $attrib_array[$att_key]['value'];
      }
    }
  }
  // set attributes
  $attrib_session_array = get_from_session_izap_ecommerce('izap_cart_attrib', TRUE);
  if (is_array($posted_attribs)) {
    $attrib_session_array[$product->guid] = $posted_attribs;
  }


  $old_cart = get_from_session_izap_ecommerce('izap_cart', TRUE);
  // check if product can be added to cart (price > 0) or has user purchased attribute
  if ($product->getPrice(FALSE) > 0 || is_array($posted_attribs)) {
    // product array
    $old_cart[] = $product->guid;
    // now remove from wishlist
    func_remove_from_wishlist_izap_ecommerce($product->guid);
  }
}

add_to_session_izap_ecommerce('izap_cart', array_unique($old_cart));
add_to_session_izap_ecommerce('izap_cart_attrib', $attrib_session_array);
forward($_SERVER['HTTP_REFERER']);
