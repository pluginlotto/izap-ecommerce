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

global $IZAP_ECOMMERCE;
$product = $vars['entity'];
$add_cart_link = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_cart?guid=' . $product->guid);
$add_wishlist_link = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_wishlist?guid=' . $product->guid);

if (!$product->isArchived()) {
  $likes_html = elgg_view('likes/display', array('entity' => $product));
  $price = $product->getPrice(false);
  if ($price) {
    if (isloggedin ()) {
      $price_product = elgg_echo('izap-ecommerce:price');
    } else {
      $price_product = elgg_echo('izap-ecommerce:price_not_more');
    }
    $price_product .= '<b id="product_price_html">' . $product->getPrice() . '</b>';
  }
}
?>
<div class="izap-product-buy">

</div>