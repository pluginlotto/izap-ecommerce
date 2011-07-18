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
//if($product->getPrice(FALSE) <= 0 || elgg_is_admin_logged_in()) {
//  return '';
//}
//$cart_url = elgg_add_action_tokens_to_url($vars['url'] . 'action/izap_ecommerce/add_to_cart?product_guid=' . $product->guid);
//$cart_url = '#';
//if( $product->comming_soon == 'no')
//define('show_buy_now', 'yes');
$color =$product->getPrice(FALSE) <= 0 ? '#4DD18C': '#4690D6';

?>

<span class="price" id="price_span" style="background-color:<?php echo $color ?>">
  <?php echo elgg_echo('izap-ecommerce:price');?>
      <?php
      echo '<span id="product_price_html">' . $product->getPrice() . '</span>' ?>
</span>