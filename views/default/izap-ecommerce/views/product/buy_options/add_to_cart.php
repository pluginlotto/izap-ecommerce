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
if($product->getPrice(FALSE) <= 0 || elgg_is_admin_logged_in()) {
  return '';
}
$cart_url = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_cart?product_guid=' . $product->guid);
$cart_url = '#';
define('show_buy_now', 'yes');
?>
<div class="download">
<!--  <a href="<?php //echo $cart_url;?>" class="img" id="post_cart_1">-->
<!--    <img src ="<?php echo $vars['url'] . 'mod/' . GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/_graphics/add_to_cart.png' ?>" />-->
<!--  </a>-->
<!--  <a href="<?php //echo $cart_url;?>" class="text" id="post_cart_2">-->
    <?php //echo elgg_echo('izap-ecommerce:add_to_cart');
    ?>
<span class="text">
  <?php echo elgg_echo('izap-ecommerce:price');?>
</span>
    <span class="download_desc">
      <?php
      echo '<span id="product_price_html">' . $product->getPrice() . '</span>' ?>
    </span>
<!--  </a>-->
</div>