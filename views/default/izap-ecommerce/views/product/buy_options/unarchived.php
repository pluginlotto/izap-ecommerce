<?php
/* * ************************************************
 * PluginLotto.com                                 *
 * Copyrights (c) 2005-2010. iZAP                  *
 * All rights reserved                             *
 * **************************************************
 * @author iZAP Team "<support@izap.in>"
 * @link http://www.izap.in/
 * Under this agreement, No one has rights to sell this script further.
 * For more information. Contact "Tarun Jangra<tarun@izap.in>"
 * For discussion about corresponding plugins, visit http://www.pluginlotto.com/pg/forums/
 * Follow us on http://facebook.com/PluginLotto and http://twitter.com/PluginLotto
 */
$product = elgg_extract('entity', $vars);
if ($product->isAvailable()) {
?>
  <div class="izap-product-buy-buynow" >
<?php
  echo $form = elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/download', array('entity' => $product));
  echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/add_to_cart', array('entity' => $product));
?>
</div>
<?php
}