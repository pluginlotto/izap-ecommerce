<?php
/**************************************************
* PluginLotto.com                                 *
* Copyrights (c) 2005-2010. iZAP                  *
* All rights reserved                             *
***************************************************
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
?>
<div class="contentWrapper izap-product-buy">
  <div class="izap-product-float-left izap-product-buy-rate">
    <b>
      <?php
      echo elggb_echo('rateit');
      ?>
    </b>
    <br />
    <span id="rate_stars">
      <?php echo elgg_view('input/rate', array('entity' => $product));?>
    </span>
  </div>

  <div class="izap-product-float-left izap-product-buy-price">
    <?php $price = $product->getPrice(false);
    if($price) {?>
    <b>
        <?php
        echo __('price');
        ?>
    </b><br />
      <?php
      echo '<b>' . $product->getPrice() . '</b>';
    }
    ?>
  </div>

  <div class="izap-product-float-right izap-product-buy-buynow">
    <?php if($product->isAvailable()) {
      $donwload_link = create_product_download_link_izap_ecommerce(rand(0, 1000), $product->guid);
      if(isadminloggedin()) {
        ?>
    <a href="<?php echo $donwload_link?>">
          <?php _e('download');?>
    </a>
        <?php
      }
      if(!$price) {
        ?>
    <a href="<?php echo $donwload_link?>">
          <?php _e('download');?>
    </a>
        <?php
      }else {
        ?>
    <a href="<?php echo $add_cart_link?>">
          <?php _e('buynow');?>
    </a>
        <?php }
    }else {?>
    <a href="#">
        <?php _e('comming soon');?>
    </a>
      <?php }?>
    <?php if(isloggedin()) {?>
      &nbsp;
    <a href="<?php echo $add_wishlist_link;?>">
      <?php _e('add_to_wishlist');?>
    </a>
      <?php }?>
  </div>

  <div class="clearfloat"></div>
</div>