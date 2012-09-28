<style type="text/css">
  .izap-line {
  text-decoration:line-through;
  }
</style>

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
$yes = $product->hasUserPurchasedOldVersion(elgg_get_logged_in_user_entity());
if( $product->comming_soon == 'no' && $product->getPrice(FALSE)>0)
define('show_buy_now', 'yes');
$color =$product->getPrice(FALSE) <= 0 ? '#4DD18C': '#4690D6';

if($yes) { $izap_line='izap-line';?>
<div class="price" id="price_span" style="background-color:<?php echo $color ?>">
  <?php echo elgg_echo('izap-ecommerce:price');?>
      <?php
      echo '<span id="product_price_html">' . $product->getPrice() . '</span>' ?>
</div> <?php } ?>
<div class="price <?php echo $izap_line;?>" id="price_span " style="background-color:<?php echo $color; ?>; ">
  <?php echo elgg_echo('Actual Prize:$');?>
      <?php
      echo '<span id="product_price_html">' . $product->price . '</span>' ?>
</div>
