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
global $IZAP_ECOMMERCE;
$product = elgg_extract('entity', $vars);


$price = $product->getPrice(false);
if ($price) {
  if (isloggedin ()) {
    $price_product = elgg_echo('izap-ecommerce:price');
  } else {
    $price_product = elgg_echo('izap-ecommerce:price_not_more');
  }
  $price_product .= '<b id="product_price_html">' . $product->getPrice() . '</b>';
}
//echo $likes_html;
//echo $price_product;
?>

  <?php
  if ($product->isAvailable()){ ?>
    <div class="izap-product-buy-buynow" >
    <?php
    $form = elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN . '/views/product/buy_options/download', array('entity' => $product));
    ?>
    </div>
    <?php 
    if ($product->canEdit()) {
      echo $form;
    } else {
      if (!$product->canDownload()) {
        //$form .= elgg_view('input/hidden', array('internalname' => 'product_guid', 'value' => $product->guid));
        //$form .= elgg_view('input/submit', array('value' => elgg_echo('izap-ecommerce:buynow')));
        echo elgg_view(GLOBAL_IZAP_ECOMMERCE_PLUGIN.'/views/product/buy_options/add_to_cart',array('entity' =>$product));
      }
      //echo elgg_view('input/form', array('body' => $form, 'action' => $vars['url'] . 'action/izap_ecommerce/add_to_cart'));
    }}
  ?>
  <?php // else {
 ?>
<!--    <a class="button" href="#">-->
<?php //echo elgg_echo('izap-ecommerce:comming soon'); ?>
<!--  </a>-->

