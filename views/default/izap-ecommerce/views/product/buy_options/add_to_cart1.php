
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
if ($product->comming_soon == 'no' && $product->getPrice(FALSE) > 0)
  define('show_buy_now', 'yes');
if ($product->getPrice(FALSE) <= 0) {
   $color = '#4DD18C';
} elseif ($product->getPrice(FALSE) <= 0 && $yes == 1) {
   $color = 'red';
} else {
   $color = '#4690D6';
}
?>
<?php
if ($yes && $vars[entity]->discount >0) : ?>
<?php echo '$yes'.$yes;?>
  <div  class="price izap-line" id="discount_price_span " style="background-color:<?php echo ($yes) ? 'red' : $color ?>; float:none ">
    <?php echo elgg_echo('Actual Prize:$'); ?>
    <?php echo '<span id="product_price_html">' . $product->price . '</span>' ?>
    </div>
<?php endif ?>



